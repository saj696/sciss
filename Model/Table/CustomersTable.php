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
                    'distributor_paper' => [
                        'path' => 'u_load/customer/distributor/:md5'
                    ],
                    'appointment_form' => [
                        'path' => 'u_load/customer/appointment/:md5'
                    ],
                    'appraisal_form' => [
                        'path' => 'u_load/customer/appraisal/:md5'
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
            ->requirePresence('name_distributor', 'create')
            ->notEmpty('name_distributor');
            
        $validator
            ->allowEmpty('proprietor_name');

        $validator
            ->allowEmpty('mobile_one');
            
        $validator
            ->allowEmpty('phone');
            
        $validator
            ->add('email', 'valid', ['rule' => 'email'])
            ->allowEmpty('email');

        $validator
            ->allowEmpty('picture');

        $validator
            ->allowEmpty('nid');

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
