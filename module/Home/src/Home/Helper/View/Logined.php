<?php
namespace Home\Helper\View;

use Zend\View\Helper\AbstractHelper;
use Auth\Service\AuthService;

/**
 * Description of Logined
 *
 * @author adam
 */
class Logined extends AbstractHelper {
    
    /**
     *
     * @var AuthService
     */
    private $_as;
    
    public function __construct(AuthService $as) {
        $this->_as = $as;
    }
    
    public function __invoke() {
        return $this->_as->hasIdentity();
    }
    
}

?>
