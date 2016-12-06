<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * AccountHeads Controller
 *
 * @property \App\Model\Table\AccountHeadsTable $AccountHeads
 */
class AccountHeadsController extends AppController
{

    public $paginate = [
        'limit' => 15,
        'order' => [
            'AccountHeads.id' => 'desc'
        ]
    ];

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $accountHeads = $this->AccountHeads->find('all', [
            'conditions' =>['AccountHeads.status !=' => 99],
        ]);
        $accounts = ['cid' => [], 'parent' => []];
        foreach ($accountHeads as $accountHead) {
            $accounts['cid'][$accountHead->id] = $accountHead;
            $accounts['parent'][$accountHead->parent][] = $accountHead->id;
        }
        $html = $this->buildChartTree(0, $accounts);
        $this->set('html', $html);

        $this->set('accountHeads', $this->paginate($accountHeads) );
        $this->set('_serialize', ['accountHeads']);
    }

    /**
     * View method
     *
     * @param string|null $id Account Head id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user=$this->Auth->user();
        $accountHead = $this->AccountHeads->get($id, [
//            'contain' => ['ParentAccountHeads', 'Contras']
        ]);
        $this->set('accountHead', $accountHead);
        $this->set('_serialize', ['accountHead']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user=$this->Auth->user();
        $time=time();
        $accountHead = $this->AccountHeads->newEntity();
        if ($this->request->is('post'))
        {
            $data=$this->request->data;
            $data['created_by']=$user['id'];
            $data['created_date']=$time;
            $accountHead = $this->AccountHeads->patchEntity($accountHead, $data);
            if ($this->AccountHeads->save($accountHead))
            {
                $this->Flash->success('The account head has been saved.');
                return $this->redirect(['action' => 'index']);
            }
            else
            {
                $this->Flash->error('The account head could not be saved. Please, try again.');
            }
        }
        $parents = $this->AccountHeads->find('list',['conditions'=>['status !=' => 99]]);
        $this->set(compact('accountHead','parents'));
        $this->set('_serialize', ['accountHead']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Account Head id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user=$this->Auth->user();
        $time=time();
        $accountHead = $this->AccountHeads->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put']))
        {
            $data=$this->request->data;
            $data['update_by']=$user['id'];
            $data['update_date']=$time;
            $accountHead = $this->AccountHeads->patchEntity($accountHead, $data);
            if ($this->AccountHeads->save($accountHead))
            {
                $this->Flash->success('The account head has been saved.');
                return $this->redirect(['action' => 'index']);
            }
            else
            {
                $this->Flash->error('The account head could not be saved. Please, try again.');
            }
        }
        $parents = $this->AccountHeads->find('list',['conditions'=>['status !=' => 99]]);
        $this->set(compact('accountHead', 'parents'));
        $this->set('_serialize', ['accountHead']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Account Head id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {

        $accountHead = $this->AccountHeads->get($id);

        $user=$this->Auth->user();
        $data['name'] = $accountHead['name'];
        $data['updated_by']=$user['id'];
        $data['updated_date']=time();
        $data['status']=99;
        $accountHead = $this->AccountHeads->patchEntity($accountHead, $data);

        if ($this->AccountHeads->save($accountHead))
        {
            $this->Flash->success('The account head has been deleted.');
        }
        else
        {
            $this->Flash->error('The account head could not be deleted. Please, try again.');
        }
        return $this->redirect(['action' => 'index']);
    }


    public function buildChartTree($parent, $accounts)
    {
        $html = "";
        if (isset($accounts['parent'][$parent])) {
            $html .= "<ul>";
            foreach ($accounts['parent'][$parent] as $ca) {
                if (!isset($accounts['parent'][$ca])) {
                    $html .= "<li style='margin: 5px;'>" . "<label style='padding: 2px 8px 2px 8px;' class='btn btn-circle red'><a style='color:white;' href='" . 'AccountHeads/edit/'. $accounts['cid'][$ca]->id . "'>" . $accounts['cid'][$ca]->name . "</a></label>" . "</li>";
                }
                if (isset($accounts['parent'][$ca])) {
                    $html .= "<li style='margin: 5px;'>" . "<label style='padding: 2px 8px 2px 8px;' class='btn btn-circle green'><a style='color:white;' href='" . 'AccountHeads/edit/'. $accounts['cid'][$ca]->id . "'>" . $accounts['cid'][$ca]->name . "</a></label>";
                    $html .= $this->buildChartTree($ca, $accounts);
                    $html .= "</li>";
                }
            }
            $html .= "</ul>";
        }
        return $html;
    }
}
