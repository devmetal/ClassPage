<?php
namespace Zipper;

/**
 * Description of Module
 *
 * @author adam
 */
class Module {
    
    public function getConfig() {
        return include __DIR__ . '/config.php';
    }
    
    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                )
            )
        );
    }
    
}

?>
