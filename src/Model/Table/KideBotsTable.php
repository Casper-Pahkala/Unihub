<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * KideBots Model
 *
 * @method \App\Model\Entity\KideBot newEmptyEntity()
 * @method \App\Model\Entity\KideBot newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\KideBot[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\KideBot get($primaryKey, $options = [])
 * @method \App\Model\Entity\KideBot findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\KideBot patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\KideBot[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\KideBot|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\KideBot saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\KideBot[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\KideBot[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\KideBot[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\KideBot[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class KideBotsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('kide_bots');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmptyString('password');

        $validator
            ->integer('current_user_id')
            ->allowEmptyString('current_user_id');

        $validator
            ->scalar('auth_token')
            ->maxLength('auth_token', 255)
            ->allowEmptyString('auth_token');

        $validator
            ->dateTime('auth_token_expires_in')
            ->allowEmptyDateTime('auth_token_expires_in');

        $validator
            ->boolean('in_trade')
            ->allowEmptyString('in_trade');

        return $validator;
    }
}
