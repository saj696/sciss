<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

/**
 * AdministrativeUnits Controller
 *
 * @property \App\Model\Table\AdministrativeUnitsTable $AdministrativeUnits
 */
class AdministrativeUnitsController extends AppController
{
    public $paginate = [
        'limit' => 15,
        'order' => [
            'AdministrativeUnits.id' => 'desc'
        ]
    ];

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $administrativeUnits = $this->AdministrativeUnits->find('all', [
            'conditions' => ['AdministrativeUnits.status !=' => 99],
            'contain' => ['AdministrativeLevels']
        ]);
        $this->set('administrativeUnits', $this->paginate($administrativeUnits));
        $this->set('_serialize', ['administrativeUnits']);
    }

    /**
     * View method
     *
     * @param string|null $id Administrative Unit id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Auth->user();
        $administrativeUnit = $this->AdministrativeUnits->get($id, [
            'contain' => ['AdministrativeLevels']
        ]);
        $this->set('administrativeUnit', $administrativeUnit);
        $this->set('_serialize', ['administrativeUnit']);
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
        $administrativeUnit = $this->AdministrativeUnits->newEntity();

        if ($this->request->is('post')) {
            try {
                $saveStatus = 0;
                $conn = ConnectionManager::get('default');
                $conn->transactional(function () use ($user, $time, $administrativeUnit, &$saveStatus)
                {
                    $data = $this->request->data;;
                    $unit_level = $data['administrative_level_id'];

                    $parentLevelInfo = TableRegistry::get('administrative_levels')->find('all', ['conditions' => ['level_no' => $unit_level-1]])->first();
                    $ownLevelInfo = TableRegistry::get('administrative_levels')->find('all', ['conditions' => ['level_no' => $unit_level]])->first();

                    $parentLevelName = $parentLevelInfo['level_name'];
                    $parent_id = $data[$parentLevelName];
                    $parentInfo = TableRegistry::get('administrative_units')->find('all', ['conditions' => ['id' => $parent_id]])->first()->toArray();

                    // Update: increase parent's total successors
                    $AdministrativeUnits = $this->AdministrativeUnits->get($parent_id);
                    $updateData['no_of_direct_successors'] = $parentInfo['no_of_direct_successors']+1;
                    $updateData['prefix'] = $parentInfo['prefix'];
                    $AdministrativeUnits = $this->AdministrativeUnits->patchEntity($AdministrativeUnits, $updateData);
                    $this->AdministrativeUnits->save($AdministrativeUnits);

                    $data['local_id'] = $parentInfo['no_of_direct_successors']+1;
                    $data['global_id'] = $data['local_id']*pow(2, 5*(Configure::read('max_level_no')-$unit_level))+$parentInfo['global_id'];
                    $data['parent'] = $parent_id;
                    $data['administrative_level_id'] = $ownLevelInfo['id'];
                    $data['level_no'] = $unit_level;
                    $data['prefix'] = strtoupper($data['prefix']);
                    $data['created_by'] = $user['id'];
                    $data['created_date'] = $time;
                    $administrativeUnit = $this->AdministrativeUnits->patchEntity($administrativeUnit, $data);
                    $this->AdministrativeUnits->save($administrativeUnit);
                });

                $this->Flash->success('The administrative unit has been saved.');
                return $this->redirect(['action' => 'index']);
            } catch (\Exception $e) {
                echo '<pre>';
                print_r($e);
                echo '</pre>';
                die();
                $this->Flash->error('The administrative unit not saved. Please try again!');
                return $this->redirect(['action' => 'index']);
            }
        }

        $administrativeLevelsData = $this->AdministrativeUnits->AdministrativeLevels->find('all', ['conditions' => ['status' => 1]]);
        $administrativeLevels = [];
        foreach($administrativeLevelsData as $administrativeLevelsDatum)
        {
            $administrativeLevels[$administrativeLevelsDatum['level_no']] = $administrativeLevelsDatum['level_name'];
        }
        $this->set(compact('administrativeUnit', 'administrativeLevels'));
        $this->set('_serialize', ['administrativeUnit']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Administrative Unit id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $this->Flash->error('The administrative unit could not be saved. Please, try again.');
        return $this->redirect(['action' => 'index']);
//        $user = $this->Auth->user();
//        $time = time();
//        $administrativeUnit = $this->AdministrativeUnits->get($id, [
//            'contain' => []
//        ]);
//        if ($this->request->is(['patch', 'post', 'put'])) {
//            $data = $this->request->data;
//            $data['updated_by'] = $user['id'];
//            $data['updated_date'] = $time;
//            $administrativeUnit = $this->AdministrativeUnits->patchEntity($administrativeUnit, $data);
//            if ($this->AdministrativeUnits->save($administrativeUnit)) {
//                $this->Flash->success('The administrative unit has been saved.');
//                return $this->redirect(['action' => 'index']);
//            } else {
//                $this->Flash->error('The administrative unit could not be saved. Please, try again.');
//            }
//        }
//        $administrativeLevels = $this->AdministrativeUnits->AdministrativeLevels->find('list', ['conditions' => ['status' => 1]]);
//        $this->set(compact('administrativeUnit', 'administrativeLevels'));
//        $this->set('_serialize', ['administrativeUnit']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Administrative Unit id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {

//        $administrativeUnit = $this->AdministrativeUnits->get($id);
//
//        $user = $this->Auth->user();
//        $data = $this->request->data;
//        $data['updated_by'] = $user['id'];
//        $data['updated_date'] = time();
//        $data['status'] = 99;
//        $administrativeUnit = $this->AdministrativeUnits->patchEntity($administrativeUnit, $data);
//        if ($this->AdministrativeUnits->save($administrativeUnit)) {
//            $this->Flash->success('The administrative unit has been deleted.');
//        } else {
//            $this->Flash->error('The administrative unit could not be deleted. Please, try again.');
//        }
        $this->Flash->error('The administrative unit could not be deleted. Please, try again.');
        return $this->redirect(['action' => 'index']);
    }

    public function ajax()
    {
        $data = $this->request->data;
        $level = $data['administrative_level'];

        $this->viewBuilder()->layout('ajax');
        $this->set(compact('level'));
    }

    public function hierarchy()
    {
        $data = $this->request->data;
        $level_name = $data['level_name'];
        $level_val = $data['level_val'];
        $next_level_name = $data['next_level_name'];

        $unitInfo = TableRegistry::get('administrative_units')->find('all', ['conditions' => ['id' => $level_val]])->first()->toArray();
        $unitId = $unitInfo['id'];
        $childrenArray = TableRegistry::get('administrative_units')->find('all', ['conditions' => ['parent' => $unitId], 'fields'=>['id', 'unit_name']])->hydrate(false)->toArray();

        $dropArray = [];
        foreach($childrenArray as $child):
            $dropArray[$child['id']] = $child['unit_name'];
        endforeach;

        $this->viewBuilder()->layout('ajax');
        $this->set(compact('dropArray', 'next_level_name'));
    }
}
