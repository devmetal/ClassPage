<?php
namespace Home\Controller;

use Home\Model\CategoryModel;
use Zend\View\Model\ViewModel;
/**
 * Description of CategoryController
 *
 * @author adam
 */
class CategoryController extends BaseController {
    
    /**
     *
     * @var \Home\Model\CategoryModel
     */
    private $_categoryModel;
    
    public function listAction() {
        $model = $this->_getCategoryModel();
        return new ViewModel(array(
            'categories' => $model->findAllOrderByNameAndItems()
        ));
    }
    
    public function viewAction() {
        $id = $this->params("id", NULL);
        if ($id === NULL)
            $this->redirect()->toRoute('items');
        
        $model = $this->_getCategoryModel();
        $category = $model->findById($id);
        
        return array(
            'category' => $category
        );
    }
    
    /**
     * 
     * @return CategoryModel
     */
    private function _getCategoryModel() {
        if ($this->_categoryModel === NULL) {
            $this->_categoryModel = $this->getServiceLocator ()
                ->get('Model\Category');
        }
        
        return $this->_categoryModel;
    }
    
}

?>
