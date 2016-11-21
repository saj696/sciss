<?php
namespace App\Model\Table;

use App\Model\Entity\Item;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Items Model
 */
class ItemsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('items');
        $this->displayField('name');
        $this->primaryKey('id');
        $this->belongsTo('Categories', [
            'foreignKey' => 'category_id',
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');
            
        $validator
            ->requirePresence('code', 'create')
            ->notEmpty('code');
            
        $validator
//            ->add('pack_size', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('pack_size');
            
        $validator
//            ->add('unit', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('unit');
            
        $validator
//            ->add('box_size', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('box_size');
            
        $validator
//            ->add('cash_sales_price', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('cash_sales_price');
            
        $validator
//            ->add('credit_sales_price', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('credit_sales_price');
            
        $validator
//            ->add('retail_price', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('retail_price');

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
        $rules->add($rules->existsIn(['category_id'], 'Categories'));
        return $rules;
    }
}
