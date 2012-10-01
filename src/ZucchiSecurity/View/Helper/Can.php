<?php
/**
 * ZucchiSecurity (http://zucchi.co.uk/)
 *
 * @link      http://github.com/zucchi/ZucchiSecurity for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */
namespace ZucchiSecurity\View\Helper;

use ZucchiSecurity\Permissions\PermissionsAwareTrait;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Storage\Session as SessionStorage;

/**
 * View helper to test ACL
 * 
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package ZucchiSecurity 
 * @subpackage View/Helper
 */
class Can extends AbstractHelper implements 
    ServiceManagerAwareInterface
{
    use PermissionsAwareTrait;
    
    /**
     *
     * @example $this->can('read', 'resource')
     * @param string $do
     * @param string $on
     * @return boolean
     */
    public function __invoke($do, $on)
    {
        return $this->getPermissionsService()->can($do, $on);
    }
}