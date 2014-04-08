<?php
namespace Users\Form\Registrate;

use Zend\InputFilter\InputFilter;
use DoctrineModule\Validator\ObjectExists;
use DoctrineModule\Validator\NoObjectExists;
use Doctrine\Common\Persistence\ObjectManager;
/**
 * Description of RegisterFilter
 *
 * @author adam
 */
class RegisterFilter extends InputFilter {
    
    public function __construct(ObjectManager $om) {
        
        $repo = $om->getRepository('ORMs\Entity\Invitation');
        $emailRepo = $om->getRepository('ORMs\Entity\User');
        
        $emailNoExists = new NoObjectExists(array(
            'object_repository' => $emailRepo,
            'fields' => array('email'),
        ));
        
        $emailNoExists->setMessages(array(
            NoObjectExists::ERROR_OBJECT_FOUND => "A megadott email
                címmel valaki már regisztrált!"
        ));
        
        $nickNoExists = new NoObjectExists(array(
            'object_repository' => $emailRepo,
            'fields' => array('nick')
        ));
        
        $nickNoExists->setMessages(array(
            NoObjectExists::ERROR_OBJECT_FOUND => "A megadott becenevet
                valaki már használja."
        ));
        
        $exists = new ObjectExists(array(
            'object_repository' => $repo,
            'fields' => array('code'),
        ));
        
        $exists->setMessages(array(
            ObjectExists::ERROR_NO_OBJECT_FOUND => "A megadott kód 
                nem található az adatbázisban!"
        ));
        
        $invEmail = new InvitationEmailValidator(array(
            'object_repository' => $repo
        ));
        
        
        $this->add(array(
            'name' => 'reg-code',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => 
                                "Kötelező adat!"
                        )
                    )
                ),
                array(
                    'name' => 'Alnum',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'allowedWhiteSpace' => false,
                        'messages' => array(
                            \Zend\I18n\Validator\Alnum::INVALID => 
                                "A kódban csak betűk és számok szerepelhetnek,
                                    szóköz nélkül.",
                            \Zend\I18n\Validator\Alnum::NOT_ALNUM => 
                                "A kódban csak betűk és számok szerepelhetnek,
                                    szóköz nélkül.",
                            \Zend\I18n\Validator\Alnum::STRING_EMPTY => 
                                "A kódban csak betűk és számok szerepelhetnek,
                                    szóköz nélkül."
                        )
                    )
                ),
                $exists //Létezik e a megadott kód
            )
        ));
        
        $this->add(array(
            'name' => 'nick',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => 
                                "Kötelező adat!"
                        )
                    )
                ),
                array(
                    'name' => 'StringLength',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 4,
                        'max' => 64,
                        'messages' => array(
                            \Zend\Validator\StringLength::TOO_LONG => 
                                "A becenév maximum 64 karakteres lehet.",
                            \Zend\Validator\StringLength::TOO_SHORT => 
                                "A becenév minimum 4 karakterből kell álljon."
                        )
                    )
                ),
                array(
                    'name' => 'Alnum',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'allowWhiteSpace' => false,
                        'messages' => array(
                            \Zend\I18n\Validator\Alnum::NOT_ALNUM => 
                                "A becenév csak betűket számokat tartalmazhat,
                                    szóköz nélkül.",
                            \Zend\I18n\Validator\Alnum::STRING_EMPTY => 
                                "A becenév csak betűket számokat tartalmazhat,
                                    szóköz nélkül."
                        )
                    )
                ),
                $nickNoExists //használatban van e
            ),
            'filters' => array(
                array('name' => 'StripTags')
            )
        ));
        
        $this->add(array(
            'name' => 'email',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => 
                                "Kötelező adat!"
                        )
                    )
                ),
                array(
                    'name' => 'EmailAddress',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\EmailAddress::INVALID_FORMAT => 
                                "Helytelen email cím!"
                        )
                    )
                ),
                array(
                    'name' => 'StringLength',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'max' => 128,
                        'messages' => array(
                            \Zend\Validator\StringLength::TOO_LONG => 
                                "Az email cím maximum 128 karakterből állhat"
                        )
                    )
                ),
                $emailNoExists, //Az email használatban van e
                $invEmail //A kód és email párosítása megfelelő e
            ),
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim')
            )
        ));
        
        $this->add(array(
            'name' => 'confirm_email',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => 
                                "Kötelező adat!"
                        )
                    )
                ),
                array(
                    'name' => 'Identical',
                    'options' => array(
                        'token' => 'email',
                        'messages' => array(
                            \Zend\Validator\Identical::NOT_SAME => 
                                "A megadott email címek nem egyeznek!"
                        )
                    )
                )
            )
        ));
        
        $this->add(array(
            'name' => 'pass',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => 
                                "Kötelező adat!"
                        )
                    )
                ),
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 5,
                        'max' => 128,
                        'messages' => array(
                            \Zend\Validator\StringLength::TOO_LONG => 
                                "A jelszó maximum 128 karaktrből állhat.",
                            \Zend\Validator\StringLength::TOO_SHORT => 
                                "A jelszó minimum 5 karakterből kell álljon."
                        )
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
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => 
                                "Kötelező adat!"
                        )
                    )
                ),
                array(
                    'name' => 'Identical',
                    'options' => array(
                        'token' => 'pass',
                        'messages' => array(
                            \Zend\Validator\Identical::NOT_SAME => 
                                "A megadott jelszavak nem egyeznek!"
                        )
                    )
                )
            )
        ));
        
    }
    
}

?>
