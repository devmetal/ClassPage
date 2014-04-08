<?php
namespace Home\Form;

use \Zend\Form\Form;
/**
 * Description of ReplyForm
 *
 * @author adam
 */
class FeedbackForm extends Form {
    
    public function __construct(array $config) {
        parent::__construct('reply');
        
        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type' => 'email',
                'class' => 'form-control input-sm'
            ),
            'options' => array(
                'label' => 'Email',
                'label_attributes' => array(
                    'class' => 'col-sm-2 control-label'
                )
            )
        ));
        
        $categories = $config['categories'];
        $this->add(array(
            'name' => 'category',
            'type' => 'select',
            'attributes' => array(
                'type' => 'select',
                'class' => 'form-control input-sm'
            ),
            'options' => array(
                'label' => 'Témakör',
                'value_options' => $categories,
                'label_attributes' => array(
                    'class' => 'col-sm-2 control-label'
                )
            )
        ));
        
        $this->add(array(
            'name' => 'desc',
            'attributes' => array(
                'type' => 'textarea',
                'class' => 'form-control input-sm'
            ),
            'options' => array(
                'label' => 'Kifejtés',
                'label_attributes' => array(
                    'class' => 'col-sm-2 control-label'
                )
            )
        ));
        
        $this->add(new \Zend\Form\Element\Csrf('security'));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Küldés',
                'class' => 'btn btn-primary btn-sm'
            )
        ));
    }
    
}

?>
