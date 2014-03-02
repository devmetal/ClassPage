<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Users\Controller\Registrate' => 'Users\Controller\RegistrateController'
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'RegistrationFilter' => function($sm) {
                return new \Users\Form\Registrate\RegisterFilter();
            },
            'RegistrationForm' => function($sm) {
                $filter = $sm->get('RegistrationFilter');
                $form = new \Users\Form\Registrate\RegistrateForm();
                $form->setInputFilter($filter);
                
                return $form;
            },
            'UserModel' => function($sm) {
                $entityManager = $sm->get('ORMs\EntityManager');
                return new \Users\Model\UserModel($entityManager, $sm);
            },
            'EmailHelper' => function($sm) {
                return new Users\Email\EmailHelper($sm);
            },
            'MailTransport' => 'Users\Email\EmailTransportFactory'
        )
    ),
    'router' => array(
        'routes' => array(
            'registration' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/registrate[/[:action]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z0-9_-][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Users\Controller\Registrate',
                        'action' => 'index'
                    )
                )
            )
        )
    ),

    'mail_config' => array(
        'development' => array(
            'from' => 'metaladam91@gmail.com',
            'type' => 'smtp',
            'options' => array(
                'name' => 'metaladam91',
                'host' => 'smtp.gmail.com',
                'port' => '465',
                'connection_class' => 'login',
                'connection_config' => array(
                    'ssl' => 'ssl',
                    'username' => 'metaladam91@gmail.com',
                    'password' => 'phpakiralynemapascal'
                )
            )
        ),
        'production' => array(
            'from' => 'info@elitosztaly.eu',
            'type' => 'sendmail',
            'options' => array()
        )
    )
)
?>
