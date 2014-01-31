<?php
namespace Auth\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputProviderInterface;
use Zend\InputFilter\InputFilterProviderInterface;
/**
 * Description of LoginForm
 *
 * @author adam
 */
class LoginForm extends Form implements InputFilterProviderInterface {
    
    public function __construct() {
        parent::__construct("login");
        
        $this->setAttribute("action", "/auth");
        $this->setAttribute("method", "post");
        $this->setAttribute("role", "form");
        
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type' => 'text',
                'id' => 'email',
                'placeholder' => 'Email cím',
                'class' => 'form-control'
            )
        ));
        
        $this->add(array(
            'name' => 'pass',
            'attributes' => array(
                'type' => 'password',
                'id' => 'pass',
                'placeholder' => 'Jelszó',
                'class' => 'form-control'
            )
        ));
        
        $this->add(array(
            'name' => 'remember-me',
            'type' => 'checkbox',
            'attributes' => array(
                'id' => 'remember-me',
            ),
            'options' => array(
                'label' => 'Emlékezz rám!',
                'use_hidden_element' => true,
                'checked_value' => 1,
                'unchecked_value' => 0
            )
        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Belépés',
                'class' => 'btn'
            )
        ));
    }

    public function getInputFilterSpecification() {
        return array(
            'email' => array(
                'required' => true,
                'filters' => array(
                    new \Zend\Filter\StringTrim()
                ),
                'validators' => array(
                    new \Zend\Validator\EmailAddress()
                )
            ),
            'pass' => array(
                'required' => true,
                'filters' => array(
                    new \Zend\Filter\StringTrim()
                ),
                'validators' => array(
                    new \Zend\I18n\Validator\Alnum()
                )
            ),
            'remember-me' => array(
                'required' => false
            )
        );
    }
    
}

?>
