<?php
namespace App\Model\Table;

use App\Model\Entity\AdministrativeLevel;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AdministrativeLevels Model
 *
 * @property \Cake\ORM\Association\HasMany $AdministrativeUnits
 */
class AdministrativeLevelsTable extends Table
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

        $this->table('administrative_levels');
        $this->displayField('level_name');
        $this->primaryKey('id');

        $this->hasMany('AdministrativeUnits', [
            'foreignKey' => 'administrative_level_id'
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
            ->requirePresence('level_name', 'create')
            ->notEmpty('level_name');

        $validator
            ->integer('level_no')
            ->requirePresence('level_no', 'create')
            ->notEmpty('level_no');

        $validator
            ->integer('parent')
            ->requirePresence('parent', 'create')
            ->notEmpty('parent');

        return $validator;
    }
}
