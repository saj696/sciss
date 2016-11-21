<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use Exception;

/**
 * TransferItems Controller
 *
 * @property \App\Model\Table\TransferItemsTable $TransferItems
 */
class RequestItemsController extends AppController
{
    public $paginate = [
        'limit' => 15,
        'order' => [
            'TransferItems.id' => 'desc'
        ]
    ];

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $user = $this->Auth->user();
        $this->loadModel('TransferResources');
        $this->loadModel('TransferEvents');

        $resources = $this->TransferResources->find('all', [
            'conditions' => ['TransferResources.status !=' => 99, 'TransferResources.created_by'=>$user['id'], 'TransferResources.resource_type'=>array_flip(Configure::read('transfer_resource_types'))['request']],
            'contain' => ['TransferItems', 'TransferEvents']
        ]);

        $events = $this->TransferEvents->find('all', [
            'conditions' => ['TransferEvents.status !=' => 99, 'TransferEvents.recipient_id'=>$user['id']],
            'contain' => ['TransferResources']
        ]);

        $this->loadModel('Items');
        $items = $this->Items->find('all', ['conditions' => ['status' => 1]]);
        $itemArray = [];
        foreach($items as $item) {
            $itemArray[$item['id']] = $item['name'].' - '.$item['pack_size'].' '.Configure::read('pack_size_units')[$item['unit']];
        }

        $this->loadModel('Users');
        $users = $this->Users->find('list', ['conditions'=>['level_no'=>0, 'administrative_unit_id'=>1, 'user_group_id !='=>1,'status'=>1]]);

        $resources = $this->paginate($resources);
        $events = $this->paginate($events);
        $this->set(compact('resources', 'itemArray', 'users', 'events'));
        $this->set('_serialize', ['resources']);
    }

    /**
     * View method
     *
     * @param string|null $id Transfer Item id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Auth->user();
        $this->loadModel('TransferItems');
        $this->loadModel('TransferEvents');
        $this->loadModel('TransferResources');
        $this->loadModel('Users');
        $this->loadModel('Stores');

        $this->loadModel('Items');
        $items = $this->Items->find('all', ['conditions' => ['status' => 1]]);
        $itemArray = [];
        foreach($items as $item) {
            $itemArray[$item['id']] = $item['name'].' - '.$item['pack_size'].' '.Configure::read('pack_size_units')[$item['unit']];
        }

        $stores = $this->Stores->find('list', ['conditions'=>['status !='=>99]])->toArray();

        $event = $this->TransferEvents->get($id);
        $resource = $this->TransferResources->get($event['transfer_resource_id'], ['contain'=>['TransferItems']]);
        $items = $resource['transfer_items'];

        $requestUserInfo = $this->Users->get($event['created_by']);
        $details = [];
        $store_ids = [$user['store_id'], $requestUserInfo['store_id']];

        foreach($items as $item):
            foreach($store_ids as $store_id):
                $ownStore = [];
                $ownStore['store'] = $store_id;
                $ownStore['item'] = $item['item_id'];
                $ownStore['quantity'] = $item['quantity'];
                $details[] = $ownStore;
            endforeach;
        endforeach;

        $this->set(compact('details', 'itemArray', 'stores'));
        $this->set('_serialize', ['itemDetail']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Auth->user();
        $time = time();
        $this->loadModel('TransferItems');
        $this->loadModel('TransferResources');
        $this->loadModel('TransferEvents');
        $requestItem = $this->TransferItems->newEntity();

        if ($this->request->is('post'))
        {
            try {
                $saveStatus = 0;
                $conn = ConnectionManager::get('default');
                $conn->transactional(function () use ($user, $time, &$saveStatus)
                {
                    $input = $this->request->data;
                    $detailArray = $input['details'];
                    $resource = $this->TransferResources->newEntity();

                    if($user['user_group_id']==Configure::read('depot_in_charge_ug')):
                        $resourceData['trigger_type'] = array_flip(Configure::read('trigger_types'))['depot'];
                        $resourceData['trigger_id'] = $user['depot_id'];
                    elseif($user['user_group_id']==Configure::read('warehouse_in_charge_ug')):
                        $resourceData['trigger_type'] = array_flip(Configure::read('trigger_types'))['warehouse'];
                        $resourceData['trigger_id'] = $user['warehouse_id'];
                    endif;

                    $resourceData['resource_type'] = array_flip(Configure::read('transfer_resource_types'))['request'];
                    $resourceData['created_by'] = $user['id'];
                    $resourceData['created_date'] = $time;
                    $resource = $this->TransferResources->patchEntity($resource, $resourceData);
                    $result = $this->TransferResources->save($resource);

                    if(isset($_POST['forward']))
                    {
                        $event = $this->TransferEvents->newEntity();
                        $eventData['transfer_resource_id'] = $result['id'];
                        $eventData['recipient_id'] = $input['recipient_id'];
                        $eventData['recipient_action'] = array_flip(Configure::read('transfer_event_types'))['request'];
                        $eventData['initiated_by'] = $user['id'];
                        $eventData['created_by'] = $user['id'];
                        $eventData['created_date'] = $time;
                        $event = $this->TransferEvents->patchEntity($event, $eventData);
                        $this->TransferEvents->save($event);
                    }

                    foreach($detailArray as $detail)
                    {
                        $transfer = $this->TransferItems->newEntity();
                        $itemData['transfer_resource_id'] = $result['id'];
                        $itemData['item_id'] = $detail['item_id'];
                        $itemData['quantity'] = $detail['quantity'];
                        $itemData['created_by'] = $user['id'];
                        $itemData['created_date'] = $time;
                        $transfer = $this->TransferItems->patchEntity($transfer, $itemData);
                        $this->TransferItems->save($transfer);
                    }

                    // Serials Table Insert/ update
                    $this->loadModel('Serials');
                    $serial_for = array_flip(Configure::read('serial_types'))['transfer_request'];
                    $year = date('Y');

                    if($user['user_group_id']==Configure::read('depot_in_charge_ug')):
                        $trigger_type = array_flip(Configure::read('serial_trigger_types'))['depot'];
                        $trigger_id = $user['depot_id'];
                    elseif($user['user_group_id']==Configure::read('warehouse_in_charge_ug')):
                        $trigger_type = array_flip(Configure::read('serial_trigger_types'))['warehouse'];
                        $trigger_id = $user['warehouse_id'];
                    else:
                        $trigger_type = array_flip(Configure::read('serial_trigger_types'))['others'];
                        $trigger_id = $user['administrative_unit_id'];
                    endif;

                    $existence = $this->Serials->find('all', ['conditions'=>['serial_for'=>$serial_for, 'year'=>$year, 'trigger_type'=>$trigger_type, 'trigger_id'=>$trigger_id]])->first();

                    if ($existence) {
                        $serial = TableRegistry::get('serials');
                        $query = $serial->query();
                        $query->update()->set(['serial_no' => $existence['serial_no']+1])->where(['id' => $existence['id']])->execute();
                        // Update resource serial_no
                        $resource = TableRegistry::get('transfer_resources');
                        $query = $resource->query();
                        $query->update()->set(['serial_no' => $existence['serial_no']+1])->where(['id' => $result['id']])->execute();
                    } else {
                        $serial = $this->Serials->newEntity();
                        $serialData['trigger_type'] = $trigger_type;
                        $serialData['trigger_id'] = $trigger_id;
                        $serialData['serial_for'] = $serial_for;
                        $serialData['year'] = $year;
                        $serialData['serial_no'] = 1;
                        $serialData['created_by'] = $user['id'];
                        $serialData['created_date'] = $time;
                        $serial = $this->Serials->patchEntity($serial, $serialData);
                        $this->Serials->save($serial);
                        // Update resource serial_no
                        $resource = TableRegistry::get('transfer_resources');
                        $query = $resource->query();
                        $query->update()->set(['serial_no' => 1])->where(['id' => $result['id']])->execute();
                    }
                });

                $this->Flash->success('The Request has been made. Thank you!');
                return $this->redirect(['action' => 'index']);
            } catch (Exception $e) {
                echo '<pre>';
                print_r($e);
                echo '</pre>';
                exit;
                $this->Flash->error('The Request has not been made. Please try again!');
                return $this->redirect(['action' => 'index']);
            }
        }

        $this->loadModel('Items');
        $items = $this->Items->find('all', ['conditions' => ['status' => 1]]);
        $dropArray = [];
        foreach($items as $item) {
            $dropArray[$item['id']] = $item['name'].' - '.$item['pack_size'].' '.Configure::read('pack_size_units')[$item['unit']];
        }

        $this->loadModel('UserGroups');
        $userGroups = $this->UserGroups->find('list', ['conditions'=>['status'=>1]]);

        $transferResources = $this->TransferItems->TransferResources->find('list', ['conditions' => ['status' => 1]]);
        $this->set(compact('requestItem', 'dropArray', 'transferResources', 'userGroups'));
        $this->set('_serialize', ['requestItem']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Transfer Item id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit()
    {
        $user = $this->Auth->user();
        $time = time();
        $id = $_REQUEST['id'];
        $recipient_id = $_REQUEST['recipient_id'];

        $this->loadModel('TransferEvents');
        $event = $this->TransferEvents->newEntity();
        $eventData['transfer_resource_id'] = $id;
        $eventData['recipient_id'] = $recipient_id;
        $eventData['recipient_action'] = array_flip(Configure::read('transfer_event_types'))['request'];
        $eventData['initiated_by'] = $user['id'];
        $eventData['created_by'] = $user['id'];
        $eventData['created_date'] = $time;

        $event = $this->TransferEvents->patchEntity($event, $eventData);

        if ($this->TransferEvents->save($event)) {
            $this->Flash->success('Forwarding Complete');
            return $this->redirect(['action' => 'index']);
        } else {
            $this->Flash->error('Forwarding not done. Please, try again.');
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Transfer Item id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->loadModel('TransferItems');
        $requestItem = $this->TransferItems->get($id);

        $user = $this->Auth->user();
        $data = $this->request->data;
        $data['updated_by'] = $user['id'];
        $data['updated_date'] = time();
        $data['status'] = 99;
        $requestItem = $this->TransferItems->patchEntity($requestItem, $data);
        if ($this->TransferItems->save($requestItem)) {
            $this->Flash->success('The request item has been deleted.');
        } else {
            $this->Flash->error('The request item could not be deleted. Please, try again.');
        }
        return $this->redirect(['action' => 'index']);
    }

    public function ajax()
    {
        $data = $this->request->data;
        $user_group = $data['user_group'];
        $dropArray = TableRegistry::get('users')->find('list', ['conditions' => ['user_group_id' => $user_group]])->hydrate(false)->toArray();

        $this->viewBuilder()->layout('ajax');
        $this->set(compact('dropArray'));
    }
}
