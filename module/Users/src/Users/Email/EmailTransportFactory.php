<?php
namespace Users\Email;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
/**
 * Description of EmailTransportFactory
 *
 * @author adam
 */
class EmailTransportFactory implements FactoryInterface {
    
    public function createService(ServiceLocatorInterface $serviceLocator) {
        $config = $serviceLocator->get('config');
        $mailConfig = $config['mail_config'];
        $environment = getenv('APPLICATION_ENVIRONMENT');
        $transportConfig = $mailConfig[$environment];

        $options = $transportConfig['options'];
        $type = $transportConfig['type'];
        
        if ($type == 'smtp') {
            $tOptions = new \Zend\Mail\Transport\SmtpOptions($options);
            $t = new \Zend\Mail\Transport\Smtp();
            $t->setOptions($tOptions);
            return $t;
        } else if ($type == 'sendmail') {
            return new \Zend\Mail\Transport\Sendmail();
        } else {
            throw new \Exception("Undefined type: $type");
        }
    }
    
}

?>
