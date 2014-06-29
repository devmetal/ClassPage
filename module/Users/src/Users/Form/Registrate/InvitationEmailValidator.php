<?php
namespace Users\Form\Registrate;

use Zend\Validator\AbstractValidator;

/**
 * Description of InvitationEmailValidator
 *
 * @author adam
 */
class InvitationEmailValidator extends AbstractValidator {
    
    const EMAIL_NOT_PASS = "email_not_pass";
    
    const REG_CODE_NULL = "reg_code_null";
    
    protected $messageTemplates = array(
        self::EMAIL_NOT_PASS => 
            "A megadott email címhez nem ez a kód tartozik!!",
        self::REG_CODE_NULL =>
            "Az email címhez tartozó meghívókód hibás!"
    );
    
    /**
     *
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $_repo;
    
    public function __construct($options = null) {
        parent::__construct($options);
        
        if (!isset($options['object_repository'])) {
            throw new \Exception("Missing obj. repo from 
                invitation email validator");
        } else {
            $this->_repo = $options['object_repository'];
        }
    }

    public function isValid($value, array $context = null) {
        if (empty($context['reg-code'])) {
            $this->error(self::REG_CODE_NULL);
            return false;
        }
        
        $regCode = $context['reg-code'];
        $email = $value;
        
        $res = $this->_repo->findOneBy(array(
            'email' => $email,
            'code' => $regCode
        ));
        
        if (!$res) {
            $this->error(self::EMAIL_NOT_PASS);
            return false;
        } else {
            return true;
        }
    }
}

?>
