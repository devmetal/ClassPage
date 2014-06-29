<?php
namespace Home\Helper\View;

use Zend\View\Helper\AbstractHelper;
use Home\Model\CategoryModel;

/**
 * Description of CategoryListPanel
 *
 * @author adam
 */
class CategoryListPanel extends AbstractHelper {
    
    /**
     *
     * @var CategoryModel
     */
    private $_model;
    
    public function __construct(CategoryModel $model) {
        $this->_model = $model;
    }
    
    public function __invoke() {
        return $this;
    }
    
    public function getTopNItem($cid, $n) {
        return $this->_model
                ->findCategoryTopNItem($cid, $n);
    }
    
}

?>
