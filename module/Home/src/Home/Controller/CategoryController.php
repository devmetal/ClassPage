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
    
    /**
     *
     * @var \Zipper\ZipperService
     */
    private $_zipper;
    
    public function listAction() {
        $model = $this->_getCategoryModel();
        return new ViewModel(array(
            'categories' => $model->findAllOrderByNameAndItems()
        ));
    }
    
    public function viewAction() {
        $id = $this->params("id", NULL);
        if ($id === NULL)
            $this->redirect()->toRoute('category');
        
        $model = $this->_getCategoryModel();
        $category = $model->findById($id);
        
        return array(
            'category' => $category,
            'items' => $category->getItemsOrderByCreated()
        );
    }
    
    public function downloadAction() {
        $id = $this->params()
                ->fromRoute('id',NULL);
        
        if ($id === NULL)
            return $this->redirect ()
                >toRoute ('category');
        
        $model = $this->_getCategoryModel();
        $category = $model->findById($id);
        
        $items = $category->getItems();
        $name = $category->getName() . '.zip';
        
        $files = array();
        foreach ($items as $item) {
            $files[] = array(
                'path' => $item->getSrc(),
                'name' => $item->getTitle()
            );
        }
        
        $zipper = $this->_getZipperService();
        $zipper->setFiles($files);
        $zipFile = $zipper->createZip();
        
        $response = new \Zend\Http\Response\Stream();
        $response->setStream(fopen($zipFile,"r"));
        $response->setStatusCode(200);
        
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Disposition','attachment; filename="'.$name.'"');
        $headers->addHeaderLine('Content-Type','application/zip');
        
        $response->setHeaders($headers);
        
        return $response;
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
 
    /**
     * 
     * @return \Zipper\ZipperService
     */
    private function _getZipperService() {
        if ($this->_zipper === NULL) {
            $this->_zipper = $this->getServiceLocator()
                    ->get('ZipperService');
        }
        
        return $this->_zipper;
    }
}

?>
