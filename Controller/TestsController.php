<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Tests Controller
 *
 * @property \App\Model\Table\TestsTable $Tests
 */
class TestsController extends AppController
{

	public $paginate = [
        'limit' => 15,
        'order' => [
            'Tests.id' => 'desc'
        ]
    ];

/**
* Index method
*
* @return void
*/
public function index()
{
			$tests = $this->Tests->find('all', [
	'conditions' =>['Tests.status !=' => 99]
	]);
		$this->set('tests', $this->paginate($tests) );
	$this->set('_serialize', ['tests']);
	}

    /**
     * View method
     *
     * @param string|null $id Test id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user=$this->Auth->user();
        $test = $this->Tests->get($id, [
            'contain' => []
        ]);
        $this->set('test', $test);
        $this->set('_serialize', ['test']);
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
        $test = $this->Tests->newEntity();
        if ($this->request->is('post'))
        {

            $data=$this->request->data;
            $data['create_by']=$user['id'];
            $data['create_date']=$time;
            $test = $this->Tests->patchEntity($test, $data);
            if ($this->Tests->save($test))
            {
                $this->Flash->success('The test has been saved.');
                return $this->redirect(['action' => 'index']);
            }
            else
            {
                $this->Flash->error('The test could not be saved. Please, try again.');
            }
        }
        $this->set(compact('test'));
        $this->set('_serialize', ['test']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Test id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user=$this->Auth->user();
        $time=time();
        $test = $this->Tests->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put']))
        {
            $data=$this->request->data;
            $data['update_by']=$user['id'];
            $data['update_date']=$time;
            $test = $this->Tests->patchEntity($test, $data);
            if ($this->Tests->save($test))
            {
                $this->Flash->success('The test has been saved.');
                return $this->redirect(['action' => 'index']);
            }
            else
            {
                $this->Flash->error('The test could not be saved. Please, try again.');
            }
        }
        $this->set(compact('test'));
        $this->set('_serialize', ['test']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Test id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {

        $test = $this->Tests->get($id);

        $user=$this->Auth->user();
        $data=$this->request->data;
        $data['updated_by']=$user['id'];
        $data['updated_date']=time();
        $data['status']=99;
        $test = $this->Tests->patchEntity($test, $data);
        if ($this->Tests->save($test))
        {
            $this->Flash->success('The test has been deleted.');
        }
        else
        {
            $this->Flash->error('The test could not be deleted. Please, try again.');
        }
        return $this->redirect(['action' => 'index']);
    }
}
