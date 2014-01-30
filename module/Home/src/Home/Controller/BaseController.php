<?php
namespace Home\Controller;

use Zend\Mvc\Controller\AbstractActionController;
/**
 * Description of BaseController
 *
 * @author adam
 */
abstract class BaseController extends AbstractActionController {
    
    public function onDispatch(\Zend\Mvc\MvcEvent $e) {
        
        $auth = $this->getServiceLocator()->get('Auth\Service\Auth');
        if (!$auth->hasIdentity()) {
            return $this->redirect()->toRoute("auth");
        }
        
        parent::onDispatch($e);
    }
    
}

?>
