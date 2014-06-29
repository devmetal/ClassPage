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
            
            $invCode = $datas['reg-code'];
            $invition = $entityManager
                    ->getRepository('ORMs\Entity\Invitation')
                    ->findOneBy(array('code' => $invCode));
            
            $invition->assignToUser($user);
            
            $this->sendMail($user->getEmail(),$code);
            
            $entityManager->persist($user);
            $entityManager->persist($invition);
            $entityManager->flush();
            
            return TRUE;
        } else {
            return array(
                'form' => $form
            );
        }
    }
    
    
    public function activateUser($code) {
        $user = $this->getEntityManager()
                ->getRepository('ORMs\Entity\User')
                ->findOneBy(array('code' => $code));
        
        if (!$user) {
            return false;
        } else if ($user->isActive()) {
            return false;
        } else {
            $user->activate();
            
            $manager = $this->getEntityManager();
            $manager->persist($user);
            $manager->flush();
            
            return true;
        }
    }
    
    public function createInvitationTo($email) {
        $userId = $this->_getAuthService()
                ->getIdentity()->getId();
        
        $user = $this->getEntityManager()
                ->find('ORMs\Entity\User', $userId);
        
        $code = uniqid() . md5(time() . rand(1000,2000));
        
        $em = $this->getEntityManager();
        
        $invition = new \ORMs\Entity\Invitation();
        $invition->setUsed(false);
        $invition->setCode($code);
        $invition->setEmail($email);
        
        $user->addInvitation($invition);
        
        $em->persist($invition);
        $em->flush();
        
        $this->sendInvition($email, $code, $user->getNick());
    }
    
    public function getUserItems($id) {
        $user = $this->getEntityManager()->find("ORMs\Entity\User", $id);
        if ($user) {
            return $user->getItemsOrderByCreatedAndCategory();
        } else {
            return false;
        }
    }
    
    public function getUserComments($id = NULL) {
        
    }
    
    private function sendMail($email, $code) {
        return $this->_sm->get('EmailHelper')
                ->sendEmail($email, $code);
    }
    
    private function sendInvition($email, $code, $user) {
        return $this->_sm->get('EmailHelper')
                ->sendInvitation($email,$code,$user);
    }
    
    /**
     * 
     * @return \Users\Form\Registrate\RegistrateForm
     */
    private function _getRegForm() {
        return $this->_sm->get('RegistrationForm');
    }
    
    /**
     * @return \Auth\Service\AuthService
     */
    private function _getAuthService() {
        return $this->_sm->get('Auth\Service\Auth');
    }
    
}

?>
