<?php
/**
 * ZucchiSecurity (http://zucchi.co.uk/)
 *
 * @link      http://github.com/zucchi/ZucchiSecurity for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */
namespace ZucchiSecurity\View\Strategy;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\View\Model\ViewModel;

/**
 * View strategy to catch unauthorised errors
 * 
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package ZucchiSecurity
 * @subpackage View
 */
class Unauthorised implements ListenerAggregateInterface
{
    /**
     * @var string
     */
    protected $template = 'error/unauthorised';

    /**
     * currently registered listeners
     * @var array
     */
    protected $listeners = array();

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_DISPATCH_ERROR, 
            array($this, 'onDispatchError'), 
            -5000
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
     * set the view template to use
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * get the template to use
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    public function onDispatchError(MvcEvent $e)
    {
        $result = $e->getResult();
        if ($result instanceof Response) {
            return;
        }

        $error = $e->getError();
        if($error == 'error-unauthorised') {
            $params = array(); 
            $params['type'] = $e->getParam('type');
            $params['privilege'] = $e->getParam('privilege');
            
            switch ($params['type']) {
                case 'route':
                    $params['type'] = 'url';
                    $params['resource'] = $_SERVER['REQUEST_URI'];
                    break;
                default:
                    $params['resource'] = $e->getParam('resource');
                    break;
            }

            $model = new ViewModel($params);
            $model->setTemplate($this->getTemplate());
            $e->getViewModel()->addChild($model);

            $response = $e->getResponse();
            if (!$response) {
                $response = new HttpResponse();
                $e->setResponse($response);
            }
            $response->setStatusCode(403);
        }
    }
}