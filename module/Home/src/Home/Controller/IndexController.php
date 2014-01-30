<?php
namespace Home\Controller;

use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

/**
 * Description of IndexController
 *
 * @author adam
 */
class IndexController extends BaseController {
    
    
    /**
     *
     * @var \Home\Service\RssService
     */
    private $rssService;
    
    public function indexAction() {
        return new ViewModel();
    }
    
    public function newsAction() {
        if (!$this->getRequest()->isXmlHttpRequest())
            return array();
        
        return new JsonModel(array(
            'feeds' => $this->rssService->getRssFeeds()
        ));
    }
    
    public function setRssService($service) {
        $this->rssService = $service;
    }
    
}

?>
