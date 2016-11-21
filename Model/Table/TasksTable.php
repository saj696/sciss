<?php
namespace App\Model\Table;

use App\Model\Entity\Task;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Tasks Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ParentTasks
 * @property \Cake\ORM\Association\HasMany $ChildTasks
 * @property \Cake\ORM\Association\HasMany $UserGroupRole
 */
class TasksTable extends Table
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

        $this->table('tasks');
        $this->displayField('name_en');
        $this->primaryKey('id');

        $this->belongsTo('ParentTasks', [
            'className' => 'Tasks',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('ChildTasks', [
            'className' => 'Tasks',
            'foreignKey' => 'parent_id'
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
            ->requirePresence('name_en', 'create')
            ->notEmpty('name_en');

        $validator
            ->requirePresence('name_bn', 'create')
            ->notEmpty('name_bn');

        $validator
            ->requirePresence('description', 'create')
            ->notEmpty('description');

        $validator
            ->requirePresence('icon', 'create')
            ->notEmpty('icon');


        $validator
            ->add('ordering', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('ordering');

        $validator
            ->add('position_left_01', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('position_left_01');

        $validator
            ->add('position_top_01', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('position_top_01');

        $validator
            ->add('status', 'valid', ['rule' => 'numeric'])
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        return $validator;
    }
    // list of all active task *for menu
    public function findActive(Query $query)
    {
        return $query->where(['status'=>1]);
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
        $rules->add($rules->existsIn(['parent_id'], 'ParentTasks'));
        return $rules;
    }
}
