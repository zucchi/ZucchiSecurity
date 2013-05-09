<?php
/**
 * ZucchiSecurity (http://zucchi.co.uk/)
 *
 * @link      http://github.com/zucchi/ZucchiSecurity for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */
namespace ZucchiSecurity\Authentication\Plugin;

use Zend\EventManager\EventManagerAwareInterface;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\Stdlib\AbstractOptions;
use Zucchi\ServiceManager\ServiceManagerAwareTrait;
use Zucchi\Event\EventProviderTrait;

/**
 * Abstract authentication adapter
 * 
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package ZucchiSecurity 
 * @subpackage Auth  
 * @category Plugin
 */
abstract class AbstractPlugin implements
    ServiceManagerAwareInterface,
    EventManagerAwareInterface
{
    use ServiceManagerAwareTrait;
    use EventProviderTrait;
    
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