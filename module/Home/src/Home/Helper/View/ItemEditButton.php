<?php
namespace Home\Helper\View;

use Zend\View\Helper\AbstractHelper;
use Auth\Service\AuthService;
use ORMs\Entity\Item;

/**
 * Description of ItemEditButton
 *
 * @author adam
 */
class ItemEditButton extends AbstractHelper {
    
    /**
     *
     * @var AuthService
     */
    private $authService;
    
    public function __construct(AuthService $auth) {
        $this->authService = $auth;
    }
    
    public function __invoke(Item $i) {
        if (!$this->authService->hasIdentity()) {
            return false;
        }
        
        $ident = $this->authService->getIdentity();
        $usrId = $ident->getId();
        
        if ($i->getUploader()->getId() === $usrId) {
            return $this->view->url('item',array(
                'action' => 'edit',
                'id' => $i->getId()
            ));
        } else {
            return false;
        }
    }
    
}

?>
