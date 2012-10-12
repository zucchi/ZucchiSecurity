<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'zucchi-security-frontend' => 'ZucchiSecurity\Controller\FrontendController',
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'Identity' => 'ZucchiSecurity\Controller\Plugin\Identity',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'zucchisecurity.auth' => 'ZucchiSecurity\Authentication\Service',
            'zucchisecurity.listener' => 'ZucchiSecurity\Event\SecurityListener',
        ),
        'factories' => array(
            'zucchisecurity.perm.options' => function ($sm) {
                $config = $sm->get('config');
                $options = new \ZucchiSecurity\Permissions\Options\PermissionsOptions();
                if (isset($config['ZucchiSecurity']['permissions'])) {
                    $options->setFromArray($config['ZucchiSecurity']['permissions']);
                }
                return $options;
            },
            'zucchisecurity.perm' => function ($sm) {
                $service = new ZucchiSecurity\Permissions\Service();
                $service->setOptions($sm->get('zucchisecurity.perm.options'));
                return $service;
            },
            'zucchisecurity.auth.local.options' => function ($sm) {
                $config = $sm->get('config');
                $options = new \ZucchiSecurity\Authentication\Plugin\Options\LocalOptions();
                if (isset($config['ZucchiSecurity']['auth_plugins']['local'])) {
                    $options->setFromArray($config['ZucchiSecurity']['auth_plugins']['local']);
                }
                return $options;
            },
            'zucchisecurity.auth.local' => function ($sm) {
                $plugin = new ZucchiSecurity\Authentication\Plugin\Local();
                $plugin->setOptions($sm->get('zucchisecurity.auth.local.options'));
                return $plugin;
            },
            'zucchisecurity.auth.captcha.options' => function ($sm) {
                $config = $sm->get('config');
                $options = new \ZucchiSecurity\Authentication\Plugin\Options\CaptchaOptions();
                if (isset($config['ZucchiSecurity']['auth_plugins']['captcha'])) {
                    $options->setFromArray($config['ZucchiSecurity']['auth_plugins']['captcha']);
                }
                return $options;
            },
            'zucchisecurity.view.strategy.unauthorised' => function ($sm) {
                $config = $sm->get('config');
                $strategy = new \ZucchiSecurity\View\Strategy\Unauthorised();
                return $strategy;
            },
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'Identity' => 'ZucchiSecurity\View\Helper\Identity',
            'Can' => 'ZucchiSecurity\View\Helper\Can',
        ),
        'factories' => array(
            'loginform' => function($sm) {
                $sl = $sm->getServiceLocator();
                $helper = new ZucchiSecurity\View\Helper\LoginForm($sl->get('zucchisecurity.auth'));
                return $helper;
            }
        ),
    ),
    // default route
    'router' => array(
        'routes' => array(
            'ZucchiSecurity' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route' => '/security[/:action]',
                    'defaults' => array(
                        'controller' => 'zucchi-security-frontend',
                    )
                ),
            ),
        ),
    ),
    'translator' => array(
        'locale' => 'en_GB',
        'translation_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'ZucchiSecurity' => __DIR__ . '/../view',
        ),
    ),
    'ZucchiSecurity' => array(
        'permissions' => array(
            'map' => array(
                'defaults' => array(
                    'post' => 'create',
                    'get' => 'read',
                    'put' => 'update',
                    'delete' => 'delete',
                ),
            ),
            'roles' => array(
                'guest' => array(
                    'label' => 'Guest Role (default role, grants public access)',
                ),
            ),
            'resources' => array(
                'route' =>array(
                    'ZucchiAdmin' => array(
                        'children' => array('ZucchiSecurity'),
                    ),
                ),
            ),
            'rules' => array(),
        ),
    ),
);
