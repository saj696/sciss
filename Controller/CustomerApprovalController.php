<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * CustomerApproval Controller
 *
 * @property \App\Model\Table\CustomerApprovalTable $CustomerApproval
 */
class CustomerApprovalController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $user = $this->Auth->user();
        $this->loadModel('Customers');

        $approves = $this->Customers->find('all',[
                'conditions' => ['Customers.status !=' => 99, 'Customers.status' => 0]
        ]);

        $approves = $this->paginate($approves);
        $this->set(compact('approves'));
        $this->set('_serialize', ['approves']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Customer Approval id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Auth->user();
        $time = time();
        $this->loadModel('Customers');
        $approves = $this->Customers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $approves = $this->Customers->patchEntity($approves, $this->request->data);
            $approves['opening_balance'] = 0;
            $approves['approved_by'] = $user['id'];
            $approves['approval_date'] = $time;

            if ($this->Customers->save($approves)) {
                $this->Flash->success(__('The customer approval has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The customer approval could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('approves'));
        $this->set('_serialize', ['approves']);
    }

}
