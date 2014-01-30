<?php
namespace Home\Service;

use Zend\Feed\Reader\Reader;

/**
 * Description of RssService
 *
 * @author adam
 */
class RssService {
    
    /**
     *
     * @var string[]
     */
    private $_urls = array();
    
    
    public function __construct(array $urls) {
        $this->_urls = $urls;
    }
    
    public function getRssFeeds() {
        $feeds = array();
        foreach ($this->_urls as $url) {
            $feed = $this->_readFeed($url);
            if ($feed !== NULL)
                $feeds[] = $feed;
        }
        return $feeds;
    }
    
    private function _readFeed($url) {
        $feed = null;
        try {
            $feed = Reader::import($url);
            $feedObj = new \stdClass();
            $feedObj->title = $feed->getTitle();
            $feedObj->posts = array();

            foreach ($feed as $item) {
                $feedObj->posts[] = array(
                    'title' => $item->getTitle(),
                    'link' => $item->getLink(),
                    'desc' => $item->getDescription()
                );
            }

            return $feedObj;
        } catch(\Exception $ex) {
            return NULL;
        }
        
        
    }
    
}

?>
