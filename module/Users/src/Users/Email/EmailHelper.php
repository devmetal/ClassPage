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
                    <a href='http://elitosztaly.eu/activate/$code'>http://elitosztaly.eu/activate/$code</a></p>
                <p>Ha a link nem jelenne meg, akkor másold az alábbi címet a böngésző
                címsorába: http://elitosztaly.eu/activate/$code</p>");
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
    
    public function sendInvitation($address, $code, $sender) {
        $html = new \Zend\Mime\Part("<h1>Meghívásod érkezett!</h1>
            <p>{$sender} nick névvel rendelkező felhasználó 
                meghívást küldött neked az elitosztaly.eu oldalra.</p>
            <h2>Regisztrációs kódod: <strong>{$code}</strong></h2>
            <p>A fenti kódot a regisztrációs felületen kell
            megadnod!</p>
            <p>Ezen az oldalon az általatok megírt és feltöltött érettségi
            tételeket találhatod meg, és te is tölthetsz fel hogyha szeretnél
            segíteni társaidon, ezekben a nehéz időkben.</p>
            <p>Regisztráció <a href='http://elitosztaly.eu' target='_blank'>Itt</a></p>");
            
        $html->type = "text/html";
        
        $mimeMessage = new \Zend\Mime\Message();
        $mimeMessage->setParts(array($html));
        
        $message = new \Zend\Mail\Message();
        $message->setTo($address)
                ->setFrom($this->_getFrom())
                ->setSubject("Elit Osztály - Meghívót kaptál")
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
