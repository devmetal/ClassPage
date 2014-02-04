<?php
namespace Home\Controller;

use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

use Home\Model\ItemModel;
use Home\Model\CategoryModel;
use Home\Form\Upload;
use Home\Form\ItemForm;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
/**
 * Description of ItemController
 *
 * @author adam
 */
class ItemController extends BaseController {
    
    /**
     *
     * @var ItemModel
     */
    private $_itemModel;
    
    /**
     *
     * @var CategoryModel
     */
    private $_categoryModel;
    
    public function indexAction() {
        $model = $this->_getCategoryModel();
        $categories = $model->findAllOrderByNameAndItems();
        
        return array(
            'categories' => $categories
        );
    }
    
    public function categoryAction() {
        $id = $this->params("id", NULL);
        if ($id === NULL)
            $this->redirect()->toRoute('items');
        
        $model = $this->_getCategoryModel();
        $category = $model->findById($id);
        
        return array(
            'category' => $category
        );
    }
    
    public function viewAction() {
        $id = $this->params("id", NULL);
        if ($id === NULL)
            $this->redirect()->toRoute('home');
        
        $model = $this->_getItemModel();
        $item = $model->findItemById($id);
        
        return array(
            'item' => $item
        );
    }
    
    public function downloadAction() {
        $id = $this->params("id", NULL);
        if ($id === NULL)
            $this->redirect()->toRoute('home');
        
        $model = $this->_getItemModel();
        $item = $model->findItemById($id);
        
        $file = $item->getSrc();
        
        $info = new \SplFileInfo($file);
        $name = $info->getBasename();
        
        $response = new \Zend\Http\Response\Stream();
        $response->setStream(fopen($file,"r"));
        $response->setStatusCode(200);
        
        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Disposition','attachment; filename="'.$name.'"');
        
        $response->setHeaders($headers);
        
        return $response;
    }
    
    public function uploadAction() {
        $form = new Upload();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $files = $request->getFiles();
            $form->setData($files);
            if ($form->isValid()) {
                $datas = $form->getData();
                $model = $this->_getItemModel();
                $id = $model->createNewEmptyItem($datas['document']);
                
                return $this->redirect()
                        ->toRoute('item', array(
                            'action' => 'edit',
                            'id' => $id
                        ));
            }
        }
        return array(
            'form' => $form
        );
    }
    
    public function ajaxUploadAction() {
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            if ($request->isPost()) {
                $files = $request->getFiles();
                $form = new Upload();
                $form->setData($files);
                if ($form->isValid()) {
                    $datas = $form->getData();
                    
                    $model = $this->_getItemModel();
                    $id = $model->createNewEmptyItem($datas['document']);
                    
                    return new JsonModel(array(
                        'result' => 'success',
                        'id' => $id
                    ));
                } else {
                    return new JsonModel(array(
                        'result' => 'invalid',
                        'messages' => $form->getMessages()
                    ));
                }
            }
        }
        
        return new JsonModel(array());
    }
    
    public function editAction() {
        $item = $this->_getItemModel()
                ->findItemById($this->params("id"));
        
        if (!$item) {
            return $this->redirect()->toRoute("items");
        }
        
        $entityManager = $this->getServiceLocator()
                ->get('ORMs\EntityManager');
        $form = new ItemForm($entityManager);
        
        $object = new DoctrineObject($entityManager, 'ORMs\Entity\Item');
        $form->get('item')->populateValues($object->extract($item));
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost();
            $form->setData($post);
            if ($form->isValid()) {
                
                $datas = $form->getData();
                $itemDatas = $datas['item'];
                
                $object->hydrate($itemDatas, $item);
                
                $entityManager->persist($item);
                $entityManager->flush();
                return $this->redirect()->toRoute("profile");
            }
        }
        return array(
            'form' => $form
        );
    }
    
    public function doAjaxEditAction() {
        $request = $this->getRequest();
        if (!$request->isXmlHttpRequest()) {
            return $this->redirect()->toRoute("home");
        }
        
        $id = $this->params("id", NULL);
        if ($id === NULL) {
            return new JsonModel(array(
                'error' => true,
                'message' => 'no-id'
            ));
        }
        
        if (!$request->isPost()) {
            return new JsonModel(array(
                'error' => true,
                'message' => 'no-post'
            ));
        }
        
        $post = $request->getPost();
        
        $em = $this->getServiceLocator()
                ->get('ORMs\EntityManager');
        
        $item = $this->_getItemModel()
                ->findItemById($id);
        
        $form = new ItemForm($em);
        $form->setData($post);
        if ($form->isValid()) {
            $datas = $form->getData();
            $itemDatas = $datas['item'];
            
            $hydrator = new DoctrineObject($em, 'ORMs\Entity\Item');
            $hydrator->hydrate($itemDatas, $item);
            $em->persist($item);
            $em->flush();
            
            return new JsonModel(array(
                'error' => false
            ));
        } else {
            return new JsonModel(array(
                'error' => true,
                'message' => 'invalid',
                'messages' => $form->getMessages()
            ));
        }
    }
    
    public function ajaxEditAction() {
        $request = $this->getRequest();
        if (!$request->isXmlHttpRequest()) {
            return $this->redirect()->toRoute("home");
        }
        
        $id = $this->params("id", NULL);
        if ($id === NULL) {
            return new JsonModel(array());
        }
        
        $item = $this->_getItemModel()->findItemById($id);
        $em = $this->getServiceLocator()
                ->get('ORMs\EntityManager');
        
        $form = new ItemForm($em);
        $hydrator = new DoctrineObject($em, 'ORMs\Entity\Item');
        $form->get('item')->populateValues($hydrator->extract($item));
        
        $vm = new ViewModel(array(
            'form' => $form,
            'id' => $id
        ));
        $vm->setTerminal(true);
        return $vm;
    }
    
    public function removeAction() {
        $id = $this->params("id", NULL);
        if ($id === NULL)
            $this->redirect()->toRoute("profile");
        
        $this->_getItemModel()->removeById($id);
        $this->redirect()->toRoute("profile");
    }
    
    /**
     * 
     * @return ItemModel
     */
    private function _getItemModel() {
        if ($this->_itemModel === NULL)
            $this->_itemModel = $this->getServiceLocator()
                ->get('Model\Item');
        
        return $this->_itemModel;
    }
    
    /**
     * 
     * @return CategoryModel
     */
    private function _getCategoryModel() {
        if ($this->_categoryModel === NULL) {
            $this->_categoryModel = $this->getServiceLocator()
                    ->get('Model\Category');
        }
        
        return $this->_categoryModel;
    }
    
}

?>
