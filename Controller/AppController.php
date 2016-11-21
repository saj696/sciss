<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Cache\Cache;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Utility\Security;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Common');

        $this->loadComponent('Auth', [
            'authorize' => ['Controller'],
            'authenticate' => [
                'Form' => [
                    'fields' => [
                        'username' => 'username',
                        'password' => 'password'
                    ]
                ]
            ],
            'loginAction' => [
                'controller' => 'Dashboard',
                'action' => 'login'
            ],
            'loginRedirect'=>[
                'controller' => 'Dashboard',
                'action' => 'index'
            ],
            'logoutRedirect'=>[
                'controller' => 'Dashboard',
                'action' => 'login'
            ]
        ]);
    }
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $auth_usr = $this->Auth->user();
        $this->set('auth_usr',$auth_usr);
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return void
     */
    public function beforeRender(Event $event)
    {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }
    public function isAuthorized($user)
    {
        $cache_variable = Configure::read('user_role_cache_var').Security::hash($user['user_group_id']);
        if(empty(Cache::read($cache_variable,'mcake')) || Cache::read($cache_variable,'mcake') === false)
        {
            $user_roles = TableRegistry::get('user_group_permissions')->find()->where(['user_group_id'=>$user['user_group_id'],'status'=>1])->toArray();
            $roles = [];
            foreach($user_roles as $user_role)
            {
                $roles[strtolower($user_role['controller'])][strtolower($user_role['action'])] = $user_role['status'];
            }
            Cache::write($cache_variable, $roles,'mcake');
        }
        $roles = isset($roles) ? $roles : Cache::read($cache_variable,'mcake');
        $this->roles=$roles;
        $controller = strtolower($this->request->param('controller'));
        $action  = strtolower($this->request->param('action'));
        $permission_free = [
            'dashboard'=>[
                'login'=>1,
                'logout'=>1,
                'index'=>1
            ],
        ];

        if(isset($permission_free[$controller][$action]))
        {
            return true;
        }
        if(isset($roles[$controller][$action]) && $roles[$controller][$action])
        {
            return true;
        }
        // log error
        $log_text = '#mazbas_cake# permission_error: actionUrl-' . $controller.'/'.$action.' time:'.time();
        $this->log($log_text, LOG_DEBUG);
        return false;
    }
}
