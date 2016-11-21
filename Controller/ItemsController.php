<?php
namespace App\Controller;

use App\Controller\AppController;
use App\View\Helper\SystemHelper;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\View\View;

/**
 * Items Controller
 *
 * @property \App\Model\Table\ItemsTable $Items
 */
class ItemsController extends AppController
{

    public $paginate = [
        'limit' => 15,
        'order' => [
            'Items.id' => 'desc'
        ]
    ];

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $items = $this->Items->find('all', [
            'conditions' => ['Items.status !=' => 99],
            'contain' => ['Categories']
        ]);
        $this->set('items', $this->paginate($items));
        $this->set('_serialize', ['items']);
    }

    /**
     * View method
     *
     * @param string|null $id Item id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Auth->user();
        $item = $this->Items->get($id, [
            'contain' => ['Categories']
        ]);
        $this->set('item', $item);
        $this->set('_serialize', ['item']);
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
        $item = $this->Items->newEntity();
        if ($this->request->is('post')) {

            $data = $this->request->data;
            $data['created_by'] = $user['id'];
            $data['created_date'] = $time;
            $item = $this->Items->patchEntity($item, $data);
            if ($this->Items->save($item)) {
                $this->Flash->success('The item has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The item could not be saved. Please, try again.');
            }
        }
        $categories = $this->Items->Categories->find('list', ['conditions' => ['status' => 1, 'level_no'=>1]]);
        $this->set(compact('item', 'categories'));
        $this->set('_serialize', ['item']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Item id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Auth->user();
        $time = time();
        $item = $this->Items->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->data;
            $data['updated_by'] = $user['id'];
            $data['updated_date'] = $time;
            $item = $this->Items->patchEntity($item, $data);
            if ($this->Items->save($item)) {
                $this->Flash->success('The item has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The item could not be saved. Please, try again.');
            }
        }
        $categories = $this->Items->Categories->find('list', ['conditions' => ['status' => 1]]);
        $this->set(compact('item', 'categories'));
        $this->set('_serialize', ['item']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Item id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {

        $item = $this->Items->get($id);

        $user = $this->Auth->user();
        $data = $this->request->data;
        $data['updated_by'] = $user['id'];
        $data['updated_date'] = time();
        $data['status'] = 99;
        $item = $this->Items->patchEntity($item, $data);
        if ($this->Items->save($item)) {
            $this->Flash->success('The item has been deleted.');
        } else {
            $this->Flash->error('The item could not be deleted. Please, try again.');
        }
        return $this->redirect(['action' => 'index']);
    }

    public function ajax()
    {
        $data = $this->request->data;
        $category = $data['category'];
        $subs = TableRegistry::get('categories')->find('all', ['conditions' => ['parent' => $category], 'fields'=>['id', 'name']])->hydrate(false)->toArray();

        $dropArray = [];
        foreach($subs as $sub):
            $dropArray[$sub['id']] = $sub['name'];
        endforeach;

        if(sizeof($dropArray)>0){
            $this->viewBuilder()->layout('ajax');
            $this->set(compact('dropArray'));
        } else{
            $this->autoRender = false;
        }

    }

    public function generateCode()
    {
        $this->autoRender=false;
        $itemPadding = Configure::read('item_generation_padding');
        $data = $this->request->data;
        $itemPrefix = TableRegistry::get('categories')->find('all', ['conditions' => ['id' => $data['category']], 'fields'=>['prefix']])->first()->toArray();
        App::import('Helper', 'SystemHelper');
        $SystemHelper = new SystemHelper(new View());
        $itemCode = $SystemHelper->generate_code($itemPrefix['prefix'], 'item', $itemPadding);
        $this->response->body($itemCode);
        return $this->response;
    }
}
