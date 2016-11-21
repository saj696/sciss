<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use Cake\Collection\Collection;

/**
 * TransferItems Controller
 *
 * @property \App\Model\Table\TransferItemsTable $TransferItems
 */
class ReceiveItemsController extends AppController
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
        $this->loadModel('Warehouses');
        $this->loadModel('Items');
        $this->loadModel('Depots');
        $this->loadModel('Users');

        $events = $this->TransferEvents->find('all', [
            'conditions' => ['TransferEvents.status !=' => 99, 'is_action_taken'=>0,'TransferEvents.recipient_id'=>$user['id'], 'recipient_action'=>array_flip(Configure::read('transfer_event_types'))['receive']],
            'contain' => ['TransferResources'=>['TransferItems']]
        ]);

        $items = $this->Items->find('all', ['conditions' => ['status' => 1]]);
        $itemArray = [];
        foreach($items as $item) {
            $itemArray[$item['id']] = $item['name'].' - '.$item['pack_size'].' '.Configure::read('pack_size_units')[$item['unit']];
        }

        $warehouses = $this->Warehouses->find('list', ['conditions'=>['status'=>1]])->toArray();
        $depots = $this->Depots->find('list', ['conditions'=>['status'=>1]])->toArray();
        $users = $this->Users->find('list', ['conditions'=>['user_group_id !='=>1,'status'=>1]]);
        $events = $this->paginate($events);

        $userLevelWarehouses = $this->Warehouses->find('all', ['conditions'=>['status'=>1, 'unit_id'=>$user['administrative_unit_id']], 'fields'=>['id']])->hydrate(false)->toArray();
        $myLevelWarehouses = [];
        foreach($userLevelWarehouses as $userLevelWarehouse):
            $myLevelWarehouses[] = $userLevelWarehouse['id'];
        endforeach;

        $userDepotInfo = $this->Depots->get($user['depot_id']);
        $depotWarehouses = json_decode($userDepotInfo['warehouses'], true);

        $this->set(compact('itemArray', 'users', 'events', 'warehouses', 'depots', 'myLevelWarehouses', 'depotWarehouses'));
        $this->set('_serialize', ['events']);
    }

    public function distribute($id=null)
    {
        $user = $this->Auth->user();
        $this->loadModel('TransferEvents');
        $this->loadModel('Stocks');
        $this->loadModel('Depots');

        try {
            $saveStatus = 0;
            $conn = ConnectionManager::get('default');
            $conn->transactional(function () use ($user, $id, &$saveStatus)
            {
                $userDepotInfo = $this->Depots->get($user['depot_id']);
                $depotWarehouses = json_decode($userDepotInfo['warehouses'], true);

                if(sizeof($depotWarehouses)>1) {
                    $this->Flash->error('Handling multiple warehouses in a depot not possible right now! Plz contact with SoftBD Ltd.');
                    return $this->redirect(['action' => 'index']);
                } else {
                    // Event action update
                    $transferEvent = TableRegistry::get('transfer_events');
                    $query = $transferEvent->query();
                    $query->update()->set(['is_action_taken' => 1])->where(['id' => $id])->execute();

                    // Stock Update
                    $event = $this->TransferEvents->get($id, ['contain' => ['TransferResources'=>['TransferItems']]]);

                    if(sizeof($event['transfer_resource']['transfer_items'])>0):
                        foreach($event['transfer_resource']['transfer_items'] as $itemInfo):
                            $existingStock = $this->Stocks->find('all', ['conditions'=>['warehouse_id'=>$depotWarehouses[0], 'item_id'=>$itemInfo['item_id']]])->first();
                            $stocks = TableRegistry::get('stocks');
                            $query = $stocks->query();
                            $query->update()->set(['quantity' => $existingStock['quantity']+$itemInfo['quantity']])->where(['id' => $existingStock['id']])->execute();
                        endforeach;
                    endif;

                    $this->Flash->success('Successfully received. Thank you!');
                    return $this->redirect(['action' => 'index']);
                }
            });
        } catch (\Exception $e) {
            echo '<pre>';
            print_r($e);
            echo '</pre>';
            exit;
            $this->Flash->error('Not Received. Please try again!');
        }

        $this->autoRender = false;
    }

    public function receive($id=null)
    {
        $user = $this->Auth->user();
        $this->loadModel('TransferEvents');
        $this->loadModel('Stocks');

        try {
            $saveStatus = 0;
            $conn = ConnectionManager::get('default');
            $conn->transactional(function () use ($user, $id, &$saveStatus)
            {
                // Event action update
                $transferEvent = TableRegistry::get('transfer_events');
                $query = $transferEvent->query();
                $query->update()->set(['is_action_taken' => 1])->where(['id' => $id])->execute();

                // Stock Update
                $event = $this->TransferEvents->get($id, ['contain' => ['TransferResources'=>['TransferItems']]]);

                if(sizeof($event['transfer_resource']['transfer_items'])>0):
                    foreach($event['transfer_resource']['transfer_items'] as $itemInfo):
                        $existingStock = $this->Stocks->find('all', ['conditions'=>['warehouse_id'=>$user['warehouse_id'], 'item_id'=>$itemInfo['item_id']]])->first();
                        $stocks = TableRegistry::get('stocks');
                        $query = $stocks->query();
                        $query->update()->set(['quantity' => $existingStock['quantity']+$itemInfo['quantity']])->where(['id' => $existingStock['id']])->execute();
                    endforeach;
                endif;
            });
            $this->Flash->success('Successfully received. Thank you!');
        } catch (\Exception $e) {
            echo '<pre>';
            print_r($e);
            echo '</pre>';
            exit;
            $this->Flash->error('Not Received. Please try again!');
        }

        return $this->redirect(['action' => 'index']);
    }
}
