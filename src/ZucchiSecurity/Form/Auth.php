<?php
/**
 * ZucchiSecurity (http://zucchi.co.uk/)
 *
 * @link      http://github.com/zucchi/ for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZucchiSecurity\Form;

use Zend\Form\Form;
use Zend\Form\Fieldset;
use Zend\Form\Element\Collection;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator;
use Zend\Filter;

/**
 * Form for managing authentication settings
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package ZucchiSecurity
 * @subpackage Form
 * @category Authentication
 */
class Auth extends Form
{
    /**
     * 
     */
    public function __construct()
    {
        parent::__construct('domain');
        $this->setAttribute('method', 'post');
        
        $this->add(array(
            'name'  => 'csrf',
            'type' => 'Zend\Form\Element\Csrf',
        ));
        
        $this->add(array(
            'name'  => 'entity',
            'attributes' => array(
                'required' => 'true',
                'type' => 'text',
            ),
            'options' => array(
                'label' => _('Entity'),
                'bootstrap' => array( // options for bootstrap form
                    'help' => array(
                        'style' => 'inline',
                        'content' => _('This is the fully qualified name of the Entity to Authenticate against'),
                    ),
                )
            ),
        ));
        
        $this->add(array(
            'name'  => 'identity',
            'attributes' => array(
                'required' => 'true',
                'type' => 'text',
            ),
            'options' => array(
                'label' => _('Identity'),
                'bootstrap' => array( // options for bootstrap form
                    'help' => array(
                        'style' => 'inline',
                        'content' => _('This is the property of the Entity to use for the authentication Identity'),
                    ),
                )
            ),
        ));
        
        $this->add(array(
            'name'  => 'credential',
            'attributes' => array(
                'required' => 'true',
                'type' => 'text',
            ),
            'options' => array(
                'label' => _('Credential'),
                'bootstrap' => array( // options for bootstrap form
                    'help' => array(
                        'style' => 'inline',
                        'content' => _('This is the property of the Entity to use for the authentication Credential'),
                    ),
                )
            ),
        ));
        
        $actions = new Fieldset('actions');
        $actions->setAttribute('class', 'form-actions');
        
        $actions->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Save',
                'class' => 'btn btn-primary'
            ),
            'options' => array(
                'bootstrap' => array(
                    'style' => 'inline',
                ),
            ),
        ));
        
        $actions->add(array(
            'name' => 'reset',
            'attributes' => array(
                'type' => 'reset',
                'value' => 'reset',
                'class' => 'btn'
            ),
            'options' => array(
                'bootstrap' => array(
                    'style' => 'inline',
                ),
            ),
        ));
        $this->add($actions);
        
    }
}