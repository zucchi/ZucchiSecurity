<?php
/**
 * ZucchiSecurity (http://zucchi.co.uk/)
 *
 * @link      http://github.com/zucchi/ for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */

namespace ZucchiSecurity\Entity;

use Zend\Permissions\Acl\Acl;
use Zend\Form\Annotation AS Form;

/**
 * Interface for enforcing authorization features
 * 
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package ZucchiSecurity
 * @subpackage Entity
 * @category Authentication
 */
trait AuthorizableTrait
{
    /**
     * Access Control List for the user
     * @var Zend\Permissions\Acl\Acl
     * @Form\Exclude
     */
    private $acl;
    
    /**
     * test if action can be performed against a resource
     * 
     * @example $this->can('read', 'page');
     * @example $this->can('edit', 'article');
     * 
     * @param string $do
     * @param string $resource
     * @return boolean
     */
    public function can($do, $resource)
    {
        return false;
    }
    
    /**
     * set the ACl to use
     * @param Zend\Permissions\Acl\Acl $acl
     * @return $this;
     */
    public function setAcl(Acl $acl)
    {
        $this->acl = $acl;
        return $this;
    }
    
    /**
     * get the currently set ACL
     * 
     * @return Zend\Permissions\Acl\Acl
     */
    public function getAcl()
    {
        return $this->acl;
    }
}