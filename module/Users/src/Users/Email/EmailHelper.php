<?php
namespace Users\Email;

use Zend\ServiceManager\ServiceManager;
/**
 * Description of EmailHelper
 *
 * @author adam
 */
class EmailHelper  {
    
    /*
     * @var ServiceManager
     */
    private $sm;
    
    public function __construct(ServiceManager $sm) {
        $this->sm = $sm;
    }
    
    public function sendEmail($address, $code) {
        
        $html = new \Zend\Mime\Part("<h1>Köszönjük hogy regisztráltál!</h1>
                    <p>Az aktiváló linked:
                    <a href='http://elitosztaly.eu/activate/$code'>Kattints ide</a></p>");
        $html->type = "text/html";
        
        $mimeMessage = new \Zend\Mime\Message();
        $mimeMessage->setParts(array($html));
        
        $message = new \Zend\Mail\Message();
        $message->setTo($address)
                ->setFrom($this->_getFrom())
                ->setSubject("Regisztráció aktiválása")
                ->setBody($mimeMessage);
        
        return $this->_getTransport()
                ->send($message);
    }
    
    private function _getTransport() {
        return $this->sm->get('MailTransport');
    }
    
    private function _getFrom() {
        $config = $this->sm->get('config');
        $mailConfig = $config['mail_config'];
        $environment = getenv('APPLICATION_ENVIRONMENT');
        $transportConfig = $mailConfig[$environment];
        return $transportConfig['from'];
    }
    
}

?>
