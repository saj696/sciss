<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TransferEvents Model
 *
 * @property \Cake\ORM\Association\BelongsTo $TransferResources
 * @property \Cake\ORM\Association\BelongsTo $Recipients
 *
 * @method \App\Model\Entity\TransferEvent get($primaryKey, $options = [])
 * @method \App\Model\Entity\TransferEvent newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TransferEvent[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TransferEvent|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TransferEvent patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TransferEvent[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TransferEvent findOrCreate($search, callable $callback = null)
 */
class TransferEventsTable extends Table
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

        $this->table('transfer_events');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->belongsTo('TransferResources', [
            'foreignKey' => 'transfer_resource_id',
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
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->integer('recipient_action')
            ->requirePresence('recipient_action', 'create')
            ->notEmpty('recipient_action');

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
        $rules->add($rules->existsIn(['transfer_resource_id'], 'TransferResources'));
        return $rules;
    }
}
