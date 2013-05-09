<?php
/**
 * ZucchiSecurity (http://zucchi.co.uk/)
 *
 * @link      http://github.com/zucchi/ for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */

namespace ZucchiSecurity\Entity;

use Zend\Permissions\Acl\Acl;

/**
 * Interface for enforcing authorization features
 * 
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package ZucchiSecurity
 * @subpackage Entity
 * @category Authentication
 */
interface AuthorizableInterface
{
    /**
     * retrieve the appropriate role/s of the entity
     * 
     * @return array
     */
    public function getRoles();
    
    public function setAcl(Acl $acl);
    
    public function getAcl();
}