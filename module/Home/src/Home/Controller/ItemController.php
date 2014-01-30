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
            $this->redirect()->toRoute('items');
        
        $model = $this->_getItemModel();
        $item = $model->findItemById($id);
        
        return array(
            'item' => $item
        );
    }
    
    public function downloadAction() {
        
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
                        ->toRoute('items', array(
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
        
        if ($request->isPost()) {
            $post = $request->getPost();
            $form->setData($post);
            if ($form->isValid()) {
                $datas = $form->getData();
                $itemDatas = $datas['item'];
                
                $hydrator->hydrate($itemDatas, $item);
                $em->persist($item);
                $em->flush();
                
                $vm = new ViewModel(array(
                    'result' => 'success',
                    'form' => $form,
                    'id' => $id
                ));
                $vm->setTerminal(true);
                return $vm;
            } else {
                $vm = new ViewModel(array(
                    'result' => 'invalid',
                    'form' => $form,
                    'id' => $id
                ));
                $vm->setTerminal(true);
                return $vm;
            }
        } else {
            $vm = new ViewModel(array(
                'form' => $form,
                'id' => $id
            ));
            $vm->setTerminal(true);
            return $vm;
        }
    }
    
    public function removeAction() {
        $id = $this->params("id", NULL);
        if ($id === NULL)
            $this->redirect()->toRoute("profile");
        
        $this->_getItemModel()->removeById($id);
        $this->redirect()->toRoute("profile");
    }
    
    public function rateAction() {
        
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
