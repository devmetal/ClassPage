<?php
return array(
    'doctrine' => array(
        'driver' => array(
            'application_entities' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/ORMs/Entity')
            ),

            'orm_default' => array(
                'drivers' => array(
                    'ORMs\Entity' => 'application_entities'
                )
            )
        ),
        'authentication' => array(
            'orm_default' => array(
                'objectManager' => 'Doctrine\ORM\EntityManager',
                'identityClass' => 'ORMs\Entity\User',
                'identityProperty' => 'email',
                'credentialProperty' => 'pass',
                'credentialCallable' => 'ORMs\Entity\User::checkPass'
            )
        )
    )
);
?>