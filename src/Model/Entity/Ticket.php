<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Ticket Entity
 *
 * @property int $id
 * @property int|null $price
 * @property int|null $original_price
 * @property string $ticket_id
 * @property string $event_id
 * @property int $person_id
 * @property string|null $event_url
 * @property bool|null $sold
 * @property bool|null $deleted
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property string|null $company_name
 * @property string|null $event_name
 * @property string|null $event_image
 * @property string|null $event_from
 * @property string|null $event_to
 * @property string|null $location
 * @property string|null $variant_id
 * @property string|null $variant_name
 * @property int|null $real_price
 *
 * @property \App\Model\Entity\Ticket[] $tickets
 */
class Ticket extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'price' => true,
        'original_price' => true,
        'ticket_id' => true,
        'event_id' => true,
        'person_id' => true,
        'event_url' => true,
        'sold' => true,
        'deleted' => true,
        'created' => true,
        'modified' => true,
        'company_name' => true,
        'event_name' => true,
        'event_image' => true,
        'event_from' => true,
        'event_to' => true,
        'location' => true,
        'variant_id' => true,
        'variant_name' => true,
        'real_price' => true,
        'tickets' => true,
    ];
}
