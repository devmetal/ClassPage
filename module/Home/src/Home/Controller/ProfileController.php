<?php
namespace Home\Controller;

use Home\Model\ItemModel;
use ORMs\Entity\User;

/**
 * Description of ProfileController
 *
 * @author adam
 */
class ProfileController extends BaseController {
    
    /**
     *
     * @var ItemModel
     */
    private $_itemModel;
    
    /**
     *
     * @var User
     */
    private $_userIdentity;
    
    public function indexAction() {
        $em = $this->_getItemModel()
                ->getEntityManager();
        
        $userId = $this->_getUserIdentity()->getId();
        $user = $em->find('ORMs\Entity\User', $userId);
        
        return array(
            'user' => $user
        );
    }
    
    
    /**
     * 
     * @return ItemModel
     */
    private function _getItemModel() {
        if ($this->_itemModel === NULL) {
            $this->_itemModel = 
                    $this->getServiceLocator()
                    ->get('Model\Item');
        }
        
        return $this->_itemModel;
    }
    
    /**
     * 
     * @return User
     */
    private function _getUserIdentity() {
        if ($this->_userIdentity === NULL) {
            $this->_userIdentity = 
                    $this->getServiceLocator()
                    ->get('Auth\Service\Auth')
                    ->getIdentity();
        }
        
        return $this->_userIdentity;
    }
    
}

?>
