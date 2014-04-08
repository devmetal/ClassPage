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
            'feedback' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/feedback',
                    'defaults' => array(
                        'controller' => 'Home\Controller\Feedback',
                        'action' => 'send'
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
            'item' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/item[/[:action][/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z0-9_-][a-zA-Z0-9_-]*',
                        'id' => '[0-9][0-9]*'
                    ),
                    'defaults' => array(
                        'controller' => 'Home\Controller\Item',
                        'action' => 'index'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'redirecting' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:redirect]'
                        ),
                    )
                )
            ),
            'category' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/category[/[:action][/:id]]',
                    'constraints' => array(
                        'action' => '[a-zA-Z0-9_-][a-zA-Z0-9_-]*',
                        'id' => '[0-9][0-9]*'
                    ),
                    'defaults' => array(
                        'controller' => 'Home\Controller\Category',
                        'action' => 'list'
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
            'Home\Controller\Index' => function($sm) {
                $rss = $sm->getServiceLocator()->get('rssFeeds');
                $c = new \Home\Controller\IndexController();
                $c->setRssService($rss);
                
                return $c;
            }
        ),
        'invokables' => array(
            'Home\Controller\Item' => 
                'Home\Controller\ItemController',
            
            'Home\Controller\Profile' => 
                'Home\Controller\ProfileController',
            
            'Home\Controller\Category' =>
                'Home\Controller\CategoryController',
            
            'Home\Controller\Feedback' => 
                'Home\Controller\FeedbackController'
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
            },
            'FeedbackFormFilter' => function($sm) {
                return new Home\Form\FeedbackFormFilter();
            },
            'FeedbackForm' => function($sm) {
                $config = $sm->get('config')['feedback'];
                
                $filter = $sm->get('FeedbackFormFilter');
                
                $form = new \Home\Form\FeedbackForm($config);
                $form->setInputFilter($filter);
                
                return $form;
            }
        )
    ),
                
    'view_helpers' => array(
        'factories' => array(
            'logined' => function($sm) {
                $as = $sm->getServiceLocator()->get('Auth\Service\Auth');
                return new \Home\Helper\View\Logined($as);
            },
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
            },
            'categoryListPanel' => function($sm) {
                return new \Home\Helper\View\CategoryListPanel(
                        $sm->getServiceLocator()->get('Model\Category'));
            },
            'latestNItem' => function($sm) {
                return new \Home\Helper\View\LatestN(
                        $sm->getServiceLocator()->get('Model\Item'), 10);
            },
            'itemEditButton' => function($sm) {
                $as = $sm->getServiceLocator()->get('Auth\Service\Auth');
                return new \Home\Helper\View\ItemEditButton($as);
            },
            'itemDeleteButton' => function($sm) {
                $as = $sm->getServiceLocator()->get('Auth\Service\Auth');
                return new \Home\Helper\View\ItemDeleteButton($as);
            },
            'feedbackForm' => function($sm) {
                return new \Home\Helper\View\FeedbackForm($sm->getServiceLocator());
            }
        )
    ),
    
    'rss_max_post_per_feed' => 5,
    'rss_feeds' => array(
        'oktatas.hu' => 'http://www.oktatas.hu/rss',
        'eduline.hu' =>  'http://eduline.hu/rss',
        'felvi.hu' => 'http://www.felvi.hu/felveteli/rss',
    ),
                
    'zipper' => array(
        'temp_path' => './data/temps',
        'archives_path' => './data/archives'
    ),
                
    'feedback' => array(
        'to' => 'feedback@elitosztaly.eu',
        'categories' => array(
            'error-report' => 'Hibajelentés',
            'feature-request' => 'Hiányzó funkció',
            'grammar' => 'Nyelvtani hiba',
            'confort' => 'Kényelmetlenség',
            'design' => 'Kinézet',
            'general' => 'Általános visszajelzés',
            'other' => 'Egyéb téma'
        ),
    )
);
?>
