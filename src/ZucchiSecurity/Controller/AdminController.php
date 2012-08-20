<?php
/**
 * Zucchi (http://zucchi.co.uk/)
 *
 * @link      http://github.com/zucchi/ for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZucchiSecurity\Controller;

use ZucchiAdmin\Controller\AbstractAdminController;
use Zucchi\Debug\Debug;

class AdminController extends AbstractAdminController
{
    public function indexAction()
    {
        return $this->loadView('zucchi-security/admin/index');
    }
}