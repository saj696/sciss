<?php
namespace App\Controller;


use App\Controller\AppController;
use App\Model\Table\AdministrativeUnitsTable;
use App\View\Helper\SystemHelper;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\View\View;


/**
 * Customers Controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 */
class CustomersController extends AppController
{

    public $paginate = [
        'limit' => 15,
        'order' => [
            'Customers.id' => 'desc'
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
        $userAdmin = $user['administrative_unit_id'];
        $this->loadModel('AdministrativeUnits');
        $userAdminGlobal = $this->AdministrativeUnits->get($userAdmin);
        $limitStart = pow(2,(Configure::read('max_level_no')- $user['level_no']-1)*5);
        $limitEnd = pow(2,(Configure::read('max_level_no')- $user['level_no'])*5);

        $customers =  TableRegistry::get('customers')->query();
        $customers->contain('AdministrativeUnits');
        $customers->where('unit_global_id -'. $userAdminGlobal['global_id'] .'>= '.$limitStart);
        $customers->where('unit_global_id -'. $userAdminGlobal['global_id'] .'< '.$limitEnd);
        $customers->where('customers.status!= 99');

        $this->set('customers', $this->paginate($customers));
        $this->set('_serialize', ['customers']);
    }

    /**
     * View method
     *
     * @param string|null $id Customer id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Auth->user();
        $customer = $this->Customers->get($id, [
            'contain' => ['AdministrativeUnits']
        ]);
        $this->set('customer', $customer);
        $this->set('_serialize', ['customer']);
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
        $customer = $this->Customers->newEntity();
        if ($this->request->is('post')) {


            $data = $this->request->data;
            $this->loadModel('AdministrativeUnits');
            $unitInfo = $this->AdministrativeUnits->get($data['administrative_unit_id']);
            $data['unit_global_id'] = $unitInfo['global_id'];
            $data['created_by'] = $user['id'];
            $data['created_date'] = $time;
            $data['pesticide_issue_date'] = strtotime($data['pesticide_issue_date']);
            $data['pesticide_end_date'] = strtotime($data['pesticide_end_date']);
            $data['trade_issue_date'] = strtotime($data['trade_issue_date']);
            $data['trade_end_date'] = strtotime($data['trade_end_date']);
            $data['status'] = 0;
            $customer = $this->Customers->patchEntity($customer, $data);
            if ($this->Customers->save($customer)) {
                $this->Flash->success('The customer has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The customer could not be saved. Please, try again.');
            }
        }
        $this->loadModel('AdministrativeLevels');
        $administrativeLevelsData = $this->AdministrativeLevels->find('all', ['conditions' => ['status' => 1]]);
        $administrativeLevels = [];
        foreach($administrativeLevelsData as $administrativeLevelsDatum)
        {
            $administrativeLevels[$administrativeLevelsDatum['level_no']] = $administrativeLevelsDatum['level_name'];
        }

        $this->set(compact('customer', 'administrativeLevels'));
        $this->set('_serialize', ['customer']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Customer id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Auth->user();
        $time = time();
        $customer = $this->Customers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->data;
            $data['updated_by'] = $user['id'];
            $data['updated_date'] = $time;
            unset($data['prefix']);
            unset($data['code']);
            $customer = $this->Customers->patchEntity($customer, $data);
            if ($this->Customers->save($customer)) {
                $this->Flash->success('The customer has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The customer could not be saved. Please, try again.');
            }
        }

        $this->loadModel('AdministrativeLevels');
        $administrativeLevelsData = $this->AdministrativeLevels->find('all', ['conditions' => ['status' => 1]]);
        $administrativeLevels = [];
        foreach($administrativeLevelsData as $administrativeLevelsDatum)
        {
            $administrativeLevels[$administrativeLevelsDatum['level_no']] = $administrativeLevelsDatum['level_name'];
        }

        $administrativeUnits = $this->Customers->AdministrativeUnits->find('list', ['conditions' => ['status' => 1, 'level_no'=>$customer['level_no']]]);
        $this->set(compact('customer', 'administrativeUnits', 'administrativeLevels'));
        $this->set('_serialize', ['customer']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Customer id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {

        $customer = $this->Customers->get($id);

        $user = $this->Auth->user();
        $data = $this->request->data;
        $data['updated_by'] = $user['id'];
        $data['updated_date'] = time();
        $data['status'] = 99;
        $customer = $this->Customers->patchEntity($customer, $data);
        if ($this->Customers->save($customer)) {
            $this->Flash->success('The customer has been deleted.');
        } else {
            $this->Flash->error('The customer could not be deleted. Please, try again.');
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

    public function generateCode()
    {
        $this->autoRender=false;
        $customerPadding = Configure::read('id_generation_padding');
        $data = $this->request->data;

        $firstGlobal = TableRegistry::get('administrative_units')->find('all', ['conditions' => ['id' => $data['unit']], 'fields'=>['global_id']])->first()->toArray();
        App::import('Helper', 'SystemHelper');
        $SystemHelper = new SystemHelper(new View());
        $askGlobalID =  $SystemHelper->asked_level_global_id($data['prefix_level'], $firstGlobal['global_id']);
        $customerPrefix = TableRegistry::get('administrative_units')->find('all',['conditions'=>['global_id' => $askGlobalID], 'fields' =>['prefix']])->first();
        $customerCode = $SystemHelper->generate_code($customerPrefix['prefix'], 'customer', $customerPadding);

        $this->response->body($customerCode);
        return $this->response;

    }
}
