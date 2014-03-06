<?php
return array(
    'service_manager' => array(
        'factories' => array(
            'ZipperService' => function($sm) {
                $tmpsPath = './data/temps';
                return new \Zipper\ZipperService($tmpsPath);
            }
        )
    ),
)
?>