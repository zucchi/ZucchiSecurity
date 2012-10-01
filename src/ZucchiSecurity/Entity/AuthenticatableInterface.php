<?php
/**
 * ZucchiSecurity (http://zucchi.co.uk/)
 *
 * @link      http://github.com/zucchi/ for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */

namespace ZucchiSecurity\Entity;

/**
 * Interface for enforcing authentication features
 * 
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package ZucchiSecurity
 * @subpackage Entity
 * @category Authentication
 */
interface AuthenticatableInterface
{
     /**
     * function to encrypt the credential
     * 
     * @param string $credential
     * @return string
     */
    public function encryptCredential($credential = null);
    
    /**
     * verify credential match
     * 
     * @param string $credential
     * @return boolean
     */
    public function verifyCredential($credential);
    
    /**
     * function to identify if the authenticated entity is prevented from
     * authenticating
     * 
     * @return boolean
     */
    public function isLocked();
}