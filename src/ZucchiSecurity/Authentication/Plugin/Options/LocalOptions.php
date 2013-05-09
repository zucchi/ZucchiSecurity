<?php
/**
 * ZucchiSecurity (http://zucchi.co.uk/)
 *
 * @link      http://github.com/zucchi/ZucchiSecurity for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */
namespace ZucchiSecurity\Authentication\Plugin\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * default options for local authentication 
 * 
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package ZucchiSecurity 
 * @subpackage Auth 
 * @category Plugin/Options
 */
class LocalOptions extends AbstractOptions 
{
    protected $entity = 'ZucchiUser\Entity\User';
    
    protected $identityFields = array('identity', 'email');

    protected $allowLocked = false;
    
	/**
     * @return the $entity
     */
    public function getEntity ()
    {
        return $this->entity;
    }

	/**
     * @param string $entity
     */
    public function setEntity ($entity)
    {
        $this->entity = $entity;
    }

	/**
     * @return the $identityFields
     */
    public function getIdentityFields ()
    {
        return $this->identityFields;
    }

	/**
     * @param multitype:string  $identityFields
     */
    public function setIdentityFields ($identityFields)
    {
        $this->identityFields = $identityFields;
    }

	/**
     * @return the $allowLocked
     */
    public function getAllowLocked ()
    {
        return $this->allowLocked;
    }

	/**
     * @param boolean $allowLocked
     */
    public function setAllowLocked ($allowLocked)
    {
        $this->allowLocked = $allowLocked;
    }


}