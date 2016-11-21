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
class DecidedRequestsController extends AppController
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
            'conditions' => ['TransferEvents.status !=' => 99, 'TransferEvents.recipient_id'=>$user['id'], 'recipient_action'=>array_flip(Configure::read('transfer_event_types'))['make_challan_forward_send_delivery']],
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

        $forwardingUsers = $this->Users->find('list', ['conditions'=>['user_group_id'=>Configure::read('depot_in_charge_ug'), 'status'=>1]])->orWhere(['user_group_id'=>Configure::read('warehouse_in_charge_ug')]);

        $this->set(compact('itemArray', 'users', 'events', 'warehouses', 'depots', 'myLevelWarehouses', 'forwardingUsers'));
        $this->set('_serialize', ['events']);
    }

    public function chalan()
    {
        $user = $this->Auth->user();
        $data = $this->request->data;
        if($data) {
            $this->loadModel('TransferEvents');
            $this->loadModel('Serials');
            $this->loadModel('Items');
            $eventIds = $data['chalan_event'];

            $items = $this->Items->find('all', ['conditions' => ['status' => 1]]);
            $itemArray = [];
            foreach($items as $item) {
                $itemArray[$item['id']] = $item['name'].' - '.$item['pack_size'].' '.Configure::read('pack_size_units')[$item['unit']];
            }

            $info = [];
            foreach($eventIds as $eventId):
                $event = $this->TransferEvents->get($eventId, ['contain' => ['TransferResources'=>['TransferItems']]]);
                $items = $event['transfer_resource']['transfer_items'];
                foreach($items as $item):
                    $arr = [];
                    $arr['item_id'] = $item['item_id'];
                    $arr['quantity'] = $item['quantity'];
                    $info[] = $arr;
                endforeach;
            endforeach;

            $returnData = [];
            foreach ($info as $key=>$item) {
                $key = $item['item_id'];
                if (!array_key_exists($key, $returnData)) {
                    $returnData[$key] = [
                        'item_id' => $item['item_id'],
                        'quantity' => $item['quantity']
                    ];
                } else {
                    $returnData[$key]['quantity'] = $returnData[$key]['quantity'] + $item['quantity'];
                }
                $key++;
            }

            // Serial Management
            $this->loadModel('Serials');
            $serial_for = array_flip(Configure::read('serial_types'))['transfer_chalan'];
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
                $sl_no = $existence['serial_no']+1;
            } else {
                $serial = $this->Serials->newEntity();
                $serialData['trigger_type'] = $trigger_type;
                $serialData['trigger_id'] = $trigger_id;
                $serialData['serial_for'] = $serial_for;
                $serialData['year'] = $year;
                $serialData['serial_no'] = 1;
                $serialData['created_by'] = $user['id'];
                $serialData['created_date'] = time();
                $serial = $this->Serials->patchEntity($serial, $serialData);
                $this->Serials->save($serial);
                $sl_no = 1;
            }

            $this->set(compact('returnData', 'itemArray', 'sl_no', 'eventIds'));
        } else {
            $this->Flash->error('Please come again sequentially. Thank you!');
            return $this->redirect(['action' => 'index']);
        }
    }

    public function forward()
    {
        $user = $this->Auth->user();
        $time = time();
        $this->loadModel('TransferEvents');
        if ($this->request->is('post'))
        {
            try {
                $saveStatus = 0;
                $conn = ConnectionManager::get('default');
                $conn->transactional(function () use ($user, $time, &$saveStatus)
                {
                    $input = $this->request->data;
                    $event_id = $input['event_id'];
                    $forward_user = $input['forward_user'];
                    if($forward_user && $forward_user>0) {
                        $event = $this->TransferEvents->get($event_id, ['contain' => ['TransferResources']]);

                        // New event
                        $eventTbl = $this->TransferEvents->newEntity();
                        $eventData['transfer_resource_id'] = $event['transfer_resource_id'];
                        $eventData['recipient_id'] = $forward_user;
                        $eventData['recipient_action'] = array_flip(Configure::read('transfer_event_types'))['deliver'];
                        $eventData['initiated_by'] = $event['initiated_by'];
                        $eventData['created_by'] = $user['id'];
                        $eventData['created_date'] = $time;
                        $eventTbl = $this->TransferEvents->patchEntity($eventTbl, $eventData);
                        $this->TransferEvents->save($eventTbl);

                        // Update event
                        $transfer_events = TableRegistry::get('transfer_events');
                        $query = $transfer_events->query();
                        $query->update()->set(['is_action_taken' => 1])->where(['id' => $event_id])->execute();

                        $this->Flash->success('The Forwarding is done. Thank you!');
                    } else {
                        $this->Flash->error('Please select a user to forward. Thank you!');
                    }
                    return $this->redirect(['action' => 'index']);
                });
            } catch (\Exception $e) {
                echo '<pre>';
                print_r($e);
                echo '</pre>';
                exit;
                $this->Flash->error('The Forwarding not done. Please try again!');
            }
        }
        return $this->redirect(['action' => 'index']);
    }

    public function sendDelivery($id = null)
    {
        $user = $this->Auth->user();
        $time = time();
        $this->loadModel('TransferItems');
        $this->loadModel('TransferEvents');
        $this->loadModel('TransferResources');
        $this->loadModel('Users');
        $this->loadModel('Warehouses');
        $this->loadModel('Depots');
        $this->loadModel('Items');
        $this->loadModel('Stocks');
        $this->loadModel('Serials');

        try {
            $saveStatus = 0;
            $conn = ConnectionManager::get('default');
            $conn->transactional(function () use ($user, $id, $time, &$saveStatus)
            {
                $event = $this->TransferEvents->get($id, ['contain' => ['TransferResources'=>['TransferItems']]]);
                // Serials Table Insert/ update
                $serial_for = array_flip(Configure::read('serial_types'))['transfer_chalan'];
                $year = date('Y');
                $trigger_type = array_flip(Configure::read('serial_trigger_types'))['warehouse'];
                $trigger_id = $event['transfer_resource']['transfer_items'][0]['warehouse_id'];
                $existence = $this->Serials->find('all', ['conditions'=>['serial_for'=>$serial_for, 'year'=>$year, 'trigger_type'=>$trigger_type, 'trigger_id'=>$trigger_id]])->first();

                if ($existence) {
                    $serial = TableRegistry::get('serials');
                    $query = $serial->query();
                    $query->update()->set(['serial_no' => $existence['serial_no']+1])->where(['id' => $existence['id']])->execute();
                    $serial_no = $existence['serial_no']+1;
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
                    $serial_no = 1;
                }
                // Resource entry
                $resource = $this->TransferResources->newEntity();
                $resourceData['reference_resource_id'] = $event['transfer_resource_id'];
                $resourceData['resource_type'] = array_flip(Configure::read('transfer_resource_types'))['receive_delivery'];
                $resourceData['trigger_type'] = array_flip(Configure::read('trigger_types'))['warehouse'];
                $resourceData['trigger_id'] = $event['transfer_resource']['transfer_items'][0]['warehouse_id'];
                $resourceData['serial_no'] = $serial_no;
                $resourceData['created_by'] = $user['id'];
                $resourceData['created_date'] = $time;
                $resource = $this->TransferResources->patchEntity($resource, $resourceData);
                $resourceResult = $this->TransferResources->save($resource);

                // Corresponding event entry
                $eventTbl = $this->TransferEvents->newEntity();
                $eventData['transfer_resource_id'] = $resourceResult['id'];
                $eventData['recipient_id'] = $event['initiated_by'];
                $eventData['recipient_action'] = array_flip(Configure::read('transfer_event_types'))['receive'];
                $eventData['initiated_by'] = $event['initiated_by'];
                $eventData['created_by'] = $user['id'];
                $eventData['created_date'] = $time;
                $eventTbl = $this->TransferEvents->patchEntity($eventTbl, $eventData);
                $this->TransferEvents->save($eventTbl);

                // Corresponding item entry
                if(sizeof($event['transfer_resource']['transfer_items'])>0):
                    foreach($event['transfer_resource']['transfer_items'] as $itemInfo):
                        $item = $this->TransferItems->newEntity();
                        $itemData['transfer_resource_id'] = $resourceResult['id'];
                        $itemData['item_id'] = $itemInfo['item_id'];
                        $itemData['quantity'] = $itemInfo['quantity'];
                        $itemData['warehouse_id'] = $itemInfo['warehouse_id'];
                        $itemData['created_by'] = $user['id'];
                        $itemData['created_date'] = $time;
                        $item = $this->TransferItems->patchEntity($item, $itemData);
                        $this->TransferItems->save($item);

                        // Reduce Stocks
                        $existingStock = $this->Stocks->find('all', ['conditions'=>['warehouse_id'=>$itemInfo['warehouse_id'], 'item_id'=>$itemInfo['item_id']]])->first();
                        $stocks = TableRegistry::get('stocks');
                        $query = $stocks->query();
                        $query->update()->set(['quantity' => $existingStock['quantity']-$itemInfo['quantity']])->where(['id' => $existingStock['id']])->execute();
                    endforeach;
                endif;

                // Event update with is_action_taken=1
                $transfer_events = TableRegistry::get('transfer_events');
                $query = $transfer_events->query();
                $query->update()->set(['is_action_taken' => 1])->where(['id' => $id])->execute();
            });
            $this->Flash->success('The delivery has been made. Thank you!');
            return $this->redirect(['action' => 'index']);
        } catch (\Exception $e) {
            echo '<pre>';
            print_r($e);
            echo '</pre>';
            exit;
            $this->Flash->error('The delivery has not been made. Please try again!');
            return $this->redirect(['action' => 'index']);
        }

        $this->autoRender = false;
    }

    public function chalanForward()
    {
        $this->loadModel('TransferItems');
        $this->loadModel('TransferEvents');
        $this->loadModel('TransferResources');
        $this->loadModel('Users');
        $data = $this->request->data;

        foreach($data['eventIds'] as $eventId):
            $event = $this->TransferEvents->get($eventId, ['contains'=>['TransferResources'=>['TransferItems']]]);
            $warehouseId = $event['transfer_resource']['transfer_items'][0]['warehouse_id'];
            $warehouseInChargeData = $this->Users->find('all', ['conditions'=>['warehouse_id'=>$warehouseId], 'fields'=>['id']])->first();
            $warehouseInChargeId = $warehouseInChargeData['id'];
        endforeach;
    }
}
