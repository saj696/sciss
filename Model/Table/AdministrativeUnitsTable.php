<?php
namespace App\Model\Table;

use App\Model\Entity\AdministrativeUnit;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AdministrativeUnits Model
 */
class AdministrativeUnitsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('administrative_units');
        $this->displayField('unit_name');
        $this->primaryKey('id');
        $this->belongsTo('AdministrativeLevels', [
            'foreignKey' => 'administrative_level_id',
            'joinType' => 'INNER'
        ]);

        $this->hasMany('Customers', [
            'foreignKey' => 'administrative_unit_id'
        ]);
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
            ->requirePresence('unit_name', 'create')
            ->notEmpty('unit_name');

        $validator->requirePresence('prefix')
        ->notEmpty('prefix', 'Please fill this field')
        ->add('prefix', [
            'length' => [
                'rule' => ['minLength', 4],
                'message' => 'Prefix need to be at least 4 characters long',
            ]
        ]);
            
        $validator
            ->add('parent', 'valid', ['rule' => 'numeric'])
            ->requirePresence('parent', 'create')
            ->notEmpty('parent');

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
        $rules->add($rules->isUnique(['prefix']));
        $rules->add($rules->existsIn(['administrative_level_id'], 'AdministrativeLevels'));
        return $rules;
    }
}
