<?php
namespace Home\Helper\View;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceManager;
/**
 * Description of FeedbackForm
 *
 * @author adam
 */
class FeedbackForm extends AbstractHelper {
    
    /**
     *
     * @var ServiceManager
     */
    private $_sm;
    
    public function __construct(ServiceManager $sm) {
        $this->_sm = $sm;
    }
    
    public function __invoke() {
        $form = $this->_sm->get('FeedbackForm');
        return $this->view->partial('home/feedback/form.phtml',array(
            'form' => $form
        ));
    }
    
}

?>
