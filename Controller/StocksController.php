<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;

/**
 * Stocks Controller
 *
 * @property \App\Model\Table\StocksTable $Stocks
 */
class StocksController extends AppController
{

    public $paginate = [
        'limit' => 15,
        'order' => [
            'Stocks.id' => 'desc'
        ]
    ];

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $stocks = $this->Stocks->find('all', [
            'conditions' => ['Stocks.status !=' => 99],
            'contain' => ['Warehouses', 'Items']
        ]);
        $this->set('stocks', $this->paginate($stocks));
        $this->set('_serialize', ['stocks']);
    }

    /**
     * View method
     *
     * @param string|null $id Stock id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Auth->user();
        $stock = $this->Stocks->get($id, [
            'contain' => ['Warehouses', 'Items']
        ]);
        $this->set('stock', $stock);
        $this->set('_serialize', ['stock']);
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
        $stock = $this->Stocks->newEntity();
        if ($this->request->is('post')) {

            try {
                $saveStatus = 0;
                $conn = ConnectionManager::get('default');
                $conn->transactional(function () use ($user, $time, &$saveStatus)
                {
                    $input = $this->request->data;
                    $detailArray = $input['details'];

                    foreach($detailArray as $detail) {
                        $existing = TableRegistry::get('stocks')->find('all', ['conditions'=>['warehouse_id'=>$input['warehouse_id'], 'item_id'=>$detail['item_id']]])->first();
                        if($existing) {
                            $updateData['quantity'] = $existing['quantity']+$detail['quantity'];
                            $updateData['approved_quantity'] = $existing['approved_quantity']+$detail['approved_quantity'];
                            $stock = $this->Stocks->patchEntity($existing, $updateData);
                            $this->Stocks->save($stock);
                        } else {
                            $stock = $this->Stocks->newEntity();
                            $data['warehouse_id'] = $input['warehouse_id'];
                            $data['item_id'] = $detail['item_id'];
                            $data['quantity'] = $detail['quantity'];
                            $data['approved_quantity'] = $detail['approved_quantity'];
                            $data['created_by'] = $user['id'];
                            $data['created_date'] = $time;
                            $stock = $this->Stocks->patchEntity($stock, $data);
                            $this->Stocks->save($stock);
                        }
                    }
                });

                $this->Flash->success('The Stock has been updated. Thank you!');
                return $this->redirect(['action' => 'index']);
            } catch (\Exception $e) {
                $this->Flash->error('The Stock has not been updated. Please try again!');
                return $this->redirect(['action' => 'index']);
            }
        }

        $this->loadModel('Items');
        $warehouses = $this->Stocks->Warehouses->find('list', ['conditions' => ['status' => 1]]);
        $items = $this->Items->find('all', ['conditions' => ['status' => 1]]);
        $dropArray = [];
        foreach($items as $item) {
            $dropArray[$item['id']] = $item['name'].' - '.$item['pack_size'].' '.Configure::read('pack_size_units')[$item['unit']];
        }
        $this->set(compact('stock', 'warehouses', 'dropArray'));
        $this->set('_serialize', ['stock']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Stock id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Auth->user();
        $time = time();
        $stock = $this->Stocks->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->data;
            $data['updated_by'] = $user['id'];
            $data['updated_date'] = $time;
            $stock = $this->Stocks->patchEntity($stock, $data);
            if ($this->Stocks->save($stock)) {
                $this->Flash->success('The stock has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The stock could not be saved. Please, try again.');
            }
        }
        $warehouses = $this->Stocks->Warehouses->find('list', ['conditions' => ['status' => 1]]);
        $items = $this->Stocks->Items->find('list', ['conditions' => ['status' => 1]]);
        $this->set(compact('stock', 'warehouses', 'items'));
        $this->set('_serialize', ['stock']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Stock id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $stock = $this->Stocks->get($id);

        $user = $this->Auth->user();
        $data = $this->request->data;
        $data['updated_by'] = $user['id'];
        $data['updated_date'] = time();
        $data['status'] = 99;
        $stock = $this->Stocks->patchEntity($stock, $data);
        if ($this->Stocks->save($stock)) {
            $this->Flash->success('The stock has been deleted.');
        } else {
            $this->Flash->error('The stock could not be deleted. Please, try again.');
        }
        return $this->redirect(['action' => 'index']);
    }
}
