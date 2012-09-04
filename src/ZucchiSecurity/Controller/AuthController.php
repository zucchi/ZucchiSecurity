<?php
/**
 * ZucchiSecurity (http://zucchi.co.uk/)
 *
 * @link      http://github.com/zucchi/ for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */

namespace ZucchiSecurity\Controller;

use ZucchiAdmin\Controller\AbstractAdminController;
use ZucchiSecurity\Form\Auth;
use Zucchi\Debug\Debug;

/**
 * Controller to manage Authentication settings
 * 
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package ZucchiSecurity
 * @subpackage Controller
 * @category Authentication
 */
class AuthController extends AbstractAdminController
{
    /**
     * (non-PHPdoc)
     * @see \Zucchi\Controller\AbstractController::getList()
     */
    public function settingsAction()
    {
        $sm = $this->getServiceLocator();
        $config = $sm->get('config');

        $path = $config['ZucchiSecurity']['config_paths']['auth'];
        
        $form = new Auth();
        
        return array(
            'form' => $form,
        );
    }
    
    public function create($data)
    {
        exit('create');
    }
    
    public function update($id, $data)
    {
        exit('update');
    }
}