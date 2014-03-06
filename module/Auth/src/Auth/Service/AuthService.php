<?php
namespace Auth\Service;

use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of AuthService
 *
 * @author adam
 */
class AuthService {
    
    /**
     *
     * @var \Zend\Authentication\AuthenticationService
     */
    private $_dtAuth;
    
    /**
     *
     * @var ServiceLocatorInterface
     */
    private $_serviceLocator;
    
    /**
     * 
     * @param \Zend\ServiceManager\ServiceLocatorInterface $loc
     */
    public function __construct(ServiceLocatorInterface $loc) {
        $this->_serviceLocator = $loc;
    }
    
    /**
     * 
     * @param string $email
     * @param string $pass
     * @return boolean|\Zend\Authentication\Result
     */
    public function login($email, $pass, $remember = 0) {
        $auth = $this->_getDoctrineAuthService();
        $auth->getAdapter()->setIdentityValue($email);
        $auth->getAdapter()->setCredentialValue($pass);
        
        $res = $auth->authenticate();
        if ($res->isValid()) {
            
            $user = $this->getIdentity();
            if (!$user->isActive()) {
                $auth->clearIdentity();
                return false;
            }
            
            if ($remember == 1) {
                $auth->getStorage()->setRememberMe();
            }
            
            return TRUE;
        } else {
            return $res;
        }
    }
    
    public function logout() {
        if ($this->hasIdentity()) {
            $auth = $this->_getDoctrineAuthService();
            $auth->clearIdentity();
            return TRUE;
        }
        return FALSE;
    }
    
    public function setStorage($storage) {
        $this->_getDoctrineAuthService()
                ->setStorage($storage);
    }
    
    public function getIdentity() {
        $auth = $this->_getDoctrineAuthService();
        return $auth->getIdentity();
    }
    
    public function hasIdentity() {
        $auth = $this->_getDoctrineAuthService();
        return $auth->hasIdentity();
    }
    
    /**
     * 
     * @return \Zend\Authentication\AuthenticationService
     */
    private function _getDoctrineAuthService() {
        if ($this->_dtAuth === NULL) {
            $this->_dtAuth = 
                    $this->_serviceLocator->get('doctrine.authenticationservice.orm_default');
        }
        
        return $this->_dtAuth;
    }
    
}

?>
