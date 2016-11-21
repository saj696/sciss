<?php
namespace App\Model\Table;

use App\Model\Entity\FinancialYear;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FinancialYears Model
 */
class FinancialYearsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('financial_years');
        $this->displayField('id');
        $this->primaryKey('id');
        $this->hasMany('CustomerAccounts', [
            'foreignKey' => 'financial_year_id'
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
            ->allowEmpty('year_name');
            
        $validator
            ->add('year_start', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('year_start');
            
        $validator
            ->add('year_end', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('year_end');

        return $validator;
    }
}
