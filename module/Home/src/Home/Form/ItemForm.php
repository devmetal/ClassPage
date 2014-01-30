<?php
namespace Home\Form;

use Zend\Form\Form;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of ItemForm
 *
 * @author adam
 */
class ItemForm extends Form{
    
    public function __construct(ObjectManager $om) {
        parent::__construct("item-form");
        
        $this->setAttribute("method", "post");
        
        $itemFieldset = new ItemFieldset($om);
        $itemFieldset->setUseAsBaseFieldset(true);
        $this->add($itemFieldset);
        
//        $this->add(array(
//            'name' => 'security',
//            'type' => 'Zend\Form\Element\Csrf'
//        ));
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Tétel mentése',
                'id' => 'submit'
            )
        ));
        
    }
    
}

?>
