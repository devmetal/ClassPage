<?php
namespace Users\Form\Invitation;

use Zend\Form\Form;

/**
 * Description of InvitationForm
 *
 * @author adam
 */
class InvitationForm extends Form {
    
    public function __construct() {
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
        
        $csrf = new \Zend\Form\Element\Csrf('csrf');
        $this->add($csrf);
    }
    
}

?>
