<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Cache\Cache;
/**
 * Dashborad Controller
 *
 * @property \App\Model\Table\DashboradTable $Dashborad
 */
class DashboardController extends AppController
{
    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
//        echo '<pre>';
//        print_r((new DefaultPasswordHasher)->hash('123456'));
//        echo '</pre>';
//        die;
    }
    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user)
            {
                $this->Auth->setUser($user);
                $this->Flash->success(__('Your Have succesfully loged in'));
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('Your username or password is incorrect.'));
        }
        $this->viewBuilder()->layout('login');
    }
    public function logout()
    {
        $this->Flash->success(__('You are now logged out'));
//        Cache::clear(false);
        return $this->redirect($this->Auth->logout());
    }
}
