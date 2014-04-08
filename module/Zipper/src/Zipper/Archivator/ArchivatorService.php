<?php
namespace Zipper\Archivator;

use Zipper\ZipperService;
/**
 * Description of ArchivatorService
 *
 * @author adam
 */
class ArchivatorService {
    
    /**
     *
     * @var ZipperService
     */
    private $_zipper;
    
    /**
     *
     * @var string
     */
    private $_archPath;
    
    public function __construct(ZipperService $zipper, $archPath) {
        $this->_zipper = $zipper;
        if (is_dir($archPath)) {
            $this->_archPath = $archPath;
        } else {
            throw new \Exception(__CLASS__ . ": the archive path 
                is not a dir: $archPath");
        }
    }
    
    public function archivate($path) {
        $this->_zipper->setFiles(array($path));
        $zip = $this->_zipper->createZip();
        
        $arciveName = pathinfo($path,PATHINFO_FILENAME)
                . date('Y_m_d__H_i_s') . '.zip';
        
        $archivePath = $this->_archPath . '/'
                . $arciveName;

        $zipContent = file_get_contents($zip);
        $f = fopen($archivePath,"w");
        fwrite($f, $zipContent);
        fclose($f);

        return $archivePath;
    }
    
    public function archivateFiles(array $paths) {
        foreach ($paths as $path) {
            $this->archivate($path);
        }
    }
    
}

?>
