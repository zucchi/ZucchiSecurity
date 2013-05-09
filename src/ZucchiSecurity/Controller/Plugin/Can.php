<?php
/**
 * ZucchiSecurity (http://zucchi.co.uk)
 *
 * @link      http://github.com/zucchi/ZucchiSecurity for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zucchi Limited. (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */
namespace ZucchiSecurity\Controller\Plugin;
 
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use ZucchiSecurity\Permissions\PermissionsAwareTrait;
use Zend\ServiceManager\ServiceManagerAwareInterface;

/**
 * Controller plugin to allow easy testing of ACL
 * 
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package ZucchiSecurity
 * @subpackage Controller
 * @category Plugin
 */
class Can extends AbstractPlugin implements 
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