<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;


/**
 * ItemUnits Controller
 *
 * @property \App\Model\Table\ItemUnitsTable $ItemUnits
 */
class ItemUnitsController extends AppController
{

    public $paginate = [
        'limit' => 15,
        'order' => [
            'ItemUnits.id' => 'desc'
        ]
    ];


    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $itemUnits = $this->ItemUnits->find('all', [
            'conditions' => ['ItemUnits.status !=' => 99]
        ]);
        $this->set('itemUnits', $this->paginate($itemUnits));
        $this->set('_serialize', ['itemUnits']);
    }

    /**
     * View method
     *
     * @param string|null $id Item Unit id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Auth->user();
        $itemUnit = $this->ItemUnits->get($id, [
            'contain' => []
        ]);
        $this->set('itemUnit', $itemUnit);
        $this->set('_serialize', ['itemUnit']);
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
        $itemUnit = $this->ItemUnits->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->data;
            $data['created_by'] = $user['id'];
            $data['created_date'] = $time;
            $data['status'] = 1;
            $itemUnit = $this->ItemUnits->patchEntity($itemUnit, $data);
            if ($this->ItemUnits->save($itemUnit)) {
                $this->Flash->success('The item unit has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The item unit could not be saved. Please, try again.');
            }
        }

        $this->set(compact('itemUnit', 'constituentLevel'));
        $this->set('_serialize', ['itemUnit']);
    }


    public function ajax()
    {
        $data = $this->request->data;
        $unit_level = $data['level'];

        $constituent_unit = TableRegistry::get('item_units')->find('all', ['conditions' => ['unit_level' => $unit_level - 1], 'fields' => ['id', 'unit_name', 'unit_level', 'unit_size']])->hydrate(false)->toArray();
        $unit_level = Configure::read("unit_levels");
        $dropArray = [];
        foreach ($constituent_unit as $unit):
            $dropArray[$unit['id']] = __($unit_level[$unit['unit_level']]) . '__' . $unit['unit_name'] . '__' . $unit['unit_size'];
        endforeach;
        $this->viewBuilder()->layout('ajax');
        $this->set(compact('dropArray'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Item Unit id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Auth->user();
        $time = time();
        $itemUnit = $this->ItemUnits->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->data;
            $data['updated_by'] = $user['id'];
            $data['updated_date'] = $time;
            $itemUnit = $this->ItemUnits->patchEntity($itemUnit, $data);
            if ($this->ItemUnits->save($itemUnit)) {
                $this->Flash->success('The item unit has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The item unit could not be saved. Please, try again.');
            }
        }
        $this->set(compact('itemUnit', 'constituentLevel'));
        $this->set('_serialize', ['itemUnit']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Item Unit id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {

        $itemUnit = $this->ItemUnits->get($id);

        $user = $this->Auth->user();
        $data = $this->request->data;
        $data['updated_by'] = $user['id'];
        $data['updated_date'] = time();
        $data['status'] = 99;
        $itemUnit = $this->ItemUnits->patchEntity($itemUnit, $data);
        if ($this->ItemUnits->save($itemUnit)) {
            $this->Flash->success('The item unit has been deleted.');
        } else {
            $this->Flash->error('The item unit could not be deleted. Please, try again.');
        }
        return $this->redirect(['action' => 'index']);
    }
}
