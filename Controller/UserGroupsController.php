<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * UserGroups Controller
 *
 * @property \App\Model\Table\UserGroupsTable $UserGroups
 */
class UserGroupsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('userGroups', $this->paginate($this->UserGroups));
        $this->set('_serialize', ['userGroups']);
    }

    /**
     * View method
     *
     * @param string|null $id User Group id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $userGroup = $this->UserGroups->get($id,['contain'=>['Created','Updated']]);
        $this->set('userGroup', $userGroup);
        $this->set('_serialize', ['userGroup']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $userGroup = $this->UserGroups->newEntity();
        if ($this->request->is('post'))
        {
            $data = $this->request->data;
            $data['created_by'] = $this->Auth->user('id');
            $data['created_time'] = time();
            $userGroup = $this->UserGroups->patchEntity($userGroup, $data,['associated'=>['Users']]);
            if ($this->UserGroups->save($userGroup)) {
                $this->Flash->success(__('The user group has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user group could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('userGroup'));
        $this->set('_serialize', ['userGroup']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User Group id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $userGroup = $this->UserGroups->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put']))
        {
            $data = $this->request->data;
            $data['updated_by'] = $this->Auth->user('id');;
            $data['updated_time'] = time();
            $userGroup = $this->UserGroups->patchEntity($userGroup, $data,['associated'=>['Users']]);
            if ($this->UserGroups->save($userGroup)) {
                $this->Flash->success(__('The user group has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user group could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('userGroup'));
        $this->set('_serialize', ['userGroup']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User Group id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->Flash->error(__('You Can not Delete a Group. You can In-active it!'));
        return $this->redirect(['action' => 'index']);
//        $this->request->allowMethod(['post', 'delete']);
//        $userGroup = $this->UserGroups->get($id);
//        if ($this->UserGroups->delete($userGroup)) {
//            $this->Flash->success(__('The user group has been deleted.'));
//        } else {
//            $this->Flash->error(__('The user group could not be deleted. Please, try again.'));
//        }
//        return $this->redirect(['action' => 'index']);
    }
}
