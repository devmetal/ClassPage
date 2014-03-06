<?php
namespace Users\Form\Registrate;

use Zend\InputFilter\InputFilter;
use DoctrineModule\Validator\ObjectExists;
use Doctrine\Common\Persistence\ObjectManager;
/**
 * Description of RegisterFilter
 *
 * @author adam
 */
class RegisterFilter extends InputFilter {
    
    public function __construct(ObjectManager $om) {
        
        $repo = $om->getRepository('ORMs\Entity\Invitation');
        $exists = new ObjectExists(array(
            'object_repository' => $repo,
            'fields' => array('code'),
        ));
        
        $invEmail = new InvitationEmailValidator(array(
            'object_repository' => $repo
        ));
        
        $exists->setMessages(array(
            ObjectExists::ERROR_NO_OBJECT_FOUND => "A megadott kód 
                nem található az adatbázisban!"
        ));
        
        $this->add(array(
            'name' => 'reg-code',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'Alnum',
                    'options' => array(
                        'allowedWhiteSpace' => false
                    )
                ),
                $exists
            )
        ));
        
        $this->add(array(
            'name' => 'nick',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 2,
                        'max' => 140
                    )
                ),
                array(
                    'name' => 'Alnum',
                    'options' => array(
                        'allowWhiteSpace' => false
                    )
                )
            ),
            'filters' => array(
                array('name' => 'StripTags')
            )
        ));
        
        $this->add(array(
            'name' => 'email',
            'required' => true,
            'validators' => array(
                array('name' => 'EmailAddress'),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'max' => 140
                    )
                ),
                $invEmail
            ),
            'filters' => array(
                array('name' => 'StripTags')
            )
        ));
        
        $this->add(array(
            'name' => 'confirm_email',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'Identical',
                    'options' => array(
                        'token' => 'email'
                    )
                )
            )
        ));
        
        $this->add(array(
            'name' => 'pass',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 5,
                        'max' => 140
                    )
                )
            ),
            'filters' => array(
                array('name' => 'StripTags')
            )
        ));
        
        $this->add(array(
            'name' => 'confirm_pass',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'Identical',
                    'options' => array(
                        'token' => 'pass'
                    )
                )
            )
        ));
        
    }
    
}

?>
