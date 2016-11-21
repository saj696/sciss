<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Closings Controller
 *
 * @property \App\Model\Table\ClosingsTable $Closings
 */
class ClosingsController extends AppController
{
    public $paginate = [
        'limit' => 15,
        'order' => [
            'Closings.id' => 'desc'
        ]
    ];

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $closings = $this->Closings->find('all', [
            'conditions' => ['Closings.status !=' => 99]
        ]);
        $this->set('closings', $this->paginate($closings));
        $this->set('_serialize', ['closings']);
    }

    /**
     * View method
     *
     * @param string|null $id Closing id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Auth->user();
        $closing = $this->Closings->get($id, [
            'contain' => []
        ]);
        $this->set('closing', $closing);
        $this->set('_serialize', ['closing']);
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
        $closing = $this->Closings->newEntity();
        if ($this->request->is('post')) {

            $data = $this->request->data;

            $data['start_date'] = strtotime($data['start_date']);
            $data['end_date'] = strtotime($data['end_date']);
            $data['created_by'] = $user['id'];
            $data['created_date'] = $time;
            $closing = $this->Closings->patchEntity($closing, $data);
            if ($this->Closings->save($closing)) {
                $this->Flash->success('The closing has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The closing could not be saved. Please, try again.');
            }
        }
        $this->set(compact('closing'));
        $this->set('_serialize', ['closing']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Closing id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Auth->user();
        $time = time();
        $closing = $this->Closings->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->data;
            $data['start_date'] = strtotime($data['start_date']);
            $data['end_date'] = strtotime($data['end_date']);
            $data['updated_by'] = $user['id'];
            $data['updated_date'] = $time;

            $closing = $this->Closings->patchEntity($closing, $data);
            if ($this->Closings->save($closing)) {
                $this->Flash->success('The closing has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The closing could not be saved. Please, try again.');
            }
        }
        $this->set(compact('closing'));
        $this->set('_serialize', ['closing']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Closing id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {

        $closing = $this->Closings->get($id);

        $user = $this->Auth->user();
        $data = $this->request->data;
        $data['updated_by'] = $user['id'];
        $data['updated_date'] = time();
        $data['status'] = 99;
        $closing = $this->Closings->patchEntity($closing, $data);
        if ($this->Closings->save($closing)) {
            $this->Flash->success('The closing has been deleted.');
        } else {
            $this->Flash->error('The closing could not be deleted. Please, try again.');
        }
        return $this->redirect(['action' => 'index']);
    }
}
