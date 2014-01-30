<?php
return array(
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Home\Controller\Index',
                        'action' => 'index'
                    )
                )
            ),
            'news' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/news',
                    'defaults' => array(
                        'controller' => 'Home\Controller\Index',
                        'action' => 'news'
                    )
                )
            ),
            'items' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/items[/[:action][/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z0-9_-][a-zA-Z0-9_-]*',
                        'id' => '[0-9][0-9]*'
                    ),
                    'defaults' => array(
                        'controller' => 'Home\Controller\Item',
                        'action' => 'index'
                    )
                )
            ),
            'profile' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/profile[/[:action][/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z0-9_-][a-zA-Z0-9_-]*',
                        'id' => '[0-9][0-9]*'
                    ),
                    'defaults' => array(
                        'controller' => 'Home\Controller\Profile',
                        'action' => 'index'
                    )
                )
            )
        )
    ),
    
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
    
    'controllers' => array(
        'factories' => array(
            'Home\Controller\Index' => 
                'Home\Controller\IndexControllerFactory'
        ),
        'invokables' => array(
            'Home\Controller\Item' => 
                'Home\Controller\ItemController',
            
            'Home\Controller\Profile' => 
                'Home\Controller\ProfileController'
        )
    ),
    
    'service_manager' => array(
        'invokables' => array(
            'greetingService' => 'Home\Service\GreetingService'
        ),
        'factories' => array(
            'rssFeeds' => 'Home\Service\RssServiceFactory',
            'ORMs\EntityManager' => function($sm) {
                return $sm->get('doctrine.entitymanager.orm_default');
            },
            'Model\Item' => function($sm) {
                return new Home\Model\ItemModel($sm->get('ORMs\EntityManager'), $sm);
            },
            'Model\Category' => function($sm) {
                return new Home\Model\CategoryModel($sm->get('ORMs\EntityManager'), $sm);
            }
        )
    ),
                
    'view_helpers' => array(
        'factories' => array(
            'userBar' => function($sm) {
                return new Home\Helper\View\UserBar(
                    $sm->getServiceLocator()->get('Auth\Service\Auth')
                );
            },
            'isActiveRoute' => function($sm) {
                return new Home\Helper\View\ActiveMenu($sm);
            },
            'categoryMenu' => function($sm) {
                return new Home\Helper\View\CategoryMenu(
                        $sm->getServiceLocator()->get('Model\Category'));
            }
        )
    ),
    
    'rss_feeds' => array(
        'http://eduline.hu/rss',
        'http://www.oktatas.hu/rss',
        'http://www.felvi.hu/felveteli/rss',
    )
);
?>
