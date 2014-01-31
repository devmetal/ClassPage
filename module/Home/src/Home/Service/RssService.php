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
    
    private $_maxPerFeed;
    
    public function __construct(array $urls, $max) {
        $this->_urls = $urls;
        $this->_maxPerFeed = $max;
    }
    
    public function getRssFeeds() {
        $feeds = array();
        foreach ($this->_urls as $name => $url) {
            $feed = $this->_readFeed($url);
            if ($feed !== NULL) {
                $this->_orderPosts($feed);
                $feeds[$name] = $feed;
            }
        }
        
        return $feeds;
    }
    
    private function _readFeed($url) {
        $feed = null;
        $posts = 0;
        try {
            $feed = Reader::import($url);
            $feedObj = new \stdClass();
            $feedObj->title = $feed->getTitle();
            $feedObj->posts = array();

            foreach ($feed as $item) {
                if (++$posts > $this->_maxPerFeed)
                    break;
                
                $feedObj->posts[] = array(
                    'title' => $item->getTitle(),
                    'link' => $item->getLink(),
                    'desc' => $item->getDescription(),
                    'date' => $item->getDateCreated()->format('Y-m-d')
                );
            }

            return $feedObj;
        } catch(\Exception $ex) {
            return NULL;
        }
    }
    
    private function _orderPosts($feed) {
        usort($feed->posts, function($a,$b){
            $adate = strtotime($a['date']);
            $bdate = strtotime($b['date']);
            
            if ($adate == $bdate) 
                return 0;
            else if ($adate > $bdate)
                return -1;
            else 
                return 1;
        });
    }
    
}

?>
