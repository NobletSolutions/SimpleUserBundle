# SimpleUserBundle
Basic pre-configured user admin and auth bundle for NSAdminBundle

##Installation

Require SimpleUserBundle
`composer require ns/simple-user-bundle`

add it to your project bundles.php
`NS\SimpleUserBundle\NSSimpleUserBundle::class => ['all' => true],`

and require the routing config in your project routes.yaml file. **NOTE**: The SimpleUserBundle routes must be included *before* the AdminBundle routes.

```yaml
ns_simple_user:
    resource: "@NSSimpleUserBundle/Resources/config/routing.yml"
    prefix:  /

ns_admin:
    resource: "@NSAdminBundle/Resources/config/routing.yml"
    prefix:  /admin
```

Update your project security.yaml file:
```yaml
security:
    encoders:
        NS\SimpleUserBundle\Security\SecurityUser:
            algorithm: bcrypt
        NS\SimpleUserBundle\Entity\User\User:
            algorithm: bcrypt
    providers:
        app:
            id: NS\SimpleUserBundle\Security\SecurityUserProvider
    firewalls:
        main:
            form_login:
                login_path: login
                check_path: login_check
            logout:
                path: logout
                target: login
    access_control:
        - { path: ^/logout$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
```

The user admin list can be accessed with the `admin_user_list` route.
