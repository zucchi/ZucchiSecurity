<?php
/**
 * ZucchiSecurity (http://zucchi.co.uk/)
 *
 * @link      http://github.com/zucchi/ZucchiSecurity for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */
namespace ZucchiSecurity\Event;

use ZucchiSecurity\Entity\Guest;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\Mvc\MvcEvent;
use Zend\View\ViewEvent;
use Zend\Console\Request as CliRequest;
use ZucchiSecurity\Authentication\Result as AuthResult;
use Zucchi\ServiceManager\ServiceManagerAwareTrait;
use Zucchi\Event\EventProviderTrait as EventProviderTrait;
use Zucchi\Debug\Debug;

/**
 * Attach security based controls to events
 * 
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package ZucchiSecurity
 * @subpackage Event
 */
class SecurityListener  implements 
    ListenerAggregateInterface,
    ServiceManagerAwareInterface,
    EventManagerAwareInterface
{
    use EventProviderTrait;
    use ServiceManagerAwareTrait;
    
    /**
     * currently registered listeners
     * @var array
     */
    protected $listeners = array();
    
    /**
     * result of authentication process
     */
    protected $result;

    /**
     * @var Zend\Permissions\Acl\Acl
     */
    protected $acl;
    
    /**
     * Attach listeners to events
     * @param SharedEventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $sm = $this->getServiceManager();
        $em = $this->getEventManager();
        $shared = $em->getSharedManager();
        
        $this->listeners = array(
            $shared->attach(
                'Zend\Mvc\Application', 
                MvcEvent::EVENT_BOOTSTRAP, 
                array($this, 'logout'), 
                1000002
            ),
            $shared->attach(
                'Zend\Mvc\Application', 
                MvcEvent::EVENT_BOOTSTRAP, 
                array($this, 'authenticateRequest'), 
                1000001
            ),
            $shared->attach(
                'Zend\Mvc\Application', 
                MvcEvent::EVENT_BOOTSTRAP, 
                array($this, 'prepareAuthorise'), 
                1000000
            ),
            $shared->attach(
                'Zend\Mvc\Application', 
                MvcEvent::EVENT_ROUTE, 
                array($this, 'authoriseRoute')
            ),
            $shared->attach(
                'Zend\Mvc\Application', 
                MvcEvent::EVENT_DISPATCH,
                array($this, 'authoriseModule'),
                10
            ),
            $shared->attach(
                'Zend\View\View', 
                ViewEvent::EVENT_RENDERER, 
                array($this, 'updateView')
            ),
            $shared->attach(
                'ZucchiSecurity',
                SecurityEvent::EVENT_AUTHENTICATE, 
                array($this, 'doLocalAuthentication')
            ),
            $shared->attach(
                'ZucchiSecurity',
                SecurityEvent::EVENT_LOGIN_FORM_BUILD, 
                array('ZucchiSecurity\Authentication\Plugin\Local', 'extendLoginForm')
            ),
            $shared->attach(
                'ZucchiSecurity',
                SecurityEvent::EVENT_LOGOUT_FORM_BUILD, 
                array('ZucchiSecurity\Authentication\Plugin\Local', 'extendLogoutForm')
            ),
            $shared->attach(
                'ZucchiSecurity',
                SecurityEvent::EVENT_LOGIN_FORM_BUILD, 
                array('ZucchiSecurity\Authentication\Plugin\Captcha', 'extendLoginForm')
            ),
        );
    }
    
    /**
     * remove listeners from events
     * @param EventManagerInterface $event
     */
    public function detach(EventManagerInterface $events)
    {
        array_walk($this->listeners, array($events,'detach'));
        $this->listeners = array();
    }
    
    /**
     * test for and action a logout
     * 
     * @param MvcEvent $event
     */
    public function logout(MvcEvent $event)
    {
        $request = $event->getRequest(); 
        $isCli = ($request instanceof CliRequest);
        
        if (!$isCli && isset($_GET['logout'])) {
            $sm = $event->getApplication()->getServiceManager();
            $authService = $sm->get('zucchisecurity.auth');
            $authService->clearStorage();
            
            // redirect to get rid of query string
            $response = $event->getResponse();
            $response->getHeaders()
                     ->addHeaderLine('Location', current(explode('?', $_SERVER['REQUEST_URI'])));
            $response->setStatusCode(302);
            return $response;
        }
    }
    
    /**
     * test result and add messages to view
     * 
     * @param MvcEvent $event
     */
    public function updateView(ViewEvent $event)
    {
        if ($this->result) {
            if (!$this->result->isValid()) {
                $response = $event->getResponse();
                $response->setStatusCode($response::STATUS_CODE_403);
                
                $messages = $event->getModel()->getVariable('messages', array());
                $messages[] = array(
                    'message'     => $this->result->getMessage(),
                    'status'      => 'error',
                    'dismissable' => false,
                );
                $event->getModel()->setVariable('messages', $messages);
            }
        }
    }
    
    /**
     * If authenticated entity is present then build and attach ACL
     * 
     *  @param MvcEvent $event
     */
    public function prepareAuthorise(MvcEvent $event)
    {
        $em = $this->getEventManager();
        $sm = $event->getApplication()->getServiceManager();
        $permsService = $sm->get('zucchisecurity.perm');
        
        // attach the Unathorised View Strategy
        $event->getApplication()
              ->getEventManager()
              ->attach($sm->get('zucchisecurity.view.strategy.unauthorised'));
        
        // if authenticated result then build the ACL for that entity
        if ($this->result && $this->result->isValid()) {
            // lets build our permissions object and attach to the entity
            $permsService->attachAcl($this->result->entity);
        }
    }
    
    /**
     * test route for access
     */
    public function authoriseRoute(MvcEvent $event)
    {
        $app = $event->getTarget();
        $em = $this->getEventManager();
        $sm = $event->getApplication()->getServiceManager();
        $permsService = $sm->get('zucchisecurity.perm');
        $rm = $event->getRouteMatch()->getMatchedRouteName();

        if ($permsService->getAcl()->hasResource('route:' . $rm)) {
            if (!$permsService->can('view', 'route:' . $rm)) {
                $event->setError('error-unauthorised')
                      ->setParam('type', 'route')
                      ->setParam('resource', $rm)
                      ->setParam('privilege', 'view');
    
                $app->getEventManager()
                    ->trigger('dispatch.error', $event);
            }
        }
    }
    
    /**
     * test if privilege possible against module for current request
     * 
     * @param MvcEvent $event
     */
    public function authoriseModule(MvcEvent $event) 
    {
        $routeMatch = $event->getRouteMatch();
        $module = $routeMatch->getParam('module', false);
        
        if ($routeMatch && $module) {
            $app = $event->getApplication();
            $sm = $app->getServiceManager();
            $request = $event->getRequest();

            $permService = $sm->get('zucchisecurity.perm');
            
            // if the module has been registered in the acl the test
            if ($permService->getAcl()->hasResource('module:' . $module)) {
                $config = $sm->get('zucchisecurity.perm.options');
                
                $map = $config->getMap();
                
                $action = $routeMatch->getParam('action', false);
                if (!$action) {
                    $action = strtolower($request->getMethod());
                }
                
                
                
                if (isset($map[$module][$action])) {
                    $privilege = $map[$module][$action];
                } else if (isset($map['defaults'][$action])) {
                    $privilege = $map['defaults'][$action];
                } else {
                    $privilege = $action;
                } 
                
                if (!$permService->can($privilege, 'module:' . $module)) {
                    $event->setError('error-unauthorised')
                      ->setParam('type', 'route')
                      ->setParam('resource', $module)
                      ->setParam('privilege', $privilege);
    
                    $app->getEventManager()
                        ->trigger('dispatch.error', $event);
                }
            }
        }
    }
    
    /**
     * tests for valid entity in the session.
     * 
     * If none found it will trigger the authenticate event for plugins to 
     * test the request and login form
     *  
     * Sets a result 
     * 
     * N.B Cannot re-authenticate while a user is logged in
     * 
     * @param MvcEvent $event
     */
    public function authenticateRequest(MvcEvent $event)
    {
        $em = $this->getEventManager();
        $sm = $event->getApplication()->getServiceManager();
        $authService = $sm->get('zucchisecurity.auth');
        
        // retrieve from session
        $entity = $authService->retrieveEntity();
        if ($entity) {
            $this->result = new AuthResult();
            $this->result->entity = $entity;
            
        } else {
            $authEvent = new SecurityEvent();
            $authEvent->setName(SecurityEvent::EVENT_AUTHENTICATE);
            $authEvent->setTarget($event->getApplication());
            $results = $em->trigger($authEvent);
            
            $this->result = $result = $results->last();
            if ($result && $result->isValid()) {
                $authService->persistEntity($result->entity);
                
                $postEvent = new SecurityEvent();
                $postEvent->setName(SecurityEvent::EVENT_AUTH_POST);
                $postEvent->setTarget($result->entity);
                $postEvent->setServiceManager($sm);
                $em->trigger($postEvent);
                
                // redirect to allow back button functionality without post resubmit
                $response = $event->getResponse();
                $response->getHeaders()
                         ->addHeaderLine('Location', $_SERVER['REQUEST_URI']);
                $response->setStatusCode(302);
                return $response;
                
            } else if ($result && !$result->isValid()) {
                $response = $event->getResponse();
                $response->setStatusCode($response::STATUS_CODE_403);
            }
        }
    }
    
    /**
     * process authentication for local plugin
     * 
     * @param MvcEvent $event
     */
    public function doLocalAuthentication(SecurityEvent $event)
    {   
        $app = $event->getTarget();
        $sm = $app->getServiceManager();
        $request = $app->getRequest(); 
        $isCli = ($request instanceof CliRequest);
        
        $auth = $sm->get('zucchisecurity.auth');
        
        if (!$isCli &&  $request->isPost()) {
            $actions = $request->getPost('actions');
            if (isset($actions['login'])) {
                // authenticate through service
                $result = $auth->authenticate('zucchisecurity.auth.local', $request);
                if ($result->isValid()) {
                    $event->stopPropagation(true);
                }
                return $result;
            } 
        }
    }
}