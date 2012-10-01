<?php
/**
 * ZucchiSecurity (http://zucchi.co.uk/)
 *
 * @link      http://github.com/zucchi/ZucchiSecurity for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */
namespace ZucchiSecurity\Permissions\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * 
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package ZucchiSecurity 
 * @subpackage Auth 
 * @category Plugin/Options
 */
class PermissionsOptions extends AbstractOptions 
{
    /**
     * Should a blanket 'deny' be applied to the ACL
     * 
     * @var bool
     */
    protected $blanketDeny = true;
    
    protected $defaultRole = 'guest';
    
    protected $roles = array(
        'guest' => array(),
    );

    /**
     * mapping of http methods/ controller actions to privileges
     */
    protected $map = array();
    
    protected $privileges = array();
    
    protected $resources = array();
    
    protected $rules = array();
    
	/**
     * @return the $defaultRole
     */
    public function getDefaultRole ()
    {
        return $this->defaultRole;
    }

	/**
     * @param string $defaultRole
     */
    public function setDefaultRole ($defaultRole)
    {
        $this->defaultRole = $defaultRole;
    }

	/**
     * @return the $roles
     */
    public function getRoles ()
    {
        return $this->roles;
    }

	/**
     * @param multitype:multitype:  $roles
     */
    public function setRoles ($roles)
    {
        $this->roles = $roles;
    }

	/**
     * @return the $resources
     */
    public function getResources ()
    {
        return $this->resources;
    }

	/**
     * @param multitype: $resources
     */
    public function setResources ($resources)
    {
        $this->resources = $resources;
    }
	/**
     * @return the $blanketDeny
     */
    public function getBlanketDeny ()
    {
        return $this->blanketDeny;
    }

	/**
     * @param boolean $blanketDeny
     */
    public function setBlanketDeny ($blanketDeny)
    {
        $this->blanketDeny = $blanketDeny;
    }
	/**
     * @return the $privileges
     */
    public function getPrivileges ()
    {
        return $this->privileges;
    }

	/**
     * @param multitype: $privileges
     */
    public function setPrivileges ($privileges)
    {
        $this->privileges = $privileges;
    }

	/**
     * @return the $rules
     */
    public function getRules ()
    {
        return $this->rules;
    }

	/**
     * @param multitype: $rules
     */
    public function setRules ($rules)
    {
        $this->rules = $rules;
    }
	/**
     * @return the $map
     */
    public function getMap ()
    {
        return $this->map;
    }

	/**
     * @param multitype: $map
     */
    public function setMap ($map)
    {
        $this->map = $map;
    }




}