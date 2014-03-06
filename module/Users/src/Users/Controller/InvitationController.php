<?php
namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Users\Model\UserModel;
/**
 * Description of InvitationController
 *
 * @author adam
 */
class InvitationController extends AbstractActionController {
    
    /**
     * @var UserModel
     */
    private $_model;
    
    private $_invForm;
    
    public function createAction() {
        if (!$this->_hasIdentity()) {
            return $this->redirect()
                    ->toRoute('home');
        }
        
        $form = $this->_getInvForm();
        
        return new ViewModel(array(
            'form' => $form
        ));
    }
    
    public function createProcessAction() {
        if (!$this->_hasIdentity()) {
            return $this->redirect()
                    ->toRoute('home');
        }
        
        $request = $this->getRequest();
        if (!$request->isPost()) {
            return $this->redirect()
                    ->toRoute('invitation');
        } else {
            $post = $request->getPost();
            $form = $this->_getInvForm();
            $form->setData($post);
            
            if (!$form->isValid()) {
                $vm = new ViewModel(array(
                    'form' => $form
                ));
                $vm->setTemplate('users/invitation/create');
                return $vm;
            } else {
                $datas = $form->getData();
                $email = $datas['email'];
                $this->_getUserModel()
                        ->createInvitationTo($email);
                
                return $this->redirect()
                    ->toRoute('invitation',array('action' => 'success'));
            }
        }
    }
    
    public function successAction() {
        return array();
    }
    
    /**
     *  @return UserModel
     */
    private function _getUserModel() {
        if ($this->_model === NULL){
            $this->_model = $this->getServiceLocator()
                    ->get('UserModel');
        }
        
        return $this->_model;
    }
    
    /**
     * 
     * @return \Users\Form\Invitation\InvitationForm
     */
    private function _getInvForm() {
        if ($this->_invForm === NULL) {
            $this->_invForm = $this->getServiceLocator()
                    ->get('InvitationForm');
        }
        
        return $this->_invForm;
    }
    
    private function _hasIdentity() {
        $auth = $this->getServiceLocator()
                ->get('Auth\Service\Auth');
        
        return $auth->hasIdentity();
    }
    
}

?>
