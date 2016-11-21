<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Serials Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Triggers
 *
 * @method \App\Model\Entity\Serial get($primaryKey, $options = [])
 * @method \App\Model\Entity\Serial newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Serial[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Serial|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Serial patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Serial[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Serial findOrCreate($search, callable $callback = null)
 */
class SerialsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('serials');
        $this->displayField('id');
        $this->primaryKey('id');
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
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->integer('trigger_type')
            ->requirePresence('trigger_type', 'create')
            ->notEmpty('trigger_type');

        $validator
            ->integer('serial_for')
            ->requirePresence('serial_for', 'create')
            ->notEmpty('serial_for');

        $validator
            ->integer('year')
            ->requirePresence('year', 'create')
            ->notEmpty('year');

        $validator
            ->integer('serial_no')
            ->requirePresence('serial_no', 'create')
            ->notEmpty('serial_no');

        return $validator;
    }
}
