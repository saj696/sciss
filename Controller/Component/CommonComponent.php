<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Core\Configure;
use Cake\Utility\Security;
use Hashids\Hashids;

/**
 * Common component
 */
class CommonComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * Hashids Settings
     *
     * @var array
     */

    /**
     * Hashids function
     *
     * @return object
     */
    public function hashids()
    {
        $security = Configure::read('security');
        return $hashids = new Hashids(
           $security['salt'],
           $security['min_hash_length'],
           $security['alphabet']
        );
    }
}
