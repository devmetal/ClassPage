<?php
return array(
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Users\Controller\Registrate' => 'Users\Controller\RegistrateController',
            'Users\Controller\Invitation' => 'Users\Controller\InvitationController',
            'Users\Controller\Profile'    => 'Users\Controller\ProfileController',
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'RegistrationFilter' => function($sm) {
                $em = $sm->get('ORMs\EntityManager');
                return new \Users\Form\Registrate\RegisterFilter($em);
            },
            'RegistrationForm' => function($sm) {
                $captcha = new Zend\Captcha\ReCaptcha();
                $captcha->setService($sm->get('captchaService'));
                $captcha->setMessage("Add meg a képen látható kódot!");
                
                $filter = $sm->get('RegistrationFilter');
                
                $form = new \Users\Form\Registrate\RegistrateForm($captcha);
                $form->setInputFilter($filter);
                
                return $form;
            },
            'InvitationFormFilter' => function($sm) {
                $em = $sm->get('ORMs\EntityManager');
                return new Users\Form\Invitation\InvitationFormFilter($em);
            },
            'InvitationForm' => function($sm) {
                $captcha = new Zend\Captcha\ReCaptcha();
                $captcha->setService($sm->get('captchaService'));
                
                $filter = $sm->get('InvitationFormFilter');
                
                $form = new Users\Form\Invitation\InvitationForm($captcha);
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
            ),
            'activation' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/activate[/:code]',
                    'constraints' => array(
                        'code' => '[A-Za-z0-9]*'
                    ),
                    'defaults' => array(
                        'controller' => 'Users\Controller\Registrate',
                        'action' => 'activate'
                    )
                )
            ),
            'invitation' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/invitation[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z0-9_-][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Users\Controller\Invitation',
                        'action' => 'create'
                    )
                )
            ),
            'profile' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/profile[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z0-9_-][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Users\Controller\Profile',
                        'action' => 'index'
                    )
                )
            )
        )
    ),

    
)
?>
