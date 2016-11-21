<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Warehouses Controller
 *
 * @property \App\Model\Table\WarehousesTable $Warehouses
 */
class WarehousesController extends AppController
{

    public $paginate = [
        'limit' => 15,
        'order' => [
            'Warehouses.id' => 'desc'
        ]
    ];

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $warehouses = $this->Warehouses->find('all', [
            'conditions' => ['Warehouses.status !=' => 99],
            'contain' => []
        ]);
        $this->set('warehouses', $this->paginate($warehouses));
        $this->set('_serialize', ['warehouses']);
    }

    /**
     * View method
     *
     * @param string|null $id Warehouse id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Auth->user();
        $warehouse = $this->Warehouses->get($id, [
            'contain' => []
        ]);
        $this->set('warehouse', $warehouse);
        $this->set('_serialize', ['warehouse']);
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
        $warehouse = $this->Warehouses->newEntity();
        if ($this->request->is('post')) {

            $data = $this->request->data;
            $data['created_by'] = $user['id'];
            $data['created_date'] = $time;
            $warehouse = $this->Warehouses->patchEntity($warehouse, $data);
            if ($this->Warehouses->save($warehouse)) {
                $this->Flash->success('The warehouse has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The warehouse could not be saved. Please, try again.');
            }
        }
        
        $this->loadModel('AdministrativeLevels');
        $administrativeLevelsData = $this->AdministrativeLevels->find('all', ['conditions' => ['status' => 1]]);
        $administrativeLevels = [];
        foreach($administrativeLevelsData as $administrativeLevelsDatum)
        {
            $administrativeLevels[$administrativeLevelsDatum['level_no']] = $administrativeLevelsDatum['level_name'];
        }
        $this->set(compact('warehouse', 'administrativeLevels'));
        $this->set('_serialize', ['warehouse', 'administrativeLevels']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Warehouse id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Auth->user();
        $time = time();
        $warehouse = $this->Warehouses->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->data;
            $data['updated_by'] = $user['id'];
            $data['updated_date'] = $time;
            $warehouse = $this->Warehouses->patchEntity($warehouse, $data);
            if ($this->Warehouses->save($warehouse)) {
                $this->Flash->success('The warehouse has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The warehouse could not be saved. Please, try again.');
            }
        }
        $this->loadModel('AdministrativeLevels');
        $administrativeLevelsData = $this->AdministrativeLevels->find('all', ['conditions' => ['status' => 1]]);
        $administrativeLevels = [];
        foreach($administrativeLevelsData as $administrativeLevelsDatum)
        {
            $administrativeLevels[$administrativeLevelsDatum['level_no']] = $administrativeLevelsDatum['level_name'];
        }

        $units = TableRegistry::get('administrative_units')->find('all', ['conditions' => ['level_no' => $warehouse['level_no']], 'fields'=>['id', 'unit_name']])->hydrate(false)->toArray();
        $unitDrops = [];
        foreach($units as $unit):
            $unitDrops[$unit['id']] = $unit['unit_name'];
        endforeach;

        $this->set(compact('administrativeLevels', 'warehouse', 'unitDrops'));
        $this->set('_serialize', ['administrativeLevels', 'warehouse', 'unitDrops']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Warehouse id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $warehouse = $this->Warehouses->get($id);

        $user = $this->Auth->user();
        $data = $this->request->data;
        $data['updated_by'] = $user['id'];
        $data['updated_date'] = time();
        $data['status'] = 99;
        $warehouse = $this->Warehouses->patchEntity($warehouse, $data);
        if ($this->Warehouses->save($warehouse)) {
            $this->Flash->success('The warehouse has been deleted.');
        } else {
            $this->Flash->error('The warehouse could not be deleted. Please, try again.');
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
