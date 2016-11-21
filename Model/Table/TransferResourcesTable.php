<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TransferResources Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ReferenceResources
 * @property \Cake\ORM\Association\BelongsTo $OriginalResources
 * @property \Cake\ORM\Association\HasMany $TransferEvents
 * @property \Cake\ORM\Association\HasMany $TransferItems
 *
 * @method \App\Model\Entity\TransferResource get($primaryKey, $options = [])
 * @method \App\Model\Entity\TransferResource newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TransferResource[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TransferResource|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TransferResource patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TransferResource[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TransferResource findOrCreate($search, callable $callback = null)
 */
class TransferResourcesTable extends Table
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

        $this->table('transfer_resources');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->hasMany('TransferEvents', [
            'foreignKey' => 'transfer_resource_id'
        ]);
        $this->hasMany('TransferItems', [
            'foreignKey' => 'transfer_resource_id'
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

        return $validator;
    }
}
