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
            $html = "<div class='item-list'>";
            foreach ($items as $item) {
                
                $id = $item['id'];
                $uploader = $item['nick'];
                $desc = $item['descr'];
                $title = $item['title'];
                $category = $item['category'];
                $created = $item['created']->format('Y-m-d');
                $url = $this->view->url('item',array(
                    'action' => 'view',
                    'id' => $id
                ));
                $durl = $this->view->url('item',array(
                    'action' => 'download',
                    'id' => $id
                ));
                
                $itemHtml = "<div class='panel panel-default item-panel'>";
                $itemHtml .= "<div class='panel-heading'>
                    <h3 class='panel-title'><a href='{$url}'>{$title} - {$category}</a></h3>
                        </div>"; 
                $itemHtml .= "<div class='panel-body'>
                        {$desc}<hr><a class='btn btn-default' href='{$url}'>Adatlap</a>
                            <a class='btn btn-default' href='{$durl}'>Letöltés</a></div>";
                $itemHtml .= "<div class='panel-footer'>{$uploader} <em>({$created})</em></div>";
                $itemHtml .= "</div>";
                
                $html .= $itemHtml;
            }
            
            return $html . "</div>";
        } else {
            return "";
        }
    }
    
    private function _items() {
        return $this->_model->getLatestNItem($this->_n);
    }
    
}

?>
