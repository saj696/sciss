<?php
namespace App\Model\Table;

use App\Model\Entity\DepotCoverage;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * DepotCoverages Model
 */
class DepotCoveragesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('depot_coverages');
        $this->displayField('id');
        $this->primaryKey('id');
        $this->belongsTo('Depots', [
            'foreignKey' => 'depot_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('AdministrativeUnits', [
            'foreignKey' => 'administrative_unit_id',
            'joinType' => 'INNER'
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
            ->add('level_no', 'valid', ['rule' => 'numeric'])
            ->requirePresence('level_no', 'create')
            ->notEmpty('level_no');

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
        $rules->add($rules->existsIn(['depot_id'], 'Depots'));
        $rules->add($rules->existsIn(['administrative_unit_id'], 'AdministrativeUnits'));
        return $rules;
    }
}
