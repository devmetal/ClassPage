<?php
namespace Home\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Home\Form\FeedbackForm;
use Zend\View\Model\ViewModel;
/**
 * Description of FeedbackController
 *
 * @author adam
 */
class FeedbackController extends AbstractActionController {
    
    /**
     *
     * @var FeedbackForm
     */
    private $_feedbackForm;
    
    public function sendAction() {
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest() 
                && $request->isPost()) {
            
            $post = $request->getPost();
            $form = $this->_getFeedbackForm();
            $form->setData($post);
            if (!$form->isValid()) {
                $viewModel = new ViewModel(array(
                    'form' => $form
                ));
                
                $viewModel->setTemplate('home/feedback/form');
                $viewModel->setTerminal(true);
                return $viewModel;
            } else {
                $this->_sendFeedbackMessage($form->getData());
               $viewModel = new ViewModel();
               $viewModel->setTemplate('home/feedback/success');
               $viewModel->setTerminal(true);
               return $viewModel;
            }
        }
        
        return $this->redirect()
                ->toRoute('home');
    }
    
    private function _sendFeedbackMessage(array $datas) {
        $subject = $datas['category'];
        $body = $datas['desc'];
        $from = $datas['email'];
        $to = $this->getServiceLocator()
                ->get('config')['feedback']['to'];
        
        $message = new \Zend\Mail\Message();
        $message->setFrom($from)
                ->setSubject($subject)
                ->setBody($body)
                ->setTo($to);
        
        return $this->_getTransport()
                ->send($message);
    }
    
    /**
     * 
     * @return FeedbackForm
     */
    private function _getFeedbackForm() {
        if ($this->_feedbackForm === NULL) {
            $this->_feedbackForm = $this->getServiceLocator()
                    ->get('FeedbackForm');
        }
        
        return $this->_feedbackForm;
    }
    
    /**
     * 
     * @return \Zend\Mail\Transport\TransportInterface
     */
    private function _getTransport() {
        return $this->getServiceLocator()->get('MailTransport');
    }
    
}

?>
