<?php
return array(
    'ZucchiSecurity' => array(
        'config_paths' => array(
            'auth' => __DIR__ . '/auth.config.php',
            'access' => __DIR__ . '/access.config.php',
        ),
        'auth' => include 'auth.config.php',
        'access' => include 'access.config.php',
    ),
    'controllers' => array(
        'invokables' => array(
            'zucchi-security-admin' => 'ZucchiSecurity\Controller\AdminController',
            'zucchi-security-auth' => 'ZucchiSecurity\Controller\AuthController',
            'zucchi-security-access' => 'ZucchiSecurity\Controller\AccessController',
        ),
    ),
    'navigation' => array(
        'ZucchiAdmin' => array(
            'security' => array(
                'label' => _('Security'),
                'route' => 'ZucchiAdmin/ZucchiSecurity',
                'pages' => array(
                    'authentication' => array(
                        'label' => _('Authentication'),
                        'route' => 'ZucchiAdmin/ZucchiSecurity/Auth',
                        'controller' => 'auth',
                    ),
                    'access' => array(
                        'label' => _('Access Control'),
                        'route' => 'ZucchiAdmin/ZucchiSecurity/Access',
                        'controller' => 'access',
                    ),
                )
            ),
        )
    ),
    // default route 
    'router' => array(
        'routes' => array(
            'ZucchiAdmin' => array(
                'child_routes' => array(
                    'ZucchiSecurity' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route' => '/security',
                            'defaults' => array(
                                'controller' => 'zucchi-security-admin',
                            )
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'Auth' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route' => '/auth[/:action]',
                                    'defaults' => array(
                                        'controller' => 'zucchi-security-auth',
                                        'action' => 'settings',
                                    )
                                ),
                                'may_terminate' => true,
                            ),
                            'Access' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route' => '/access[/:action]',
                                    'defaults' => array(
                                        'controller' => 'zucchi-security-access',
                                        'action' => 'settings',
                                    )
                                ),
                                'may_terminate' => true,
                            ),
                        )
                    ),
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
);
