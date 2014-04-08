<?php
$env = getenv('APPLICATION_ENVIRONMENT');

$captchaKeys = array(
    'private' => '',
    'public' => ''
);

if ($env == 'development') {
    $captchaKeys['private'] = '6Lfe1O8SAAAAAN14qdeLQOgCnDEocli0A1vllJ4q';
    $captchaKeys['public'] = '6Lfe1O8SAAAAAMf4Fp6hrb6GITuG9UpOThVBTv_C';
} else {
    $captchaKeys['private'] = '6Lfe2e8SAAAAADD9GmbY2N715oge-Pl_tgrdCTvD';
    $captchaKeys['public'] = '6Lfe2e8SAAAAACcLT3z4fJwyGejQipCBUM6iNz6k';
}

return array(
    'service_manager' => array(
        'factories' => array(
            'captchaService' => function($sm) use ($captchaKeys) {
                $serv = new \ZendService\ReCaptcha\ReCaptcha();
                $serv->setPrivateKey($captchaKeys['private']);
                $serv->setPublicKey($captchaKeys['public']);
                return $serv;
            }
        )
    ),
    
    'mail_config' => array(
        'development' => array(
            'from' => 'metaladam91@gmail.com',
            'type' => 'smtp',
            'options' => array(
                'name' => 'metaladam91',
                'host' => 'smtp.gmail.com',
                'port' => '465',
                'connection_class' => 'login',
                'connection_config' => array(
                    'ssl' => 'ssl',
                    'username' => 'metaladam91@gmail.com',
                    'password' => 'phpakiralynemapascal'
                )
            )
        ),
        'production' => array(
            'from' => 'info@elitosztaly.eu',
            'type' => 'sendmail',
            'options' => array()
        )
    )
);
