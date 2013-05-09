<?php
/**
 * ZucchiSecurity (http://zucchi.co.uk/)
 *
 * @link      http://github.com/zucchi/ for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */

namespace ZucchiSecurity\Form;

use Zend\Form\Form;
use Zend\Form\Fieldset;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator;
use Zend\Filter;

/**
 * Base Form for logout
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package ZucchiSecurity
 * @subpackage Form
 * @category Authentication
 */
class Logout extends Form
{
    /**
     * construct base for security form
     */
    public function __construct()
    {
        parent::__construct('logout');
        $this->setAttribute('method', 'get');
    }
}