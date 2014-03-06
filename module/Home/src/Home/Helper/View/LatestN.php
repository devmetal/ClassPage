<?php
namespace Home\Helper\View;

use Zend\View\Helper\AbstractHelper;
use Home\Model\ItemModel;
/**
 * Description of LatestN
 *
 * @author adam
 */
class LatestN extends AbstractHelper {
    
    /**
     *
     * @var ItemModel
     */
    private $_model;
    
    private $_n;
    
    public function __construct(ItemModel $model, $n) {
        $this->_model = $model;
        $this->_n = $n;
    }
    
    public function __invoke($n = 0) {
        
        if ($n > 0)
            $this->_n = $n;
        
        $items = $this->_items();
        if (!empty($items)) {
            $html = "<div class='list-group'>";
            foreach ($items as $item) {
                
                $id = $item['id'];
                $uploader = $item['nick'];
                $desc = $item['descr'];
                $title = $item['title'];
                $url = $this->view->url('item',array(
                    'action' => 'download',
                    'id' => $id
                ));
                
                $itemHtml = "<a href='{$url}' class='list-group-item'>";
                $itemHtml .= "<h4 class='list-group-item-heading'>{$title}</h4>"; 
                $itemHtml .= "<p class='list-group-item-text'>
                    Feltöltő neve: {$uploader}<br>
                    Tétel leírás<br>{$desc}</p>";
                $itemHtml .= "</a>";
                
                $html .= $itemHtml;
            }
            
            return "</div>" . $html;
        } else {
            return "";
        }
    }
    
    private function _items() {
        return $this->_model->getLatestNItem($this->_n);
    }
    
}

?>
