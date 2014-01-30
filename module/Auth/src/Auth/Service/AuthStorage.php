<?php
namespace Auth\Service;

use Zend\Authentication\Storage\Session;

/**
 * Description of AuthStorage
 *
 * @author adam
 */
class AuthStorage extends Session {
    
    public function setRememberMe($time = 1209600) {
        $this->session->getManager()->rememberMe($time);
    }
    
}

?>
