<?php
namespace Users\Form\Invitation;

use Zend\InputFilter\InputFilter;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Validator\NoObjectExists;
/**
 * Description of InvitationFormFilter
 *
 * @author adam
 */
class InvitationFormFilter extends InputFilter {
    
    public function __construct(ObjectManager $om) {
        $repo = $om->getRepository('ORMs\Entity\Invitation');
        
        $existsValidator = new NoObjectExists(array(
            'object_repository' => $repo,
            'fields' => array('email')
        ));
        
        $existsValidator->setMessages(array(
            NoObjectExists::ERROR_OBJECT_FOUND => "A megadott email címre már küldtek egy meghívót"
        ));
        
        $this->add(array(
            'name' => 'email',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'EmailAddress'
                ),
                $existsValidator
            )
        ));
    }
    
}

?>
