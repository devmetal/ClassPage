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
        $max = $config['rss_max_post_per_feed'];
        $rssService = new RssService($feeds,$max);
        return $rssService;
    }
}

?>
