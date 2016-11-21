<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Depots Controller
 *
 * @property \App\Model\Table\DepotsTable $Depots
 */
class DepotsController extends AppController
{
    public $paginate = [
        'limit' => 15,
        'order' => [
            'Depots.id' => 'desc'
        ]
    ];

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $depots = $this->Depots->find('all', [
            'conditions' => ['Depots.status !=' => 99],
            'contain' => []
        ]);
        $this->set('depots', $this->paginate($depots));
        $this->set('_serialize', ['depots']);
    }

    /**
     * View method
     *
     * @param string|null $id Depot id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Auth->user();
        $depot = $this->Depots->get($id, [
            'contain' => []
        ]);
        $this->set('depot', $depot);
        $this->set('_serialize', ['depot']);
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
        $depot = $this->Depots->newEntity();
        if ($this->request->is('post')) {

            $data = $this->request->data;
            $data['warehouses'] = json_encode($data['warehouses']);
            $data['created_by'] = $user['id'];
            $data['created_date'] = $time;
            $depot = $this->Depots->patchEntity($depot, $data);
            if ($this->Depots->save($depot)) {
                $this->Flash->success('The depot has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The depot could not be saved. Please, try again.');
            }
        }

        $this->loadModel('AdministrativeLevels');
        $administrativeLevelsData = $this->AdministrativeLevels->find('all', ['conditions' => ['status' => 1]]);
        $administrativeLevels = [];
        foreach($administrativeLevelsData as $administrativeLevelsDatum)
        {
            $administrativeLevels[$administrativeLevelsDatum['level_no']] = $administrativeLevelsDatum['level_name'];
        }
        $this->loadModel('Warehouses');
        $warehouses = $this->Warehouses->find('list', ['conditions'=>['status'=>1]]);
        $this->set(compact('depot', 'administrativeLevels', 'warehouses'));
        $this->set('_serialize', ['depot', 'administrativeLevels']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Depot id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Auth->user();
        $time = time();
        $depot = $this->Depots->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->data;
            $data['warehouses'] = json_encode($data['warehouses']);
            $data['updated_by'] = $user['id'];
            $data['updated_date'] = $time;
            $depot = $this->Depots->patchEntity($depot, $data);
            if ($this->Depots->save($depot)) {
                $this->Flash->success('The depot has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The depot could not be saved. Please, try again.');
            }
        }

        $this->loadModel('AdministrativeLevels');
        $administrativeLevelsData = $this->AdministrativeLevels->find('all', ['conditions' => ['status' => 1]]);
        $administrativeLevels = [];
        foreach($administrativeLevelsData as $administrativeLevelsDatum)
        {
            $administrativeLevels[$administrativeLevelsDatum['level_no']] = $administrativeLevelsDatum['level_name'];
        }

        $units = TableRegistry::get('administrative_units')->find('all', ['conditions' => ['level_no' => $depot['level_no']], 'fields'=>['id', 'unit_name']])->hydrate(false)->toArray();
        $unitDrops = [];
        foreach($units as $unit):
            $unitDrops[$unit['id']] = $unit['unit_name'];
        endforeach;

        $this->loadModel('Warehouses');
        $warehouses = $this->Warehouses->find('list', ['conditions'=>['status'=>1]]);
        $this->set(compact('administrativeLevels', 'depot', 'unitDrops', 'warehouses'));
        $this->set('_serialize', ['administrativeLevels', 'depot', 'unitDrops']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Depot id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {

        $depot = $this->Depots->get($id);

        $user = $this->Auth->user();
        $data = $this->request->data;
        $data['updated_by'] = $user['id'];
        $data['updated_date'] = time();
        $data['status'] = 99;
        $depot = $this->Depots->patchEntity($depot, $data);
        if ($this->Depots->save($depot)) {
            $this->Flash->success('The depot has been deleted.');
        } else {
            $this->Flash->error('The depot could not be deleted. Please, try again.');
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
