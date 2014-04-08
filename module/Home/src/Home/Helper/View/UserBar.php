<?php
namespace Home\Helper\View;

use Zend\View\Helper\AbstractHelper;
use Auth\Service\AuthService;

/**
 * Description of UserBar
 *
 * @author adam
 */
class UserBar extends AbstractHelper {
    
    /**
     *
     * @var AuthService
     */
    private $_authService;
    
    public function __construct(AuthService $serv) {
        $this->_authService = $serv;
    }
    
    public function __invoke() {
        $id = $this->_authService->getIdentity();
        $profileLink = $this->view->url('profile');
        return "<p class='navbar-text'>Belépve mint: 
            <a href='".$profileLink."'><strong>" . $id->getNick() . "</strong></a></p>";
    }
    
}

?>
