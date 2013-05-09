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
 * Base form for login
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package ZucchiSecurity
 * @subpackage Form
 * @category Authentication
 */
class Login extends Form
{
    /**
     * construct base for security form
     */
    public function __construct()
    {
        parent::__construct('login');
        $this->setAttribute('method', 'post');
        
//         $this->add(array(
//             'name'  => 'csrf',
//             'type' => 'Zend\Form\Element\Csrf',
//             'priority' => 999999,
//         ));
        
        $actions = new Fieldset('actions');
        $actions->setAttribute('class', 'form-actions');
        
        $this->add($actions, array('priority' => -9999));
        
    }
}