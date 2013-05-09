<?php
/**
 * ZucchiSecurity (http://zucchi.co.uk/)
 *
 * @link      http://github.com/zucchi/ZucchiSecurity for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */
namespace ZucchiSecurity\View\Helper;

use Zend\EventManager\EventManagerInterface;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Model\ViewModel;
use Zend\Form\Form;
use ZucchiSecurity\Service\ServiceInterface AS AuthService;

/**
 * view helper to output login form
 * 
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package ZucchiSecurity 
 * @subpackage View/Helper
 */
class LoginForm extends AbstractHelper
{
    /**
     * Authentication service
     * @var AuthService
     */
    protected $service;
    
    /**
     * construct the helper
     * @param AuthService $service
     */
    public function __construct(AuthService $service)
    {
        $this->setService($service);
    }
    
    /**
     * render the login form
     * @param bool $render
     * @return string|\Zend\View\Model\ViewModel
     */
    public function __invoke($render = true)
    {
        $entity = $this->getView()->identity();
        if ($entity) {
            $viewModel = new ViewModel(array(
                'form' => $this->service->getLogoutForm(),
            ));
            
            $viewModel->setTemplate('zucchi-security/widget/logout-form');
        } else {
            $viewModel = new ViewModel(array(
                'form' => $this->service->getLoginForm(),
            ));
            
            $viewModel->setTemplate('zucchi-security/widget/login-form');
        }
        if ($render) {
            return $this->getView()->render($viewModel);
        } else {
            return $viewModel;
        }
    }
    
    /**
     * Set the Auth Service to use
     * @param AuthService $service
     * @return \ZucchiSecurity\View\Helper\LoginForm
     */
    public function setService(AuthService $service)
    {
        $this->service = $service;
        return $this;
    }
    
    /**
     * get the current auth service being used
     * @return \ZucchiSecurity\Authentication\ServiceInterface
     */
    public function getService()
    {
        return $this->service;
    }
    
    
}