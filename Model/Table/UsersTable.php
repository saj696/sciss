<?php
namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 */
class UsersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('users');
        $this->displayField('full_name_en');
        $this->primaryKey('id');
        $this->belongsTo('AdministrativeUnits', [
            'foreignKey' => 'administrative_unit_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Warehouses', [
            'foreignKey' => 'warehouse_id'
        ]);
        $this->belongsTo('Depots', [
            'foreignKey' => 'depot_id'
        ]);
        $this->belongsTo('UserGroups', [
            'foreignKey' => 'user_group_id',
            'joinType' => 'INNER'
        ]);

        $this->addBehavior('Xety/Cake3Upload.Upload', [
                'fields' => [
                    'picture' => [
                        'path' => 'u_load/users/:md5'
                    ]
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
            ->add('level_no', 'valid', ['rule' => 'numeric'])
            ->requirePresence('level_no', 'create')
            ->notEmpty('level_no');
            
        $validator
            ->allowEmpty('full_name_bn');
            
        $validator
            ->requirePresence('full_name_en', 'create')
            ->notEmpty('full_name_en');
            
        $validator
            ->requirePresence('username', 'create')
            ->notEmpty('username');

        $validator->allowEmpty('warehouse_id');
        $validator->allowEmpty('depot_id');
//        $validator
//            ->requirePresence('password', 'create')
//            ->notEmpty('password');

//        $validator
//            ->requirePresence('confirm_password', 'create')
//            ->notEmpty('confirm_password');
            
        $validator
            ->allowEmpty('picture');

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
        $rules->add($rules->isUnique(['username']));
        $rules->add($rules->existsIn(['administrative_unit_id'], 'AdministrativeUnits'));
//        $rules->add($rules->existsIn(['warehouse_id'], 'Warehouses'));
//        $rules->add($rules->existsIn(['depot_id'], 'Depots'));
        $rules->add($rules->existsIn(['user_group_id'], 'UserGroups'));
        return $rules;
    }
}
