ZucchiSecurity
==============

Module to provide and allow management of security features for Zucchi ZF2 Modules 

Installation
------------

From the root of your ZF2 Skeleton Application run

    ./composer.phar require zucchi/security
    
Authorisation
-------------

This module comes with an authorisation layer built on top of Zend\Permissions\Acl.

The module will build a custom ACL based on the roles available to the current 
user. If no user data is available it will default to build an ACL for the 
"guest" role.

On each request it will test the ACL to see if the curent user has access to the 
"route" specified.

If the current user is not authorised it will trigger the unauthorised view 
strategy and display the login/logout forms.

Helpers
-------

As part of the module you have a both a view and controller helper. "$this->can(privilege, $resource)"
proxies to the permissions service method "can($privilege, $resource)"

@example : $this->can('edit', 'module:ZucchiUser');

Tests the ACL to see if any of the roles assigned to the current user allows the edit permission against the module ZucchiUser.

Configuration
-------------

When adding a module to your project it will need to be registered with the 
ZucchiSecurity Module.

You can do this by adding the following (as a bare minimum) to your configuration.

<pre>
'ZucchiSecurity' => array(
        'permissions' => array(
            'resources' => array(
                'route' =>array(
                    'MyModuleRouteKey', // the route key used for your module
                ),
            ),
            'rules' => array(
                array(
                    'action' => 'allow'
                    'role' => 'guest',
                    'resource' => 'route:MyModuleRouteKey',
                    'privileges' => array('view'),
                ),
            )
        ),
    ),
</pre>

Full details of the different options for configuration can be found in 
./config/zucchisecurity.access.local.php.dist

Authentication
--------------

The module comes with a built in Authentication layer that will be triggered 
when the current user is not authorised to view the current route.

This authentication depends on the ZucchiUser Module for user management

*Extending*

The authentication process can be easily extended by attaching to the following 
'ZucchiSecurity' events

<pre>
    const EVENT_LOGIN_FORM_BUILD   = 'zucchisecurity.form.login.build';
    const EVENT_LOGOUT_FORM_BUILD  = 'zucchisecurity.form.logout.build';
    
    const EVENT_AUTHENTICATE       = 'zucchisecurity.authenticate';
    const EVENT_AUTH_POST          = 'zucchisecurity.authenticate.post';
</pre>

_zucchisecurity.form.???.build_

These events allow you to extend the forms used in logging in and logging out

_zucchisecurity.authenticate_

This event allows you to add triggers for your own authentication logic.

It is important that when authenticating your logic must return an instance of 
ZucchiSecurity\Authentication\Result or compatible interface.

N.B. Dont forget to stop propagation of the event when you sucessfully authenticate. 

_zucchisecurity.authenticate.post_

This event allows you to hook into the result of your authentication. 

A good example of this can be found in the ZucchiUser module which hooks into 
this event and creates a log of the successful

Roadmap
-------

*   Implement Registration features
