<?php
namespace Home\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\InputFilter\InputFilter;

/**
 * Description of Upload
 *
 * @author adam
 */
class Upload extends Form {
    
    public function __construct() {
        parent::__construct("create-item");
        $this->setAttribute("method", "post");
        $this->setAttribute("action", "/items/upload");
        
        $this->_addElements();
        $this->_addFilters();
    }
    
    private function _addElements() {
        $file = new Element\File('document');
        $file->setAttribute('id', 'document')
                ->setAttribute('class', 'upload-element');
        $this->add($file);
        
        $submit = new Element\Submit('submit');
        $submit->setAttribute('class', 'btn btn-default')
                ->setValue("Feltöltés");
        $this->add($submit);
    }
    
    private function _addFilters() {
        $inputFilter = new InputFilter();
        
        $fileInput = new \Zend\InputFilter\FileInput('document');
        $fileInput->setRequired(true);
        $fileInput->getFilterChain()
                ->attachByName(
                    'filerenameupload', 
                    array(
                        'target' => './data/uploads/',
                        'randomize' => true,
                        'use_upload_name' => true
                    )
                );
        
        $fileInput->getValidatorChain()
                ->attachByName(
                    'fileextension', 
                    array(
                        'extension' => array('doc', 'docx', 'pdf', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'),
                        'messages' => array(
                            \Zend\Validator\File\Extension::FALSE_EXTENSION => 
                                "Hibás fájlkiterjesztés! Csak doc, docx, pdf, xls, xlsx, ppt, pptx és
                                    txt fájlokat tölthetsz fel. Javasoljuk a doc vagy a pdf használatát.",
                            \Zend\Validator\File\Extension::NOT_FOUND => 
                                "Hibás fájlkiterjesztés! Csak doc, docx, pdf, xls, xlsx, ppt, pptx és
                                    txt fájlokat tölthetsz fel. Javasoljuk a doc vagy a pdf használatát."
                        )
                    )
                )
                ->attachByName(
                    'filesize', 
                    array(
                        'max' => '10MB',
                        'messages' => array(
                            \Zend\Validator\File\Size::TOO_BIG => 
                                "A feltölteni kívánt fájl túl nagy! 
                                    10 megabájt a felső határ."
                        )
                    )
                );
        
        $inputFilter->add($fileInput);
        $this->setInputFilter($inputFilter);
    }
    
}

?>
