<?php
namespace App\Model\Table;

use App\Model\Entity\UserGroup;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UserGroups Model
 *
 * @property \Cake\ORM\Association\HasMany $UserGroupPermissions
 * @property \Cake\ORM\Association\HasMany $Users
 */
class UserGroupsTable extends Table
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

        $this->table('user_groups');
        $this->displayField('title_en');
        $this->primaryKey('id');

        $this->hasMany('UserGroupPermissions', [
            'foreignKey' => 'user_group_id'
        ]);
        $this->hasMany('Users', [
            'foreignKey' => 'user_group_id'
        ]);

        $this->belongsTo('Created', [
            'className'=>'Users',
            'foreignKey' => 'created_by'
        ]);
        $this->belongsTo('Updated', [
            'className'=>'Users',
            'foreignKey' => 'updated_by'
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
            ->allowEmpty('title_en');

        $validator
            ->requirePresence('title_bn', 'create')
            ->notEmpty('title_bn');

        $validator
            ->add('created_by', 'valid', ['rule' => 'numeric'])
            ->requirePresence('created_by', 'create')
            ->notEmpty('created_by');

        $validator
            ->add('created_time', 'valid', ['rule' => 'numeric'])
            ->requirePresence('created_time', 'create')
            ->notEmpty('created_time');

        $validator
            ->add('updated_by', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('updated_by');

        $validator
            ->add('updated_time', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('updated_time');

        $validator
            ->add('ordering', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('ordering');

        $validator
            ->add('status', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('status');

        return $validator;
    }
}
