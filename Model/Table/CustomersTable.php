<?php
namespace App\Model\Table;

use App\Model\Entity\Customer;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Customers Model
 */
class CustomersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('customers');
        $this->displayField('name');
        $this->primaryKey('id');
        $this->belongsTo('AdministrativeUnits', [
            'foreignKey' => 'administrative_unit_id',
            'joinType' => 'INNER'
        ]);

        $this->addBehavior('Xety/Cake3Upload.Upload', [
                'fields' => [
                    'picture' => [
                        'path' => 'u_load/customer/photo/:md5'
                    ],
                    'signature' => [
                        'path' => 'u_load/customer/signature/:md5'
                    ],
                    'nid' => [
                        'path' => 'u_load/customer/nid/:md5'
                    ],
                    'distributor_paper' => [
                        'path' => 'u_load/customer/distributor_paper/:md5'
                    ],
                    'appraisal_form' => [
                        'path' => 'u_load/customer/appraisal_form/:md5'
                    ],
                    'appointment_form' => [
                        'path' => 'u_load/customer/appointment_form/:md5'
                    ],
                ]
            ]
        );
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');
            
        $validator
            ->requirePresence('code', 'create')
            ->notEmpty('code');
            
        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');
            
        $validator
            ->requirePresence('address','create')
            ->notEmpty('address');
            
        $validator
            ->requirePresence('proprietor','create')
            ->notEmpty('proprietor');
            
        $validator
            ->requirePresence('contact_person','create')
            ->notEmpty('contact_person');
            
//        $validator
//            ->add('business_type', 'valid', ['rule' => 'numeric'])
//            ->allowEmpty('business_type');
            
        $validator
            ->allowEmpty('mobile');
            
        $validator
            ->allowEmpty('telephone');
            
        $validator
            ->add('email', 'valid', ['rule' => 'email'])
            ->allowEmpty('email');
            
//        $validator
//            ->add('credit_limit', 'valid', ['rule' => 'numeric'])
//            ->allowEmpty('credit_limit');
            
//        $validator
//            ->add('credit_invoice_days', 'valid', ['rule' => 'numeric'])
//            ->allowEmpty('credit_invoice_days');
//
//        $validator
//            ->add('cash_invoice_days', 'valid', ['rule' => 'numeric'])
//            ->allowEmpty('cash_invoice_days');

//        $validator
//            ->requirePresence('customer_status','create')
//            ->notEmpty('customer_status');
//
//        $validator
//            ->requirePresence('pesticide_no','create')
//            ->notEmpty('pesticide_no');
//
//        $validator
//            ->requirePresence('pesticide_issue_date','create')
//            ->notEmpty('pesticide_issue_date');
//
//        $validator
//            ->requirePresence('pesticide_end_date','create')
//            ->notEmpty('pesticide_end_date');
//
//        $validator
//            ->requirePresence('trade_no','create')
//            ->notEmpty('trade_no');
//
//        $validator
//            ->requirePresence('trade_issue_date','create')
//            ->notEmpty('trade_issue_date');
//
//        $validator
//            ->requirePresence('trade_end_date','create')
//            ->notEmpty('trade_end_date');

        $validator
            ->allowEmpty('picture');

        $validator
            ->allowEmpty('nid');

        $validator
            ->allowEmpty('signature');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
//        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->isUnique(['code']));
        $rules->add($rules->existsIn(['administrative_unit_id'], 'AdministrativeUnits'));
        return $rules;
    }
}
