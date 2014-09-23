<?php
/**
 * States
 *
 * LICENSE
 *
 * This source file is subject to the MIT license and the version 3 of the GPL3
 * license that are bundled with this package in the folder licences
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@uni-alteri.com so we can send you a copy immediately.
 *
 * @subpackage  StatesBundle
 * @copyright   Copyright (c) 2009-2014 Uni Alteri (http://agence.net.ua)
 * @link        http://teknoo.it/states Project website
 * @license     http://teknoo.it/states/license/mit         MIT License
 * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 * @version     0.9.9
 */

namespace UniAlteri\Bundle\StatesBundle\Entity;

use UniAlteri\States\Exception\InvalidArgument;
use UniAlteri\States\Proxy\Integrated;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class IntegratedEntity
 * @package     States
 * @subpackage  StatesBundle
 * @copyright   Copyright (c) 2009-2014 Uni Alteri (http://agence.net.ua)
 * @link        http://teknoo.it/states Project website
 * @license     http://teknoo.it/states/license/mit         MIT License
 * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class IntegratedEntity extends Integrated
{
    /**
     * Class name of the factory to use in set up to initialize this object in this construction
     * @var string
     */
    protected static $_startupFactoryClassName = '\UniAlteri\Bundle\StatesBundle\Factory\StartupFactory';

    /**
     * Constructor
     */
    public function __construct()
    {
        //Initialize proxy
        parent::__construct();

        //Select good state
        $this->updateState();
    }

    /**
     * Doctrine does not call the construction and create a new instance without it....
     * This callback reinitialize proxy
     * @ORM\PostLoad()
     */
    public function postLoadDoctrine()
    {
        //Call the method of the trait to initialize local attributes of the proxy
        $this->_initializeProxy();
        //Call the startup factory to initialize this proxy
        $this->_initializeObjectWithFactory();
        $this->updateState();
    }

    /**
     * Callback to extends in your entity to apply states according to your entity's value
     */
    public function updateState()
    {
    }

    /**
     * No use magic getter/setter here. Add this to be compliant with twig...
     *
     * @param string $name
     * @return bool|mixed
     */
    public function __isset($name)
    {
        return false;
    }

    /**
     * Check if the current entity is in the required state defined by $stateName
     * @param string $stateName
     * @return bool
     * @throws InvalidArgument when $stateName is not a valid string
     */
    public function inState($stateName)
    {
        if (!is_string($stateName) && (is_object($stateName) && !is_callable(array($stateName, '__toString')))) {
            throw new InvalidArgument('Error, $stateName is not valid');
        }

        $stateName = (string) $stateName;
        $enabledStatesList = $this->listEnabledStates();

        if (is_array($enabledStatesList) && !empty($enabledStatesList)) {
            //array_flip + isset is more efficient than in_array
            $stateName = str_replace('_', '', strtolower($stateName));
            $enabledStatesList = array_flip(
                array_map('strtolower', $enabledStatesList)
            );
            return isset($enabledStatesList[$stateName]);
        } else {
            return false;
        }
    }
}
