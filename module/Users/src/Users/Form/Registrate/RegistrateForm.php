<?php
namespace Users\Form\Registrate;

use Zend\Form\Form;
use Zend\Captcha\AdapterInterface;
/**
 * Description of RegistrateForm
 *
 * @author adam
 */
class RegistrateForm extends Form {
    
    public function __construct(AdapterInterface $captchaAdapter) {
        
        parent::__construct("Regisztráció");
        
        $this->setAttribute("method", "post");
        $this->setAttribute("enctype", "multipart/form-data");
        $this->setAttribute("action", "/registrate/process");
        
        $this->add(array(
            'name' => 'reg-code',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Meghívó kód',
                'label_attributes' => array(
                    'class' => 'col-sm-3'
                )
            )
        ));
        
        $this->add(array(
            'name' => 'nick',
            'attributes' => array(
                'type' => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Felhasználónév',
                'label_attributes' => array(
                    'class' => 'col-sm-3'
                )
            )
        ));
        
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type' => 'email',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Email cím',
                'label_attributes' => array(
                    'class' => 'col-sm-3'
                )
            )
        ));
        
        $this->add(array(
            'name' => 'confirm_email',
            'attributes' => array(
                'type' => 'email',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Email cím megerősítése',
                'label_attributes' => array(
                    'class' => 'col-sm-3'
                )
            )
        ));
        
        $this->add(array(
            'name' => 'pass',
            'attributes' => array(
                'type' => 'password',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Jelszó',
                'label_attributes' => array(
                    'class' => 'col-sm-3'
                )
            )
        ));
        
        $this->add(array(
            'name' => 'confirm_pass',
            'attributes' => array(
                'type' => 'password',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Jelszó megerősítése',
                'label_attributes' => array(
                    'class' => 'col-sm-3'
                )
            )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'class' => 'btn btn-default',
                'value' => 'Regisztrálok'
            )
        ));
        
        $captcha = new \Zend\Form\Element\Captcha('captcha');
        $captcha->setCaptcha($captchaAdapter);
        $this->add($captcha);
        
        $this->add(new \Zend\Form\Element\Csrf('security'));
        
    }
    
}

?>
