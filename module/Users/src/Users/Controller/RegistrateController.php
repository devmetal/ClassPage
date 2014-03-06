<?php
namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Description of RegistrateController
 *
 * @author adam
 */
class RegistrateController extends AbstractActionController{
    
    public function indexAction() {
        $form = $this->getServiceLocator()
                ->get('RegistrationForm');
        
        return new \Zend\View\Model\ViewModel(array(
            'form' => $form
        ));
    }
    
    public function processAction() {
        $request = $this->getRequest();
        if (!$request->isPost()) {
            $this->redirect()->toRoute("registration");
        } else {
            $model = $this->getServiceLocator()
                    ->get('UserModel');
            
            $result = $model->registrateUser($request);
            if ($result !== TRUE) {
                $viewModel = new \Zend\View\Model\ViewModel();
                $viewModel->setTemplate('users/registrate/index');
                $viewModel->setVariables($result);
                return $viewModel;
            } else {
                $this->redirect()->toRoute("registration",array('action' => 'confirm'));
            }
        }
    }
    
    public function confirmAction() {
        return array();
    }
    
    public function activateAction() {
        $code = $this->params()
                ->fromRoute('code',NULL);
        
        if (!$code) {
            return $this->redirect()->toRoute("registration");
        }
        
        $model = $this->getServiceLocator()
                ->get('UserModel');
        
        if ($model->activateUser($code)) {
            return new ViewModel(array(
                'result' => 'success'
            ));
        } else {
            return new ViewModel(array(
                'result' => 'fail'
            ));
        }
    }
    
}

?>
