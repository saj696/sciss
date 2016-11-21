<?php
namespace App\Controller;

use App\Controller\AppController;
use ReflectionClass;
use ReflectionMethod;
/**
 * Tasks Controller
 *
 * @property \App\Model\Table\TasksTable $Tasks
 */
class TasksController extends AppController
{
    public $paginate = [
        'limit' => 10,
        'order' => [
            'Tasks.id' => 'desc'
        ]
    ];
    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $tasks = $this->Tasks->find('all',[
           'contain'=>['ParentTasks','ChildTasks']
        ]);

        $this->set('tasks', $this->paginate($tasks));
        $this->set('_serialize', ['tasks']);
    }

    /**
     * View method
     *
     * @param string|null $id Task id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $task = $this->Tasks->get($id, [
            'contain' => ['ParentTasks', 'ChildTasks']
        ]);
        $this->set('task', $task);
        $this->set('_serialize', ['task']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $task = $this->Tasks->newEntity();
        if ($this->request->is('post'))
        {
            $data = $this->request->data;
            $data['create_by'] = $this->Auth->user();
            $data['create_date'] = time();
            $task = $this->Tasks->patchEntity($task, $data);
            if ($this->Tasks->save($task))
            {
                $this->Flash->success(__('The task has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The task could not be saved. Please, try again.'));
            }
        }
        $parentTasks = $this->Tasks->ParentTasks->find('list');
        $controllers = $this->get_controllers();

        $this->set(compact('task', 'parentTasks','controllers'));
        $this->set('_serialize', ['task']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Task id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $task = $this->Tasks->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put']))
        {
            $data = $this->request->data;
            $data['update_by'] = $this->Auth->user();
            $data['update_date'] = time();
            $task = $this->Tasks->patchEntity($task,$data);
            if ($this->Tasks->save($task))
            {
                $this->Flash->success(__('The task has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else
            {
                $this->Flash->error(__('The task could not be saved. Please, try again.'));
            }
        }
        if($task['controller']){
            $methods = $this->get_methods($task['controller']);
        }
        $controllers = $this->get_controllers();
        $parentTasks = $this->Tasks->ParentTasks->find('list');
        $this->set(compact('task', 'parentTasks','controllers','methods'));
        $this->set('_serialize', ['task']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Task id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $task = $this->Tasks->get($id);
        if ($this->Tasks->delete($task)) {
            $this->Flash->success(__('The task has been deleted.'));
        } else {
            $this->Flash->error(__('The task could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    public function ajax($action = '')
    {
        if($action == 'get_method')
        {
            $controller = $this->request->data('controller');
            $methods = $this->get_methods($controller);
            $this->response->body(json_encode($methods));
            return $this->response;
        }
    }
    private function get_methods($controller){
        $class_name = 'App\\Controller\\' . $controller . 'Controller';
        if(!class_exists($class_name))//TODO:check the issue
        {
            return false;
        }
        $controller_class = new ReflectionClass($class_name);
        $actions = $controller_class->getMethods(ReflectionMethod::IS_PUBLIC);
        $ignore = ['beforeFilter', 'afterFilter', 'initialize'];
        $methods = [];
        foreach ($actions as $action) {
            if ($action->class == $class_name && !in_array($action->name, $ignore)) {
                $methods[] = $action->name;
            }
        }
        return $methods;
    }
    private function get_controllers()
    {
        $all_controllers = scandir('../src/Controller/');
        $controllers = [];
        $ignore = [
            '.',
            '..',
            'PagesController.php',
            'Component',
            'AppController.php',
        ];
        foreach ($all_controllers as $file) {
            if (!in_array($file, $ignore)) {
                $controller = explode('.', $file)[0];
                $controller = str_replace('Controller', '', $controller);
                $controllers[$controller] = $controller;
            }
        }
        return $controllers;
    }
}
