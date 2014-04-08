<?php
namespace Home\Form;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of ItemFieldset
 *
 * @author adam
 */
class ItemFieldset extends Fieldset implements InputFilterProviderInterface{
    
    
    public function __construct(ObjectManager $om) {
        parent::__construct("item");
        
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
                'id' => 'item-id'
            )
        ));
        
        $this->add(array(
            'name' => 'title',
            'type' => 'text',
            'attributes' => array(
                'type' => 'text',
                'id' => 'title',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Tétel címe',
                'label_attributes' => array(
                    'class' => 'col-sm-4 control-label'
                )
            )
        ));
        
        $this->add(array(
            'name' => 'desc',
            'type' => 'textarea',
            'attributes' => array(
                'type' => 'textarea',
                'id' => 'desc',
                'class' => 'form-control'
            ),
            'options' =>array(
                'label' => 'Tétel rövid leírása',
                'label_attributes' => array(
                    'class' => 'col-sm-4 control-label'
                )
            )
        ));
        
        
        $this->add(array(
            'name' => 'category',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'object_manager' => $om,
                'target_class' => 'ORMs\Entity\Category',
                'property' => 'name',
                'label' => 'Kategória/Tantárgy',
                'label_attributes' => array(
                    'class' => 'col-sm-4 control-label'
                )
            ),
            'attributes' => array(
                'class' => 'form-control'
            )
        ));
        
    }
    
    public function getInputFilterSpecification() {
        return array(
            'id' => array(
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'Digits',
                    )
                )
            ),
            'desc' => array(
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim')
                ),
            ),
            'title' => array(
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim'
                    ),
                    array(
                        'name' => 'StripTags'
                    )
                ),
                'validators' => array(
                    array(
                        'name' => 'NotEmpty',
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 
                                    "A mező nem lehet üres"
                            )
                        )
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 3,
                            'max' =>  64,
                            'encoding' => 'utf-8',
                            'messages' => array(
                                \Zend\Validator\StringLength::TOO_LONG => 
                                    "Maximum 64 karaktert írhatsz be",
                                \Zend\Validator\StringLength::TOO_SHORT =>
                                    "Legalább 3 karaktert kell bírnod"
                            )
                        )
                    )
                )
            )
        );
    }
}

?>
