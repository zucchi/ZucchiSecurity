<?php
/**
 * ZucchiSecurity (http://zucchi.co.uk/)
 *
 * @link      http://github.com/zucchi/ZucchiSecurity for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */
namespace ZucchiSecurity\View\Helper;

use Zucchi\ServiceManager\ServiceManagerAwareTrait;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Storage\Session as SessionStorage;

/**
 * view helper to retrieve authenticated entity
 *  
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package ZucchiSecurity 
 * @subpackage View/Helper
 */
class Identity extends AbstractHelper implements 
    ServiceManagerAwareInterface
{
    use ServiceManagerAwareTrait;
    
    /**
     * 
     * @var SessionStorage
     */
    protected $storage;
    
    
    /**
     * 
     * @return \Zucchi\Controller\Plugin\Auth
     */
    public function __invoke()
    {
        return $this->getEntity();
    }
    
    public function getEntity()
    {
        return $this->getStorage()->read();
    }
    
    /**
     * set the storage container 
     * @param SessionStorage $storage
     * @return $this
     */
    public function setStorage(SessionStorage $storage)
    {
        $this->storage = $storage;
        return $this;
    }
    
    /**
     * get the current storage container
     * @return \Zend\Authentication\Storage\Session
     */
    public function getStorage()
    {
        if (!$this->storage) {
            $this->storage = new SessionStorage();
        }
        return $this->storage;
    }
    
}