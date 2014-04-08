<?php
namespace Zipper;

/**
 * Description of ZipperService
 *
 * @author adam
 */
class ZipperService {
    
    private $_files = array();
    
    private $_tempDir;
    
    private $_dest = NULL;
    
    public function __construct($tempDir) {
        $this->_tempDir = $tempDir;
    }
    
    public function setFiles(array $files) {
        $this->_files = $files;
    }
    
    public function setDestination($dest) {
        $this->_dest = $dest;
    }
    
    public function createZip() {
        $zip = new \ZipArchive();
        $zfile = NULL;
        
        if ($this->_dest === NULL) {
            $zfile = tempnam($this->_tempDir, 'tmp');
        }
        
        $zip->open($zfile, \ZipArchive::CREATE);
        
        foreach ($this->_files as $file) {
            if (is_array($file)) {
                if (!empty($file['path']) && !empty($file['name'])) {
                    $name = $file['name'];
                    $path = $file['path'];
                    $ext = (!empty($file['ext']))?$file['ext']
                            :pathinfo($path, PATHINFO_EXTENSION);
                    $zip->addFromString($name . ".$ext", file_get_contents($path));
                } else {
                    continue;
                }
            } else if (is_file($file) && is_readable($file)) {
                $zip->addFromString(basename($file),  file_get_contents($file));
            }
        }
        $zip->close();
        
        return $zfile;
    }
    
}

?>
