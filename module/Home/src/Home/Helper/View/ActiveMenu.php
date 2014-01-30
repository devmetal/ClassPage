<?php
namespace Home\Helper\View;

use Zend\View\Helper\AbstractHelper;

use Zend\ServiceManager\ServiceLocatorInterface;
/**
 * Description of ActiveMenu
 *
 * @author adam
 */
class ActiveMenu extends AbstractHelper {
    
    /**
     *
     * @var ServiceLocatorInterface
     */
    private $_serviceLocator;
    
    public function __construct(ServiceLocatorInterface $locator) {
        $this->_serviceLocator = $locator->getServiceLocator();
    }
    
    public function __invoke($route) {
        $router = $this->_serviceLocator->get('router');
        $request = $this->_serviceLocator->get('request');
        $match = $router->match($request);
        $name = $match->getMatchedRouteName();
        
        if ($name === $route) {
            return TRUE;
        }
        
        return FALSE;
    }
    
}

?>
