<?php
/**
 * ZucchiSecurity (http://zucchi.co.uk/)
 *
 * @link      http://github.com/zucchi/ZucchiSecurity for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */
namespace ZucchiSecurity\Authentication;

use Zucchi\Debug\Debug;
use Zucchi\Event\EventProviderTrait;
use Zucchi\ServiceManager\ServiceManagerAwareTrait;

use ZucchiSecurity\Event\SecurityEvent;
use ZucchiSecurity\Form\Login as LoginForm;
use ZucchiSecurity\Form\Logout as LogoutForm;
use ZucchiSecurity\Authentication\Plugin\PluginInterface;
use ZucchiSecurity\Service\AbstractService;
use ZucchiSecurity\Service\ServiceInterface;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManagerAwareInterface;
use ZucchiDoctrine\Entity\AbstractEntity;

use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Http\Request;

/**
 * Service to handle authentication via local entity
 * 
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package ZucchiSecurity 
 * @subpackage Auth  
 * @category Service
 */
class Service extends AbstractService implements 
    ServiceInterface, 
    ServiceManagerAwareInterface,
    EventManagerAwareInterface
{
    /**
     * @var LoginForm
     */
    protected $loginForm;
    
    /**
     * 
     * @var LogoutForm
     */
    protected $logoutForm;
    
    
    /**
     * The sessino storage to contain the authenticated entity 
     * @var SessionStorage
     */
    protected $storage;
    
    /**
     * get and build a login form
     * @return Form
     */
    public function getLoginForm()
    {
        if (!$this->loginForm) {
            $this->loginForm = new LoginForm();
            $event = new SecurityEvent();
            $event->setName(SecurityEvent::EVENT_LOGIN_FORM_BUILD);
            $event->setTarget($this->loginForm);
            $event->setServiceManager($this->getServiceManager());
            $this->getEventManager()->trigger($event);
        }
        
        $app = $this->getServiceManager()->get('application');
        $request = $app->getRequest();
        if ($request->isPost()) {
            $this->loginForm->setData($request->getPost());
            $this->loginForm->isValid(); // we dont do anything here other than to populate and test the form
        }
        
        return $this->loginForm;
    }
    
    /**
     * get and build a logout form
     * @return Form
     */
    public function getLogoutForm()
    {
        if (!$this->logoutForm) {
            $this->logoutForm = new LogoutForm();
            $this->getEventManager()->trigger(SecurityEvent::EVENT_LOGOUT_FORM_BUILD, $this->logoutForm);
        }
        
        return $this->logoutForm;
    }
    
    /**
     * attempt to authenticate using the specified plugin and request
     * 
     * @param string $plugin
     * @param Request $request
     * @return ZucchiSecurity\Authentication\Result
     */
    public function authenticate($plugin, Request $request)
    {
        if (is_string($plugin)) {
            $plugin = $this->getServiceManager()->get($plugin);
        }
        
        if (!$plugin instanceof PluginInterface) {
            throw new \RuntimeException(
                sprintf('%s is an invalid authentication plugin', get_class($plugin))
            );
        }
        $result = $plugin->authenticate($request, $this->getLoginForm());
        
        return $result;
    }
    
    /**
     * persist the authenticated entity
     * 
     * @todo: add entity expriation
     * @return \ZucchiSecurity\Authentication\Service
     */
    public function persistEntity($entity)
    {
        $this->getStorage()->write($entity);
        return $this;
    }
    
    /**
     * retrieve the authenticated entity from session storage
     * 
     * @return \Zend\Authentication\Storage\mixed
     */
    public function retrieveEntity()
    {
        return $this->getStorage()->read();
    }
    
    /**
     * clear the session storage
     * 
     * @return \ZucchiSecurity\Authentication\Service
     */
    public function clearStorage()
    {
        $this->getStorage()->clear();
        return $this;
    }
    
    /**
     * set the storage container 
     * 
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
     * 
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