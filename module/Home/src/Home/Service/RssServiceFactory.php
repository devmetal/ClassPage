<?php
namespace Home\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of RssServiceFactory
 *
 * @author adam
 */
class RssServiceFactory implements FactoryInterface {
   
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $config = $serviceLocator->get('config');
        $feeds = $config['rss_feeds'];
        $rssService = new RssService($feeds);
        return $rssService;
    }
}

?>
