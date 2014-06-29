<?php
namespace Users\Form\Invitation;

use Zend\Form\Form;
use Zend\Captcha\AdapterInterface;
/**
 * Description of InvitationForm
 *
 * @author adam
 */
class InvitationForm extends Form {
    
    public function __construct(AdapterInterface $captchaAdapter) {
        parent::__construct("invitation");
        
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type' => 'email'
            ),
            'options' => array(
                'label' => 'Az email cím, amire a meghívót küldjük'
            )
        ));
        
        $this->add(array(
            'name' => 'send',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Küldés'
            )
        ));
        
        $captcha = new \Zend\Form\Element\Captcha('captcha');
        $captcha->setCaptcha($captchaAdapter);
        $captcha->setLabel("A meghívó elküldéséhez kérlek add meg a biztonsági kódot");
        $this->add($captcha);
        
        $csrf = new \Zend\Form\Element\Csrf('csrf');
        $this->add($csrf);
    }
    
}

?>
