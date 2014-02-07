<?php
namespace Users\Model;

/**
 * Description of UserModel
 *
 * @author adam
 */
class UserModel extends AbstractModel {
    
    /**
     *
     * @var \Zend\ServiceManager\ServiceManager
     */
    private $_sm;
    
    public function __construct(\Doctrine\ORM\EntityManager $manager, 
            \Zend\ServiceManager\ServiceManager $sm) {
        parent::__construct($manager);
        
        $this->_sm = $sm;
    }
    
    public function registrateUser(\Zend\Http\Request $req) {
        
    }
    
    public function activateUser(\Zend\Http\Request $req) {
        
    }
    
    public function getUserProfile($id = NULL) {
        
    }
    
    public function getUserItems($id = NULL) {
        
    }
    
    public function getUserComments($id = NULL) {
        
    }
    
}

?>
