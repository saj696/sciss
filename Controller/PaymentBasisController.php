<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * PaymentBasis Controller
 *
 * @property \App\Model\Table\PaymentBasisTable $PaymentBasis
 */
class PaymentBasisController extends AppController
{

    public $paginate = [
        'limit' => 15,
        'order' => [
            'PaymentBasis.id' => 'desc'
        ]
    ];

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $user=$this->Auth->user();
        $time=time();

        $row = $this->PaymentBasis->find('all')->first();
        $id = $row['id'];

        if($id>0)
        {
            $paymentBasis = $this->PaymentBasis->get($id, [
                'contain' => []
            ]);
        }
        else
        {
            $paymentBasis = $this->PaymentBasis->newEntity();
        }

        if ($this->request->is(['patch', 'post', 'put']))
        {
            $data=$this->request->data;
            $data['created_by']=$user['id'];
            $data['created_date']=$time;
            $paymentBasis = $this->PaymentBasis->patchEntity($paymentBasis, $data);
            if ($this->PaymentBasis->save($paymentBasis))
            {
                $this->Flash->success('The payment basi has been saved.');
                return $this->redirect(['action' => 'index']);
            }
            else
            {
                $this->Flash->error('The payment basi could not be saved. Please, try again.');
                return $this->redirect(['action' => 'index']);
            }
        }
        $this->set(compact('paymentBasis'));
        $this->set('_serialize', ['paymentBasis']);
    }

}
