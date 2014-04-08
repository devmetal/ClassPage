<?php
return array(
    'service_manager' => array(
        'factories' => array(
            'ZipperService' => function($sm) {
                $config = $sm->get('config');
                $tmpsPath = $config['zipper']['temp_path'];
                return new \Zipper\ZipperService($tmpsPath);
            },
            'ArchivatorService' => function($sm) {
                $zipper = $sm->get('ZipperService');
                $config = $sm->get('config');
                $archives = $config['zipper']['archives_path'];
                return new \Zipper\Archivator\ArchivatorService($zipper, $archives);
            }
        )
    ),
)
?>