<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;

/**
 * Payments Controller
 *
 * @property \App\Model\Table\PaymentsTable $Payments
 */
class PaymentsController extends AppController
{

    public $paginate = [
        'limit' => 15,
        'order' => [
            'Payments.id' => 'desc'
        ]
    ];

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $payments = $this->Payments->find('all', [
            'contain' => ['Customers'],
            'conditions' =>['Payments.status !=' => 99]
        ]);
        $this->set('payments', $this->paginate($payments) );
        $this->set('_serialize', ['payments']);
    }

    /**
     * View method
     *
     * @param string|null $id Payment id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user=$this->Auth->user();
        $payment = $this->Payments->get($id, [
            'contain' => []
        ]);
        $this->set('payment', $payment);
        $this->set('_serialize', ['payment']);
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
        $payment = $this->Payments->newEntity();
        if ($this->request->is('post')){

            $data=$this->request->data;
            $this->loadModel('InvoicePayments');
            $this->loadModel('Customers');
            $this->loadModel('Invoices');
            $this->loadModel('InvoicedProducts');
            $this->loadModel('InvoicedProductsPayments');
            $data['created_by']=$user['id'];
            $data['created_date']=$time;
            $data['collection_date'] = strtotime($data['collection_date']);
            $customerInfo = $this->Customers->find('all',['conditions'=> ['id' => $data['customer_id']], 'fields' =>['unit_global_id','customer_type']])->hydrate(false)->first();
            $data['customer_type'] = $customerInfo['customer_type'];
            $data['parent_global_id'] = $customerInfo['unit_global_id'];
            $payment = $this->Payments->patchEntity($payment, $data);
            $this->Payments->save($payment);

        }
//        Administrative levels
        $this->loadModel('AdministrativeLevels');
        $parantsLevels= [];
        $parentDatas = $this->AdministrativeLevels->find('all',['fields' => ['level_name', 'level_no'],'conditions'=>['status' => 1]])->toArray();
        foreach($parentDatas as $parentData)
        {
            $parantsLevels[$parentData['level_no']] = $parentData['level_name'];
        }
//    Account Head table
        $this->loadModel('AccountHeads');
        $paymentAccounts = [];
        $paymentDatas = $this->AccountHeads->find('all',['fields' =>['name','code'],'conditions' => ['account_selector' => 2] ])->hydrate(false)->toArray();
        foreach($paymentDatas as $paymentData):
            $paymentAccounts[$paymentData['code']] = $paymentData['name'];
        endforeach;

        $this->set(compact('payment','parantsLevels','paymentAccounts'));
        $this->set('_serialize', ['payment']);

    }

    /**
     * Edit method
     *
     * @param string|null $id Payment id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user=$this->Auth->user();
        $time=time();
        $payment = $this->Payments->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put']))
        {
            $data=$this->request->data;
            $data['update_by']=$user['id'];
            $data['update_date']=$time;
            $payment = $this->Payments->patchEntity($payment, $data);
            if ($this->Payments->save($payment))
            {
                $this->Flash->success('The payment has been saved.');
                return $this->redirect(['action' => 'index']);
            }
            else
            {
                $this->Flash->error('The payment could not be saved. Please, try again.');
            }
        }
        $this->set(compact('payment'));
        $this->set('_serialize', ['payment']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Payment id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $payment = $this->Payments->get($id);
        $user=$this->Auth->user();
        $data=$this->request->data;
        $data['updated_by']=$user['id'];
        $data['updated_date']=time();
        $data['status']=99;
        $payment = $this->Payments->patchEntity($payment, $data);
        if ($this->Payments->save($payment))
        {
            $this->Flash->success('The payment has been deleted.');
        }
        else
        {
            $this->Flash->error('The payment could not be deleted. Please, try again.');
        }
        return $this->redirect(['action' => 'index']);
    }

    public function ajax($param)
    {
        if($param == "units"):
            $data = $this->request->data;
            $level = $data['level'];
            $units = TableRegistry::get('administrative_units')->find('all', ['conditions' => ['level_no' => $level], 'fields'=>['id', 'unit_name']])->hydrate(false)->toArray();
            $dropArray = [];
            foreach($units as $unit):
                $dropArray[$unit['id']] = $unit['unit_name'];
            endforeach;
            $this->response->body(json_encode($dropArray));
            return $this->response;

        elseif($param == "customers"):
            $data = $this->request->data;
            $unit = $data['unit'];
            $customers = TableRegistry::get('customers')->find('all', ['conditions' => ['administrative_unit_id' => $unit], 'fields'=>['id', 'name']])->hydrate(false)->toArray();
            $dropArray = [];
            foreach($customers as $customer):
                $dropArray[$customer['id']] = $customer['name'];
            endforeach;
            $this->response->body(json_encode($dropArray));
            return $this->response;

        elseif($param == "dueInvoice"):
            $data = $this->request->data;
            $customer = $data['customer'];
            $paymentBasis = TableRegistry::get('payment_basis')->find('all', ['conditions' => ['status' => 1], 'fields'=>['basis']])->first();
            if(($paymentBasis['basis']) == 1):
//                table payment basis and condition check customer id and due greater than zero
                $invoices = TableRegistry::get('invoices')->find('all',['conditions' => ['customer_id' => $customer, 'due >' =>0 ], 'fields' => ['id','net_total','due','invoice_date'], 'limit'=>25, 'order' => ['invoices.id ASC'] ])->hydrate(false)->toArray();
                $invoiceArray = [];
                foreach($invoices as $invoice):
                    $invoiceArray[$invoice['id']] = 'Invoice Date :'.' '.date('d-m-y',$invoice['invoice_date']).', Net Total :'. ' '.$invoice['net_total'].', Due :'.' '.$invoice['due'];
                endforeach;
                $this->response->body(json_encode($invoiceArray));
                return $this->response;

            else:
                $invoices = TableRegistry::get('invoices')->find('all',['conditions' => ['customer_id' => $customer], 'fields' => ['id','net_total','due','invoice_date']])->hydrate(false)->toArray();
                $invoiceArray = [];
                foreach($invoices as $invoice):
                    $invoiceArray[$invoice['id']] = 'Invoice Date :'.' '.date('d-m-y',$invoice['invoice_date']).', Net Total :'. ' '.$invoice['net_total'].', Due :'.' '.$invoice['due'];
                endforeach;
                $this->response->body(json_encode($invoiceArray));
                return $this->response;
            endif;

        elseif($param == "paymentTable"):
            $data = $this->request->data;
            $dueInvoice = $data['dueInvoice'];
            $invoiceTables = TableRegistry::get('invoices')->find('all',['conditions' => ['id' => $dueInvoice], 'fields' => ['id','net_total','due','invoice_date'] ])->hydrate(false)->toArray();

            $dropArray = [];
            foreach($invoiceTables as $invoiceTable):
                $dropArray['id'] = $invoiceTable['id'];
                $dropArray['net_total'] = $invoiceTable['net_total'];
                $dropArray['due'] = $invoiceTable['due'];
                $dropArray['invoice_date'] = $invoiceTable['invoice_date'];
            endforeach;
            $this->viewBuilder()->layout('ajax');
            $this->set(compact('dropArray'));

        else:

        endif;
    }
}

