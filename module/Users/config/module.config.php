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
                $entityManager = $sm->getServiceLocator()
                        ->get('ORMs\EntityManager');
                return new \Users\Model\UserModel($entityManager, $sm);
            }
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
    )
)
?>
