<?php
namespace ORMs\Entity\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
/**
 * Description of BaseListener
 *
 * @author adam
 */
class BaseListener {
    
    public function prePersist($arg) {
        if (method_exists($arg, "setCreated")) {
            $arg->setCreated(new \DateTime());
        }
        
        if (method_exists($arg, "setEdited")) {
            $arg->setEdited(new \DateTime());
        }
    }
    
    public function preUpdate($arg) {
        if (method_exists($arg, "setEdited")) {
            $arg->setEdited(new \DateTime());
        }
    }
    
}

?>
