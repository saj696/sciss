<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Cache\Cache;
use Cake\Core\App;
use ReflectionClass;
use ReflectionMethod;

/**
 * UserGroupPermissions Controller
 *
 * @property \App\Model\Table\UserGroupPermissionsTable $UserGroupPermissions
 */
class UserGroupPermissionsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
//        $user_groups = $this->UserGroupPermissions->UserGroups->find('all',
//            [
//                'conditions' => ['status' => 1],
//                'contain' => [
//                    'UserGroupPermissions' => function ($q){
//                        return $q
//                            ->select(['UserGroupPermissions.id']);
//                    }
//                ]
//            ]);
        $user_groups = $this->UserGroupPermissions->UserGroups->find('all',[
        'conditions'=>['UserGroups.status'=>1],
        'contain'=>['Created','Updated']
        ]);
//        $user_groups->select(['user_groups.id','user_groups.title','UserGroupPermissions.id','total_method'=>$user_groups->func()->count('UserGroupPermissions.id'),'total_controller'=>$user_groups->func()->count('DISTINCT UserGroupPermissions.controller')])
//            ->where(['UserGroupPermissions.status'=>1])
//            ->group(['UserGroupPermissions.user_group_id'])
//            ->leftJoin('user_groups','user_groups.id = UserGroupPermissions.user_group_id');
        $this->set(compact('user_groups'));
    }

    /**
     * View method
     *
     * @param string|null $id User Group Permission id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $userGroupPermission = $this->UserGroupPermissions->get($id, [
            'contain' => ['UserGroups']
        ]);
        $this->set('userGroupPermission', $userGroupPermission);
        $this->set('_serialize', ['userGroupPermission']);
    }
    /**
     * Edit method
     *
     * @param string|null $id User Group Permission id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $userGroups = $this->UserGroupPermissions->UserGroups->get($id, [
            'contain' => []
        ]);
        $old_permissions = $this->UserGroupPermissions->find('all',['conditions'=>['user_group_id' => $id]]);
        $old_data = [];
        if($old_permissions)
        {
            foreach($old_permissions as $permission)
            {
                $old_data[$permission['controller']][$permission['action']] = $permission;
            }
        }
        if ($this->request->is(['patch', 'post', 'put']))
        {
            $inputs = $this->request->data();
            $input_data = [];
            $time = time();
            $user = $this->Auth->user();
            if(isset($inputs['roles']))
            {
                foreach ($inputs['roles'] as $controller => $actions)
                {
                    foreach ($actions as $action)
                    {
                        if(!$old_permissions)
                        {
                            $input_data[] = [
                                'user_group_id' => $id,
                                'controller' => $controller,
                                'action' => $action,
                                'status' => 1,
                                'created_by' => $user['id'],
                                'created_time' => $time
                            ];
                        }
                        else
                        {
                            if(isset($old_data[$controller][$action]))
                            {
                                if($old_data[$controller][$action]['status'] == 0)
                                {
                                    $old_data[$controller][$action]->status = 1;
                                    $old_data[$controller][$action]->updated_by = $user['id'];

                                    $old_data[$controller][$action]->updated_time = $time;
                                    $this->UserGroupPermissions->save($old_data[$controller][$action]);
                                }
                                unset($old_data[$controller][$action]);
                            }
                            else
                            {
                                $input_data[] = [
                                    'user_group_id' => $id,
                                    'controller' => $controller,
                                    'action' => $action,
                                    'status' => 1,
                                    'created_by' => $user['id'],
                                    'created_time' => $time
                                ];
                            }
                        }
                    }
                }
            }

            // delete old permission
            if(!empty($old_data))
            {
                foreach($old_data as $data){
                    foreach ($data as $d) {
                        $d->status = 0;
                        $d->updated_by = $user['id'];
                        $d->updated_time = $time;
                        $this->UserGroupPermissions->save($d);
                    }

                }
            }
            // new entry
            if($input_data)
            {
                $permissions = $this->UserGroupPermissions->newEntities($input_data);
                foreach ($permissions as $entity)
                {
                    $this->UserGroupPermissions->save($entity);
                }
            }
            $this->Flash->success(__('The permissions has been saved.'));
            Cache::clear(false,'mcake');
            return $this->redirect(['action' => 'index']);
        }
        $controllers = $this->get_controllers();
        foreach ($controllers as $controller)
        {
            $controllers_methods[$controller] = $this->get_method($controller);
        }
        $this->set(compact('userGroups', 'controllers_methods','old_data'));
    }

    private function get_controllers()
    {
        $Controllers = scandir('../src/Controller/');
        $results = [];
        $ignore = [
            '.',
            '..',
            'PagesController.php',
            'Component',
            'AppController.php',
        ];
        foreach ($Controllers as $file) {
            if (!in_array($file, $ignore)) {
                $controller = explode('.', $file)[0];
                $controller = str_replace('Controller', '', $controller);
                $results[] = $controller;
            }
        }
        return $results;
    }

    private function get_method($controller)
    {
        $class_name = 'App\\Controller\\' . $controller . 'Controller';
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
}
