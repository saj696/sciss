<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * DepotCoverages Controller
 *
 * @property \App\Model\Table\DepotCoveragesTable $DepotCoverages
 */
class DepotCoveragesController extends AppController
{

    public $paginate = [
        'limit' => 15,
        'order' => [
            'DepotCoverages.id' => 'desc'
        ]
    ];

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $depotCoverages = $this->DepotCoverages->find('all', [
            'conditions' => ['DepotCoverages.status !=' => 99],
            'contain' => ['Depots', 'AdministrativeUnits']
        ]);
        $this->set('depotCoverages', $this->paginate($depotCoverages));
        $this->set('_serialize', ['depotCoverages']);
    }

    /**
     * View method
     *
     * @param string|null $id Depot Coverage id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Auth->user();
        $depotCoverage = $this->DepotCoverages->get($id, [
            'contain' => ['Depots', 'AdministrativeUnits']
        ]);
        $this->set('depotCoverage', $depotCoverage);
        $this->set('_serialize', ['depotCoverage']);
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
        $depotCoverage = $this->DepotCoverages->newEntity();
        if ($this->request->is('post')) {

            $data = $this->request->data;
            $data['created_by'] = $user['id'];
            $data['created_date'] = $time;
            $depotCoverage = $this->DepotCoverages->patchEntity($depotCoverage, $data);
            if ($this->DepotCoverages->save($depotCoverage)) {
                $this->Flash->success('The depot coverage has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The depot coverage could not be saved. Please, try again.');
            }
        }
        $depots = $this->DepotCoverages->Depots->find('list', ['conditions' => ['status' => 1]]);
        $this->loadModel('AdministrativeLevels');
        $administrativeLevelsData = $this->AdministrativeLevels->find('all', ['conditions' => ['status' => 1]]);
        $administrativeLevels = [];
        foreach($administrativeLevelsData as $administrativeLevelsDatum)
        {
            $administrativeLevels[$administrativeLevelsDatum['level_no']] = $administrativeLevelsDatum['level_name'];
        }
        $this->set(compact('depotCoverage', 'depots', 'administrativeLevels'));
        $this->set('_serialize', ['depotCoverage']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Depot Coverage id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Auth->user();
        $time = time();
        $depotCoverage = $this->DepotCoverages->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->data;
            $data['updated_by'] = $user['id'];
            $data['updated_date'] = $time;
            $depotCoverage = $this->DepotCoverages->patchEntity($depotCoverage, $data);
            if ($this->DepotCoverages->save($depotCoverage)) {
                $this->Flash->success('The depot coverage has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The depot coverage could not be saved. Please, try again.');
            }
        }

        $depots = $this->DepotCoverages->Depots->find('list', ['conditions' => ['status' => 1]]);
        $this->loadModel('AdministrativeLevels');
        $administrativeLevelsData = $this->AdministrativeLevels->find('all', ['conditions' => ['status' => 1]]);
        $administrativeLevels = [];
        foreach($administrativeLevelsData as $administrativeLevelsDatum)
        {
            $administrativeLevels[$administrativeLevelsDatum['level_no']] = $administrativeLevelsDatum['level_name'];
        }

        $administrativeUnits = $this->DepotCoverages->AdministrativeUnits->find('list', ['conditions' => ['status' => 1, 'level_no'=>$depotCoverage['level_no']]]);
        $this->set(compact('depotCoverage', 'depots', 'administrativeLevels', 'administrativeUnits'));
        $this->set('_serialize', ['depotCoverage']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Depot Coverage id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {

        $depotCoverage = $this->DepotCoverages->get($id);

        $user = $this->Auth->user();
        $data = $this->request->data;
        $data['updated_by'] = $user['id'];
        $data['updated_date'] = time();
        $data['status'] = 99;
        $depotCoverage = $this->DepotCoverages->patchEntity($depotCoverage, $data);
        if ($this->DepotCoverages->save($depotCoverage)) {
            $this->Flash->success('The depot coverage has been deleted.');
        } else {
            $this->Flash->error('The depot coverage could not be deleted. Please, try again.');
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
