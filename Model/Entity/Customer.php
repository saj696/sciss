<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Customer Entity.
 *
 * @property int $id
 * @property int $administrative_unit_id
 * @property \App\Model\Entity\AdministrativeUnit $administrative_unit
 * @property string $code
 * @property string $name
 * @property string $address
 * @property string $proprietor
 * @property string $contact_person
 * @property int $business_type
 * @property string $mobile
 * @property string $telephone
 * @property string $email
 * @property float $credit_limit
 * @property int $credit_invoice_days
 * @property int $cash_invoice_days
 * @property int $approved_by
 * @property int $approval_date
 * @property int $is_mango
 * @property int $is_potato
 * @property int $customer_type
 * @property int $status
 * @property int $created_by
 * @property int $created_date
 * @property int $updated_by
 * @property int $updated_date
 * @property \App\Model\Entity\CustomerAccount[] $customer_accounts
 */
class Customer extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
