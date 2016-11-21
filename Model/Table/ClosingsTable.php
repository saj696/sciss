<?php
namespace App\Model\Table;

use App\Model\Entity\Closing;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Closings Model
 */
class ClosingsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('closings');
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
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');
            
        $validator
            ->add('closing_no', 'valid', ['rule' => 'numeric'])
            ->requirePresence('closing_no', 'create')
            ->notEmpty('closing_no');
            
        $validator
            ->add('start_date', 'valid', ['rule' => 'numeric'])
            ->requirePresence('start_date', 'create')
            ->notEmpty('start_date');
            
        $validator
            ->add('end_date', 'valid', ['rule' => 'numeric'])
            ->requirePresence('end_date', 'create')
            ->notEmpty('end_date');

        return $validator;
    }
}
