<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

/**
 * Categories Controller
 *
 * @property \App\Model\Table\CategoriesTable $Categories
 */
class CategoriesController extends AppController
{

    public $paginate = [
        'limit' => 15,
        'order' => [
            'Categories.id' => 'desc'
        ]
    ];

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $categories = $this->Categories->find('all', [
            'conditions' => ['Categories.status !=' => 99]
        ]);
        $this->set('categories', $this->paginate($categories));
        $this->set('_serialize', ['categories']);
    }

    /**
     * View method
     *
     * @param string|null $id Category id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Auth->user();
        $category = $this->Categories->get($id, [
            'contain' => []
        ]);
        $this->set('category', $category);
        $this->set('_serialize', ['category']);
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
        $category = $this->Categories->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->data;
            $data['prefix'] = strtoupper($data['prefix']);
            $levelInfo = TableRegistry::get('categories')->find('all', ['conditions' => ['id' => $data['parent']], 'fields' => ['level_no', 'number_of_direct_successors', 'global_id']])->first();
            $successor = TableRegistry::get('categories');
            $query = $successor->query();
            $query->update()
                ->set(['number_of_direct_successors' => $levelInfo['number_of_direct_successors'] + 1])
                ->where(['id' => $data['parent']])
                ->execute();
            $data['created_by'] = $user['id'];
            $data['created_date'] = $time;
            $data['level_no'] = $levelInfo['level_no'] + 1;
            $data['local_id'] = $levelInfo['number_of_direct_successors'] + 1;
            $data['global_id'] = $data['local_id'] * pow(2, 6 * (Configure::read('category_max_level_no') - $data['level_no'])) + $levelInfo['global_id'];
            $category = $this->Categories->patchEntity($category, $data);
            if ($this->Categories->save($category)) {
                $this->Flash->success('The category has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The category could not be saved. Please, try again.');
            }
        }
        $parentName = $this->Categories->find('list', ['conditions' => ['status' => 1]])->toArray();
        $this->set(compact('category', 'parentName'));
        $this->set('_serialize', ['category']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Category id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Auth->user();
        $time = time();
        $category = $this->Categories->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->data;
            $data['update_by'] = $user['id'];
            $data['update_date'] = $time;
            $category = $this->Categories->patchEntity($category, $data);
            if ($this->Categories->save($category)) {
                $this->Flash->success('The category has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The category could not be saved. Please, try again.');
            }
        }
        $this->set(compact('category'));
        $this->set('_serialize', ['category']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Category id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {

        $category = $this->Categories->get($id);

        $user = $this->Auth->user();
        $data = $this->request->data;
        $data['updated_by'] = $user['id'];
        $data['updated_date'] = time();
        $data['status'] = 99;
        $category = $this->Categories->patchEntity($category, $data);
        if ($this->Categories->save($category)) {
            $this->Flash->success('The category has been deleted.');
        } else {
            $this->Flash->error('The category could not be deleted. Please, try again.');
        }
        return $this->redirect(['action' => 'index']);
    }
}
