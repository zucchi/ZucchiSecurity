<?php
/**
 * ZucchiSecurity (http://zucchi.co.uk/)
 *
 * @link      http://github.com/zucchi/ZucchiSecurity for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */
namespace ZucchiSecurity\Event;

use Zucchi\ServiceManager\ServiceManagerAwareTrait;

use Zend\ServiceManager\ServiceManagerAwareInterface;

use Zend\EventManager\Event;
use Zend\Stdlib\RequestInterface as Request;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\View\Model\ModelInterface as Model;
use Zend\View\Model\ViewModel;

/**
 * Security Event Object
 * 
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package ZucchiSecurity
 * @subpackage Event
 */
class SecurityEvent extends Event implements 
    ServiceManagerAwareInterface
{
    use ServiceManagerAwareTrait;
    
    /**#@+
     * Security events triggered by eventmanager
     */
    const EVENT_LOGIN_FORM_BUILD   = 'zucchisecurity.form.login.build';
    const EVENT_LOGOUT_FORM_BUILD  = 'zucchisecurity.form.logout.build';
    
    const EVENT_AUTHENTICATE       = 'zucchisecurity.authenticate';
    const EVENT_AUTH_POST          = 'zucchisecurity.authenticate.post';
    
    const EVENT_AUTHORISE       = 'zucchisecurity.authorise';
    const EVENT_AUTHORISED       = 'zucchisecurity.authorised';
    const EVENT_UNAUTHORISED       = 'zucchisecurity.unauthorised';
    /**#@-*/
    
}
