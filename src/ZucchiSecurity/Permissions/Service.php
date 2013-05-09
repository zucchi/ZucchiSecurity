<?php
/**
 * ZucchiSecurity (http://zucchi.co.uk/)
 *
 * @link      http://github.com/zucchi/ZucchiSecurity for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */
namespace ZucchiSecurity\Permissions;

use Zucchi\Debug\Debug;

use ZucchiSecurity\Service\AbstractService;
use ZucchiSecurity\Service\ServiceInterface;
use ZucchiSecurity\Entity\AuthorizableInterface;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\Permissions\Acl;

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
     * 
     * @var Zend\Permissions\Acl\Acl
     */
    public $acl;
    
    /**
     * Roles available to the permissions service
     * @var array
     */
    public $roles = array();
    
    /**
     * Build an appropriate ACL for the roles provided
     * 
     * Should result in a Dedicated ACL for just that entity rather than a 
     * bloated and slow ACL with all data populated 
     * 
     * @param AuthorizableInterface $entity
     * return ZucchiSecurity\Permissions\Service
     */
    public function buildAcl($roles)
    {
        if (!is_array($roles)) {
            $roles = array($roles);
        }
            
        $this->roles = $roles;
        
        $this->acl = new Acl\Acl();
        
        // populate roles
        foreach ($this->roles AS $role) {
            // load entities roles
            $this->addRole($role);
        }

        // loop over different resource types and populate
        $resourcesRoots = $this->options->getResources();
        foreach ($resourcesRoots AS $type => $resources) {
            foreach ($resources as $resource => $data) {
                // quick test to allow is to define terminating reasources
                if (is_string($data)) {
                    $resource = $data;
                    $data = array();
                }
                $this->addResource($type, $resource, $data);
            }
        }
        
        // loop through hardcoded rules
        $rules = $this->options->getRules();
        foreach ($rules as $rule) {
            $this->addRule($rule);
        }
        
        // apply blanket 'allow' or 'deny'
        if ($this->options->blanketDeny) {
            $this->acl->deny();
        } else {
            $this->acl->allow();
        }
        
        return $this->acl;
    }
    
    /**
     * test all available roles for current acl 
     * @param string $privilege
     * @param string $resource
     * @return boolean
     */
    public function can($privilege, $resource)
    {
        $result = false;
        foreach ($this->getRoles() as $role) {
            if ($this->acl->isAllowed($role, $resource, $privilege)) {
                $result = true;
            }
        }
        return $result;
    }
    
    /**
     * retrieve the current ACL.
     * 
     * @return \Zend\Permissions\Acl\Acl
     */
    public function getAcl()
    {
        if (!$this->acl) {
            $this->setRoles(array($this->options->defaultRole));
            $this->buildAcl($this->roles);
        }
        return $this->acl;
    }
    
    /**
     * set roles for service and attach ACL to authorizable entity
     * @param AuthorizableInterface $entity
     */
    public function attachAcl(AuthorizableInterface $entity)
    {
        $this->roles = $entity->getRoles();
        $this->buildAcl($this->roles);
        $entity->setAcl($this->acl);
    }
    
    /**
     * parse config and add role to ACL
     * 
     * recursive function to allow multiple inheritence
     * 
     * @param string $role
     */
    protected function addRole($role)
    {
        $roles = $this->options->getRoles();
        
        if (!$this->acl->hasRole($role)) {
            $parents = isset($roles[$role]['parents']) ? $roles[$role]['parents'] : array(); 
            foreach ($parents AS $parent) {
                $this->addRole($parent);
            }

            if ($role) {
                $this->acl->addRole($role, $parents);
            }
        }
    }
    
    /**
     * Add resource (of a type) and its children
     * 
     * @param string $type
     * @param string $resource
     * @param array $data
     * @param string $parent
     */
    protected function addResource($type, $resource, $data, $parent = null)
    {
        $route = $type . ':' . $resource;
        
        if (!$this->acl->hasResource($route)) {
            $this->acl->addResource($route, $parent);
            
            if (isset($data['children'])) {
                foreach ($data['children'] as $child => $childData) {
                    
                    if (is_string($childData)) { // quick test to allow is to define leaf reasources
                        $child = $childData;
                        $childData = array();
                    }
                    
                    $this->addResource($type, $resource . '/' . $child, $childData, $route);
                }
            }
        }
    }
    
    /**
     * Add a rule to the ACL
     * @param array $rule
     * @return boolean
     */
    protected function addRule($rule)
    {
        $method = 'allow';
        $roles = null;
        $resources = null;
        $privileges = null;
        $assertion = null;
        
        if (isset($rule['action']) && (in_array($rule['action'], array('allow', 'deny')))) {
            $method = $rule['action'];
        }
        
        if (isset($rule['role'])) {
            $roles = array();
            if (!is_array($rule['role'])) {
                $rule['role'] = array($rule['role']);
            }
            foreach ($rule['role'] as $aRole) {
                if ($this->acl->hasRole($aRole)) {
                    $roles[] = $aRole;
                }
            }
            
            if (empty($roles)) {
                // no valid roles so return false
                return false;
            }
        }
        
        if (isset($rule['resource'])) {
            $resources = array();
            if (!is_array($rule['resource'])) {
                $rule['resource'] = array($rule['resource']);
            }
            foreach ($rule['resource'] as $aResource) {
                if ($this->acl->hasResource($aResource)) {
                    $resources[] = $aResource;
                }
            }
            
            if (empty($resources)) {
                // no valid roles so return false
                return false;
            }
        }
        
        if (isset($rule['privilege'])) {
            $privileges = array();
            if (!is_array($rule['privilege']    )) {
                $rule['privilege'] = array($rule['privilege']);
            }
            $privileges = $rule['privilege'];
        }
        
        if (isset($rule['assertion'])) {
            $assertion = $rule['assertion'];
        }
        
        return $this->acl->{$method}($roles, $resources, $privileges, $assertion);
        
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

}