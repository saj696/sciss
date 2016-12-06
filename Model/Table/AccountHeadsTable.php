<?php
namespace App\Model\Table;

use App\Model\Entity\AccountHead;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AccountHeads Model
 */
class AccountHeadsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('account_heads');
        $this->displayField('name');
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
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');
            
        $validator
            ->requirePresence('name')
            ->notEmpty('name');
            
        $validator
            ->allowEmpty('parent');

        $validator
            ->allowEmpty('contra_id');

        $validator
            ->allowEmpty('code');
            
        $validator
            ->allowEmpty('applies_to');
            
        $validator
            ->allowEmpty('is_contra');

        return $validator;
    }
}
