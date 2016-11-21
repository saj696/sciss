<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    public $paginate = [
        'limit' => 15,
        'order' => [
            'Users.id' => 'desc'
        ]
    ];

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $users = $this->Users->find('all', [
            'conditions' => ['Users.status !=' => 99],
            'contain' => ['AdministrativeUnits', 'UserGroups']
        ]);
        $this->set('users', $this->paginate($users));
        $this->set('_serialize', ['users']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Auth->user();
        $user = $this->Users->get($id, [
            'contain' => ['AdministrativeUnits', 'Warehouses', 'Depots', 'UserGroups']
        ]);
        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $userInfo = $this->Auth->user();
        $time = time();
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {

            $data = $this->request->data;

            if(($data['password']) != ($data['confirm_password'])){
                $this->Flash->error('Password Does Not Match');
                return $this->redirect(['action' => 'add']);
            }

            $data['created_by'] = $userInfo['id'];
            $data['created_date'] = $time;
            $user = $this->Users->patchEntity($user, $data);

            if ($this->Users->save($user)) {
                $this->Flash->success('The user has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The user could not be saved. Please, try again.');
            }
        }

        $this->loadModel('AdministrativeLevels');
        $administrativeLevelsData = $this->AdministrativeLevels->find('all', ['conditions' => ['status' => 1]]);
        $administrativeLevels = [];
        foreach($administrativeLevelsData as $administrativeLevelsDatum)
        {
            $administrativeLevels[$administrativeLevelsDatum['level_no']] = $administrativeLevelsDatum['level_name'];
        }

        $warehouses = $this->Users->Warehouses->find('list', ['conditions' => ['status' => 1]]);
        $depots = $this->Users->Depots->find('list', ['conditions' => ['status' => 1]]);
        $userGroups = $this->Users->UserGroups->find('list', ['conditions' => ['status' => 1]]);
        $this->set(compact('user', 'warehouses', 'depots', 'userGroups', 'administrativeLevels'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $userInfo = $this->Auth->user();
        $time = time();
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        unset($user['password']);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->data;
            if (empty($data['password'])) {
                unset($data['password']);
            }
            if(empty($data['password']) && empty($data['confirm_password'])){
                unset($data['password']);
            }
            else if(($data['password']) != ($data['confirm_password'])){
                $this->Flash->error('Password Does Not Match');
                return $this->redirect($this->here);
            }
            $data['updated_by'] = $userInfo['id'];
            $data['updated_date'] = $time;
            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                $this->Flash->success('The user has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The user could not be saved. Please, try again.');
            }
        }

        $this->loadModel('AdministrativeLevels');
        $administrativeLevelsData = $this->AdministrativeLevels->find('all', ['conditions' => ['status' => 1]]);
        $administrativeLevels = [];
        foreach($administrativeLevelsData as $administrativeLevelsDatum)
        {
            $administrativeLevels[$administrativeLevelsDatum['level_no']] = $administrativeLevelsDatum['level_name'];
        }

        $administrativeUnits = $this->Users->AdministrativeUnits->find('list', ['conditions' => ['status' => 1, 'level_no'=>$user['level_no']]]);
        $warehouses = $this->Users->Warehouses->find('list', ['conditions' => ['status' => 1]]);
        $depots = $this->Users->Depots->find('list', ['conditions' => ['status' => 1]]);
        $userGroups = $this->Users->UserGroups->find('list', ['conditions' => ['status' => 1]]);
        $this->set(compact('user', 'administrativeUnits', 'warehouses', 'depots', 'userGroups', 'administrativeLevels'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $user = $this->Users->get($id);

        $user = $this->Auth->user();
        $data = $this->request->data;
        $data['updated_by'] = $user['id'];
        $data['updated_date'] = time();
        $data['status'] = 99;
        $user = $this->Users->patchEntity($user, $data);
        if ($this->Users->save($user)) {
            $this->Flash->success('The user has been deleted.');
        } else {
            $this->Flash->error('The user could not be deleted. Please, try again.');
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
