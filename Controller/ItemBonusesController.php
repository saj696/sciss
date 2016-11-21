<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;

/**
 * ItemBonuses Controller
 *
 * @property \App\Model\Table\ItemBonusesTable $ItemBonuses
 */
class ItemBonusesController extends AppController
{

    public $paginate = [
        'limit' => 15,
        'order' => [
            'ItemBonuses.id' => 'desc'
        ]
    ];

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $itemBonuses = $this->ItemBonuses->find('all', [
            'conditions' => ['ItemBonuses.status !=' => 99],
            'contain' => ['Items']
        ]);
        $this->set('itemBonuses', $this->paginate($itemBonuses));
        $this->set('_serialize', ['itemBonuses']);
    }

    /**
     * View method
     *
     * @param string|null $id Item Bonus id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Auth->user();
        $itemBonus = $this->ItemBonuses->get($id, [
            'contain' => ['Items']
        ]);
        $this->set('itemBonus', $itemBonus);
        $this->set('_serialize', ['itemBonus']);
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
        $itemBonus = $this->ItemBonuses->newEntity();

        if ($this->request->is('post')) {
            try {
                $saveStatus = 0;
                $conn = ConnectionManager::get('default');
                $conn->transactional(function () use ($user, $time, &$saveStatus)
                {
                    $data = $this->request->data;
                    $detailArray = $data['details'];

                    foreach($detailArray as $detail) {
                        $itemBonus = $this->ItemBonuses->newEntity();
                        $data['item_id'] = $detail['item_id'];
                        $data['order_quantity'] = $detail['order_quantity'];
                        $data['bonus_quantity'] = $detail['bonus_quantity'];
                        $data['created_by'] = $user['id'];
                        $data['created_date'] = $time;
                        $itemBonus = $this->ItemBonuses->patchEntity($itemBonus, $data);
                        $this->ItemBonuses->save($itemBonus);
                    }
                });

                $this->Flash->success('The item bonus has been saved.');
                return $this->redirect(['action' => 'index']);
            } catch (\Exception $e) {
                echo '<pre>';
                print_r($e);
                echo '</pre>';
                die();
                $this->Flash->error('The item bonus has not been saved. Please try again!');
                return $this->redirect(['action' => 'index']);
            }
        }

        $items = $this->ItemBonuses->Items->find('all', ['conditions' => ['status' => 1]]);
        $dropArray = [];
        foreach($items as $item) {
            $dropArray[$item['id']] = $item['name'].' - '.$item['pack_size'].' '.Configure::read('pack_size_units')[$item['unit']];
        }

        $this->set(compact('itemBonus', 'items', 'dropArray'));
        $this->set('_serialize', ['itemBonus']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Item Bonus id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Auth->user();
        $time = time();
        $itemBonus = $this->ItemBonuses->get($id, [
            'contain' => []
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->data;
            $data['updated_by'] = $user['id'];
            $data['updated_date'] = $time;
            $itemBonus = $this->ItemBonuses->patchEntity($itemBonus, $data);

            if ($this->ItemBonuses->save($itemBonus)) {
                $this->Flash->success('The item bonus has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The item bonus could not be saved. Please, try again.');
            }
        }

        $items = $this->ItemBonuses->Items->find('all', ['conditions' => ['status' => 1]]);
        $dropArray = [];
        foreach($items as $item) {
            $dropArray[$item['id']] = $item['name'].' - '.$item['pack_size'].' '.Configure::read('pack_size_units')[$item['unit']];
        }

        $this->set(compact('itemBonus', 'items', 'dropArray'));
        $this->set('_serialize', ['itemBonus']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Item Bonus id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {

        $itemBonus = $this->ItemBonuses->get($id);

        $user = $this->Auth->user();
        $data = $this->request->data;
        $data['updated_by'] = $user['id'];
        $data['updated_date'] = time();
        $data['status'] = 99;
        $itemBonus = $this->ItemBonuses->patchEntity($itemBonus, $data);
        if ($this->ItemBonuses->save($itemBonus)) {
            $this->Flash->success('The item bonus has been deleted.');
        } else {
            $this->Flash->error('The item bonus could not be deleted. Please, try again.');
        }
        return $this->redirect(['action' => 'index']);
    }
}
