<?php
namespace Home\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of IndexControllerFactory
 *
 * @author adam
 */
class IndexControllerFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $ctr = new IndexController();
        
        $ctr->setRssService(
            $serviceLocator->getServiceLocator()->get('rssFeeds')
        );
        
        return $ctr;
    }    
}

?>
