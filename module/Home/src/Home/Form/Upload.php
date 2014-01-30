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
class Upload extends Form{
    
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
                        'extension' => array('doc', 'docx', 'pdf', 'xls', 'xlsx', 'ppt', 'pptx', 'txt')
                    )
                )
                ->attachByName('filesize', array('max' => '10MB'));
        
        $inputFilter->add($fileInput);
        $this->setInputFilter($inputFilter);
    }
    
}

?>
