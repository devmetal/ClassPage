<?php
namespace Home\Form;

use Zend\InputFilter\InputFilter;

/**
 * Description of FeedbackFormFilter
 *
 * @author adam
 */
class FeedbackFormFilter extends InputFilter {
    
    public function __construct() {
        $this->add(array(
            'name' => 'email',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'EmailAddress',
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\EmailAddress::INVALID_FORMAT =>
                                "Helytelen email címet adtál meg"
                        )
                    )
                )
            )
        ));
        
        $this->add(array(
            'name' => 'desc',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 10,
                        'max' => 512,
                        'messages' => array(
                            \Zend\Validator\StringLength::TOO_LONG => 
                                "Legfeljebb 512 karaktert írhatsz",
                            \Zend\Validator\StringLength::TOO_SHORT =>
                                "Legalább 10 karaktert kell beírnod"
                        )
                    )
                )
            )
        ));
    }
    
}

?>
