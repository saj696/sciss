<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use stdClass;

/**
 * ReducedStocks Controller
 *
 * @property \App\Model\Table\ReducedStocksTable $ReducedStocks
 */
class ReducedStocksController extends AppController
{

    public $paginate = [
        'limit' => 15,
        'order' => [
            'ReducedStocks.id' => 'desc'
        ]
    ];

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $reducedStocks = $this->ReducedStocks->find('all', [
            'conditions' => ['ReducedStocks.status !=' => 99],
            'contain' => ['Warehouses', 'Items']
        ]);
        $this->set('reducedStocks', $this->paginate($reducedStocks));
        $this->set('_serialize', ['reducedStocks']);
    }

    /**
     * View method
     *
     * @param string|null $id Reduced Stock id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Auth->user();
        $reducedStock = $this->ReducedStocks->get($id, [
            'contain' => ['Warehouses', 'Items']
        ]);
        $this->set('reducedStock', $reducedStock);
        $this->set('_serialize', ['reducedStock']);
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
        $reducedStock = $this->ReducedStocks->newEntity();
        if ($this->request->is('post')) {

            try {
                $saveStatus = 0;
                $conn = ConnectionManager::get('default');
                $conn->transactional(function () use ($user, $time, &$saveStatus)
                {
                    $input = $this->request->data;
                    $this->loadModel('Stocks');
                    $detailArray = $input['details'];

                    foreach($detailArray as $detail) {
                        $existing = TableRegistry::get('stocks')->find('all', ['conditions'=>['warehouse_id'=>$input['warehouse_id'], 'item_id'=>$detail['item_id']]])->first();
                        $updateData['quantity'] = $existing['quantity']-$detail['quantity'];
                        $stock = $this->Stocks->patchEntity($existing, $updateData);
                        $this->Stocks->save($stock);

                        $reducedStock = $this->ReducedStocks->newEntity();
                        $data['warehouse_id'] = $input['warehouse_id'];
                        $data['item_id'] = $detail['item_id'];
                        $data['type'] = $detail['type'];
                        $data['quantity'] = $detail['quantity'];
                        $data['created_by'] = $user['id'];
                        $data['created_date'] = $time;
                        $reducedStock = $this->ReducedStocks->patchEntity($reducedStock, $data);
                        $this->ReducedStocks->save($reducedStock);
                    }
                });

                $this->Flash->success('The Stock has been updated. Thank you!');
                return $this->redirect(['action' => 'index']);
            } catch (\Exception $e) {
                echo '<pre>';
                print_r($e);
                echo '</pre>';
                exit;
                $this->Flash->error('The Stock has not been updated. Please try again!');
                return $this->redirect(['action' => 'index']);
            }
        }
        $warehouses = $this->ReducedStocks->Warehouses->find('list', ['conditions' => ['status' => 1]]);
        $items = $this->ReducedStocks->Items->find('list', ['conditions' => ['status' => 1]]);
        $this->set(compact('reducedStock', 'warehouses', 'items'));
        $this->set('_serialize', ['reducedStock']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Reduced Stock id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Auth->user();
        $time = time();
        $reducedStock = $this->ReducedStocks->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->data;
            $data['updated_by'] = $user['id'];
            $data['updated_date'] = $time;
            $reducedStock = $this->ReducedStocks->patchEntity($reducedStock, $data);
            if ($this->ReducedStocks->save($reducedStock)) {
                $this->Flash->success('The reduced stock has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The reduced stock could not be saved. Please, try again.');
            }
        }
        $warehouses = $this->ReducedStocks->Warehouses->find('list', ['conditions' => ['status' => 1]]);
        $items = $this->ReducedStocks->Items->find('list', ['conditions' => ['status' => 1]]);
        $this->set(compact('reducedStock', 'warehouses', 'items'));
        $this->set('_serialize', ['reducedStock']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Reduced Stock id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {

        $reducedStock = $this->ReducedStocks->get($id);

        $user = $this->Auth->user();
        $data = $this->request->data;
        $data['updated_by'] = $user['id'];
        $data['updated_date'] = time();
        $data['status'] = 99;
        $reducedStock = $this->ReducedStocks->patchEntity($reducedStock, $data);
        if ($this->ReducedStocks->save($reducedStock)) {
            $this->Flash->success('The reduced stock has been deleted.');
        } else {
            $this->Flash->error('The reduced stock could not be deleted. Please, try again.');
        }
        return $this->redirect(['action' => 'index']);
    }

    public function ajax()
    {
        $data = $this->request->data;
        $store = $data['store'];

        $items = TableRegistry::get('stocks')->find()->hydrate(false)
            ->where(['stocks.warehouse_id' => $store])
            ->select(['stocks.item_id', 'stocks.quantity', 'stocks.approved_quantity'])
            ->select(['items.name', 'items.pack_size', 'items.unit'])
            ->innerJoin('items', 'items.id = stocks.item_id')
            ->hydrate(false)
            ->toArray();

        $dropArray = [];
        foreach($items as $item):
            $dropArray[$item['item_id']] = $item['items']['name'].' - '.$item['items']['pack_size'].' '.Configure::read('pack_size_units')[$item['items']['unit']];
        endforeach;

        $this->viewBuilder()->layout('ajax');
        $this->set(compact('dropArray'));
    }

    public function existing()
    {
        $data = $this->request->data;

        $item_id = $data['item_id'];
        $warehouse_id = $data['store_id'];
        $item = TableRegistry::get('stocks')->find('all', ['conditions' => ['warehouse_id' => $warehouse_id, 'item_id'=>$item_id]])->first()->toArray();

        $this->response->body($item['quantity']);
        $this->autoRender = false;
        return $this->response;
    }
}
