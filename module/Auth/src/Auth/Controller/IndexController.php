<?php
namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\EventManager\EventManagerInterface;
use Auth\Form\LoginForm;
use Auth\Service\AuthService;

/**
 * Description of IndexController
 *
 * @author adam
 */
class IndexController extends AbstractActionController {
    
    /**
     *
     * @var AuthService
     */
    private $_authService;
    
    public function logoutAction() {
        $auth = $this->_getAuthService();
        if ($auth->hasIdentity()) {
            $auth->logout();
        }
        $this->redirect()->toRoute("auth");
    }
    
    public function authAction() {
        $request = $this->getRequest();
        $form = new LoginForm();
        if ($request->isPost()) {
            $post = $request->getPost();
            
            
            $form->setData($post);
            if ($form->isValid()) {
                $datas = $form->getData();
                
                $auth = $this->_getAuthService();
                $res = $auth->login($datas['email'], $datas['pass'], $datas['remember-me']);
                if ($res === TRUE) {
                    return $this->redirect()->toRoute('home');
                }
            }
            
            return new ViewModel(array(
                'form' => $form,
                'message' => "Valami nem stimmel"
            ));
        } else {
            return new ViewModel(array(
                'form' => $form,
            ));
        }
    }
    
    /**
     * 
     * @return AuthService
     */
    private function _getAuthService() {
        if ($this->_authService === NULL) {
            $this->_authService = $this->getServiceLocator()
                    ->get('Auth\Service\Auth');
        }
        
        return $this->_authService;
    }
    
    public function setEventManager(EventManagerInterface $events) {
        parent::setEventManager($events);
        
        $controller = $this;
        $events->attach("dispatch", function($e) use ($controller){
            $controller->layout()->setTemplate("auth/layout");
        },100);
        
        return $this;
    }
    
}

?>
