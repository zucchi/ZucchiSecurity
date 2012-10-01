<?php
/**
 * ZucchiSecurity (http://zucchi.co.uk/)
 *
 * @link      http://github.com/zucchi/ZucchiSecurity for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */
namespace ZucchiSecurity\Authentication\Plugin;

use Zend\EventManager\EventInterface;
use ZucchiSecurity\Form\Login as LoginForm;

/**
 * Interface for Authentication plugin
 * 
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package ZucchiSecurity 
 * @subpackage Auth  
 * @category Plugin
 */
interface PluginInterface
{
    /**
     * Extend login form 
     * @param EventInterface $event
     */
    static public function extendLoginForm(EventInterface $event);
    
    /**
     * Extend logout form
     * @param EventInterface $event
     */
    static public function extendLogoutForm(EventInterface $event);
    
}