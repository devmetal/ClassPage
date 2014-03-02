<?php
namespace Users\Model;


use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
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
        $form = $this->_getRegForm();
        $post = $req->getPost();
        
        $form->setData($post);
        if ($form->isValid()) {
            $datas = $form->getData();
            $entityManager = $this->getEntityManager();
            $object = new DoctrineObject($entityManager,'ORMs\Entity\User');
            
            $user = new \ORMs\Entity\User();
            $object->hydrate($datas, $user);
            
            $user->genCode();
            $code = $user->getCode();
            
            $this->sendMail($user->getEmail(),$code);
            
            $entityManager->persist($user);
            $entityManager->flush();
            
            return TRUE;
        } else {
            return array(
                'form' => $form
            );
        }
    }
    
    private function sendMail($email, $code) {
        return $this->_sm->get('EmailHelper')
                ->sendEmail($email, $code);
    }
    
    /**
     * 
     * @return \Users\Form\Registrate\RegistrateForm
     */
    private function _getRegForm() {
        return $this->_sm->get('RegistrationForm');
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
