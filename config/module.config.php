<?php
return array(
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
                'label' => 'Security',
                'route' => 'ZucchiAdmin/ZucchiSecurity',
                'pages' => array(
                    'authentication' => array(
                        'label' => 'Authentication',
                        'route' => 'ZucchiAdmin/ZucchiSecurity/Auth',
                        'controller' => 'auth',
                    ),
                    'access' => array(
                        'label' => 'Access Control',
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
    'view_manager' => array(
        'template_path_stack' => array(
            'ZucchiSecurity' => __DIR__ . '/../view',
        ),
    ),
);
