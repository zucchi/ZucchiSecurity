<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Zend_Authentication
 */

namespace ZucchiSecurity\Authentication;

/**
 * @category   Zend
 * @package    Zend_Authentication
 */
class Result
{
    
    /**
     * General Failure
     */
    const NOT_ATTEMPTED                  =  0;
    
    /**
     * General Failure
     */
    const FAILURE                        =  -1;

    /**
     * Failure due to identity not being found.
     */
    const FAILURE_IDENTITY_NOT_FOUND     = -2;

    /**
     * Failure due to identity being ambiguous.
     */
    const FAILURE_IDENTITY_AMBIGUOUS     = -3;

    /**
     * Failure due to invalid credential being supplied.
     */
    const FAILURE_CREDENTIAL_INVALID     = -4;

    /**
     * Failure due to entity not meeting authentication requirements.
     */
    const FAILURE_NOT_AUTHENTICATABLE    = -5;
    
    /**
     * Failure to autheticate due to entity being kocked
     */
    const FAILURE_PROHIBITED             = -6;
    
    /**
     *  Failure due to uncategorized reasons.
     */
    const FAILURE_UNCATEGORIZED          = -7;

    /**
     * Authentication success.
     */
    const SUCCESS                        =  1;
    
    /**
     * Authentication result code
     *
     * @var int
     */
    public $code;

    /**
     * The identity used in the authentication attempt
     *
     * @var mixed
     */
    public $identity;

    /**
     * The authenticated Entity 
     */
    public $entity;
    
    /**
     * An array of string reasons why the authentication attempt was unsuccessful
     *
     * If authentication was successful, this should be an empty array.
     *
     * @var array
     */
    protected $messages = array(
        -7 => 'Authentication for %s failed for unknown reasons',
        -6 => 'Authentication for %s has been prohibited',
        -5 => 'It is not possible to Authenticate "%s" ',
        -4 => 'Invalid credential provided for %s',
        -3 => 'Identity "%s" was ambiguous',
        -2 => 'Identity "%s" not found',
        -1 => 'Authentication for "%s" failed',
        0 => 'Authentication not attempted',
        1 => 'Authentication Successful',
    );

    /**
     * Sets the result code, identity, and failure messages
     *
     * @param  int     $code
     * @param  mixed   $identity
     * @param  array   $messages
     */
    public function __construct($code = self::NOT_ATTEMPTED, $identity = '')
    {
        $code = (int) $code;

        $this->code     = $code;
        $this->identity = $identity;
    }

    /**
     * Returns whether the result represents a successful authentication attempt
     *
     * @return boolean
     */
    public function isValid()
    {
        if ($this->code >= 0 && $this->entity) {
            return true;
        } 
        return false;
    }
    
    public function getMessage()
    {
        return sprintf($this->messages[$this->code], $this->identity);
    }
    
}
