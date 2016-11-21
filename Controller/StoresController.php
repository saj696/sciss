<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Stores Controller
 *
 * @property \App\Model\Table\StoresTable $Stores
 */
class StoresController extends AppController
{

    public $paginate = [
        'limit' => 15,
        'order' => [
            'Stores.id' => 'desc'
        ]
    ];

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $stores = $this->Stores->find('all', [
            'conditions' => ['Stores.status !=' => 99],
            'contain' => []
        ]);
        $this->set('stores', $this->paginate($stores));
        $this->set('_serialize', ['stores']);
    }

    /**
     * View method
     *
     * @param string|null $id Store id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Auth->user();
        $store = $this->Stores->get($id, [
            'contain' => []
        ]);
        $this->set('store', $store);
        $this->set('_serialize', ['store']);
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
        $store = $this->Stores->newEntity();
        if ($this->request->is('post')) {

            $data = $this->request->data;
            $data['created_by'] = $user['id'];
            $data['created_date'] = $time;
            $store = $this->Stores->patchEntity($store, $data);
            if ($this->Stores->save($store)) {
                $this->Flash->success('The store has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The store could not be saved. Please, try again.');
            }
        }

        $this->loadModel('AdministrativeLevels');
        $administrativeLevelsData = $this->AdministrativeLevels->find('all', ['conditions' => ['status' => 1]]);
        $administrativeLevels = [];
        foreach($administrativeLevelsData as $administrativeLevelsDatum)
        {
            $administrativeLevels[$administrativeLevelsDatum['level_no']] = $administrativeLevelsDatum['level_name'];
        }
        $this->set(compact('store', 'administrativeLevels'));
        $this->set('_serialize', ['store', 'administrativeLevels']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Store id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Auth->user();
        $time = time();
        $store = $this->Stores->get($id, [
            'contain' => []
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->data;
            $data['updated_by'] = $user['id'];
            $data['updated_date'] = $time;
            $store = $this->Stores->patchEntity($store, $data);
            if ($this->Stores->save($store)) {
                $this->Flash->success('The store has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The store could not be saved. Please, try again.');
            }
        }

        $this->loadModel('AdministrativeLevels');
        $administrativeLevelsData = $this->AdministrativeLevels->find('all', ['conditions' => ['status' => 1]]);
        $administrativeLevels = [];
        foreach($administrativeLevelsData as $administrativeLevelsDatum)
        {
            $administrativeLevels[$administrativeLevelsDatum['level_no']] = $administrativeLevelsDatum['level_name'];
        }

        $units = TableRegistry::get('administrative_units')->find('all', ['conditions' => ['level_no' => $store['level_no']], 'fields'=>['id', 'unit_name']])->hydrate(false)->toArray();
        $unitDrops = [];
        foreach($units as $unit):
            $unitDrops[$unit['id']] = $unit['unit_name'];
        endforeach;

        $this->set(compact('administrativeLevels', 'store', 'unitDrops'));
        $this->set('_serialize', ['administrativeLevels', 'store', 'unitDrops']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Store id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $store = $this->Stores->get($id);
        $user = $this->Auth->user();
        $data = $this->request->data;
        $data['updated_by'] = $user['id'];
        $data['updated_date'] = time();
        $data['status'] = 99;
        $store = $this->Stores->patchEntity($store, $data);
        if ($this->Stores->save($store)) {
            $this->Flash->success('The store has been deleted.');
        } else {
            $this->Flash->error('The store could not be deleted. Please, try again.');
        }
        return $this->redirect(['action' => 'index']);
    }

    public function ajax()
    {
        $data = $this->request->data;
        $level = $data['level'];
        $units = TableRegistry::get('administrative_units')->find('all', ['conditions' => ['level_no' => $level], 'fields'=>['id', 'unit_name']])->hydrate(false)->toArray();

        $dropArray = [];
        foreach($units as $unit):
            $dropArray[$unit['id']] = $unit['unit_name'];
        endforeach;

        $this->viewBuilder()->layout('ajax');
        $this->set(compact('dropArray'));
    }
}
