<?php
return array(
    'view_manager' => array(
        'doctype' => 'HTML5',
        'template_map' => array(
            'auth/layout' => __DIR__ . '/../view/layout/auth_layout.phtml'
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        )
    ),
    
    'router' => array(
        'routes' => array(
            'auth' => array(
                'type' => 'Zend\MVC\Router\Http\Literal',
                'options' => array(
                    'route' => '/auth',
                    'defaults' => array(
                        'controller' => 'Auth\Controller\Index',
                        'action' => 'auth'
                    )
                )
            ),
            'logout' => array(
                'type' => 'Zend\MVC\Router\Http\Literal',
                'options' => array(
                    'route' => '/logout',
                    'defaults' => array(
                        'controller' => 'Auth\Controller\Index',
                        'action' => 'logout'
                    )
                )
            )
        )
    ),
        
    'controllers' => array(
        'invokables' => array(
            'Auth\Controller\Index' => 'Auth\Controller\IndexController'
        )
    ),
    
    'service_manager' => array(
        'factories' => array(
            'Auth\Service\Auth' => function($sm) {
                $srvc = new Auth\Service\AuthService($sm);
                $srvc->setStorage(new Auth\Service\AuthStorage());
                return $srvc;
            }
        )
    )
)
?>
