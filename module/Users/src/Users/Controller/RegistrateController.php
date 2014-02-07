<?php
namespace Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;

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
        
    }
    
    public function confirmAction() {
        
    }
    
    public function activateAction() {
        
    }
    
}

?>
