<?php
namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Auth\Service\AuthService;
/**
 * Description of ProfileController
 *
 * @author adam
 */
class ProfileController extends AbstractActionController {
    
    /**
     *
     * @var AuthService
     */
    private $_authService;
    
    public function indexAction() {
        $auth = $this->_getAuthService();
        if (!$auth->hasIdentity()) {
            return $this->redirect()->toRoute('home');
        }
        
        $identity = $auth->getIdentity();
        $id = $identity->getId();
        return array(
            'user' => $identity,
            'items' => $this->getServiceLocator()
                ->get('UserModel')
                ->getUserItems($id)
        );
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
    
}

?>
