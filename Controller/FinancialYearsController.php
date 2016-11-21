<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * FinancialYears Controller
 *
 * @property \App\Model\Table\FinancialYearsTable $FinancialYears
 */
class FinancialYearsController extends AppController
{
    public $paginate = [
        'limit' => 15,
        'order' => [
            'FinancialYears.id' => 'desc'
        ]
    ];

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $financialYears = $this->FinancialYears->find('all', [
            'conditions' => ['FinancialYears.status !=' => 99]
        ]);
        $this->set('financialYears', $this->paginate($financialYears));
        $this->set('_serialize', ['financialYears']);
    }

    /**
     * View method
     *
     * @param string|null $id Financial Year id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Auth->user();
        $financialYear = $this->FinancialYears->get($id, [
            'contain' => []
        ]);
        $this->set('financialYear', $financialYear);
        $this->set('_serialize', ['financialYear']);
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
        $financialYear = $this->FinancialYears->newEntity();

        if ($this->request->is('post')) {
            $data = $this->request->data;
            $data['year_start'] = strtotime($data['year_start']);
            $data['year_end'] = strtotime($data['year_end']);
            $data['created_by'] = $user['id'];
            $data['created_date'] = $time;

            $financialYear = $this->FinancialYears->patchEntity($financialYear, $data);
            if ($this->FinancialYears->save($financialYear)) {
                $this->Flash->success('The financial year has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The financial year could not be saved. Please, try again.');
            }
        }
        $this->set(compact('financialYear'));
        $this->set('_serialize', ['financialYear']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Financial Year id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Auth->user();
        $time = time();
        $financialYear = $this->FinancialYears->get($id, [
            'contain' => []
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->data;
            $data['year_start'] = strtotime($data['year_start']);
            $data['year_end'] = strtotime($data['year_end']);
            $data['updated_by'] = $user['id'];
            $data['updated_date'] = $time;

            $financialYear = $this->FinancialYears->patchEntity($financialYear, $data);
            if ($this->FinancialYears->save($financialYear)) {
                $this->Flash->success('The financial year has been saved.');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('The financial year could not be saved. Please, try again.');
            }
        }
        $this->set(compact('financialYear'));
        $this->set('_serialize', ['financialYear']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Financial Year id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {

        $financialYear = $this->FinancialYears->get($id);

        $user = $this->Auth->user();
        $data = $this->request->data;
        $data['updated_by'] = $user['id'];
        $data['updated_date'] = time();
        $data['status'] = 99;
        $financialYear = $this->FinancialYears->patchEntity($financialYear, $data);
        if ($this->FinancialYears->save($financialYear)) {
            $this->Flash->success('The financial year has been deleted.');
        } else {
            $this->Flash->error('The financial year could not be deleted. Please, try again.');
        }
        return $this->redirect(['action' => 'index']);
    }
}
