<?php
namespace App\Model\Table;

use App\Model\Entity\Category;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Categories Model
 */
class CategoriesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('categories');
        $this->displayField('name');
        $this->primaryKey('id');
        $this->hasMany('Items', [
            'foreignKey' => 'category_id'
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
            ->add('id', 'valid', ['rule' => 'integer'])
            ->allowEmpty('id', 'create');
            
        $validator
            ->requirePresence('level_no', 'create')
            ->notEmpty('level_no');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');
            
        $validator
            ->requirePresence('parent', 'create')
            ->notEmpty('parent');
            
//        $validator
//            ->add('number_of_direct_successors', 'valid', ['rule' => 'integer'])
//            ->requirePresence('number_of_direct_successors', 'create')
//            ->notEmpty('number_of_direct_successors');
            
        $validator
            ->requirePresence('status', 'create')
            ->notEmpty('status');

        $validator
            ->requirePresence('prefix', 'create')
            ->notEmpty('status');
            
        $validator
            ->allowEmpty('created_by');
            
        $validator
            ->allowEmpty('created_date');
            
        $validator
            ->allowEmpty('updated_by');
            
        $validator
            ->allowEmpty('updated_date');

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

        return $rules;
    }
}
