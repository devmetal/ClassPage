<?php
namespace Home\Helper\View;

use Zend\View\Helper\AbstractHelper;
use Home\Model\CategoryModel;

/**
 * Description of CategoryMenu
 *
 * @author adam
 */
class CategoryMenu extends AbstractHelper implements \Iterator {
    
    /**
     *
     * @var CategoryModel
     */
    private $_categoryModel;
    
    private $_categories;
    
    private $_i = 0;
    
    private $_n = 0;

    public function __construct(CategoryModel $model) {
        $this->_categoryModel = $model;
    }
    
    public function __invoke() {
        $this->_categories = 
                $this->_categoryModel->findAllOrderByNameAndItems();
        $this->_n = count($this->_categories);
        
        return $this;
    }

    public function current() {
        $cat = $this->_categories[$this->_i];
        $name = $cat['name'];
        $id = $cat['id'];
        $num = $cat['items_count'];
        
        return "<li><a href='/items/category/$id'>$name ($num)</a></li>";
    }

    public function key() {
        return $this->_i;
    }

    public function next() {
        ++$this->_i;
    }

    public function rewind() {
        $this->_i = 0;
    }

    public function valid() {
        return ($this->_i < $this->_n);
    }
    
}

?>
