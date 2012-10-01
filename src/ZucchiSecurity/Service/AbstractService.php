<?php
/**
 * ZucchiSecurity (http://zucchi.co.uk/)
 *
 * @link      http://github.com/zucchi/ZucchiSecurity for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */
namespace ZucchiSecurity\Service;

use Zucchi\Event\EventProviderTrait as EventProviderTrait;
use Zucchi\ServiceManager\ServiceManagerAwareTrait;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\Stdlib\AbstractOptions;

/**
 * Abstract authentication service
 * 
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package ZucchiSecurity 
 * @subpackage Auth  
 * @category Service
 */
abstract class AbstractService implements 
    ServiceManagerAwareInterface,
    EventManagerAwareInterface
{
    use EventProviderTrait;
    use ServiceManagerAwareTrait;
    
    /**
     * @var LocalOptions
     */
    protected $options;
    
    /**
     * @param LocalOptions $options
     * @return \ZucchiSecurity\Authentication\Plugin\Local
     */
    public function setOptions(AbstractOptions $options)
    {
        $this->options = $options;
        return $this;
    }
    
    /**
     * @return \ZucchiSecurity\Authentication\Plugin\Options\LocalOptions
     */
    public function getOptions()
    {
        return $this->options;
    }
    
}