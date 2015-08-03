<?php

return array(
    'flirtplek' => array(
        'url'                   => 'http://flirtplek.nl',
        'p'                     => '99999',
        'api'                   => '/ajax/profiles.php',
        'defaultquery'          => 'action=filter&selects=photo&is-erotic=true&enable-erotic=true&limit=100',
        'api_type'              => 'flirtplek',
        'whitelabel_url'        => 'http://flirtplek.nl',
        'whitelabel_format'     => '%whitelabel%/profiel/%name%/?p=%p%&pi=%pi%',
        'cache_ttl'             => 3600,
        'fallback_filter'       => function(array $array) {return $array;},
    ),
    'relatie' => array(
        'url'                   => 'http://relatie.nl',
        'p'                     => '99999',
        'api'                   => '/ajax/profiles.php',
        'defaultquery'          => 'action=filter&selects=photo&is-erotic=false&enable-erotic=false&limit=100',
        'api_type'              => 'flirtplek',
        'whitelabel_url'        => 'http://relatie.nl',
        'whitelabel_format'     => '%whitelabel%/profiel/%name%/?p=%p%&pi=%pi%',
        'cache_ttl'             => 3600,
        'fallback_filter'       => function(array $array) {return $array;},
    ),
    'neukoproepjes' => array(
        'url'                   => 'http://neukoproepjes.nl',
        'p'                     => '99999',
        'api'                   => '/ajax/profiles.php',
        'defaultquery'          => 'action=filter&selects=photo&is-erotic=true&enable-erotic=true&limit=100',
        'api_type'              => 'flirtplek',
        'whitelabel_url'        => 'http://neukoproepjes.nl',
        'whitelabel_format'     => '%whitelabel%/profiel/%name%/?p=%p%&pi=%pi%',
        'cache_ttl'             => 3600,
        'fallback_filter'       => function(array $array) {return $array;},
    ),
    'hobbysletten' => array(
        'url'                   => 'http://hobbysletten.nl',
        'p'                     => '99999',
        'api'                   => '/ajax/profiles.php',
        'defaultquery'          => 'action=filter&selects=photo&is-erotic=true&enable-erotic=true&limit=100',
        'api_type'              => 'flirtplek',
        'whitelabel_url'        => 'http://hobbysletten.nl',
        'whitelabel_format'     => '%whitelabel%/profiel/%name%/?p=%p%&pi=%pi%',
        'cache_ttl'             => 3600,
        'fallback_filter'       => function(array $array) {return $array;},
    ),
    'islive' => array(
        'url'                   => 'http://www.islive.nl',
        'p'                     => '',
        'api'                   => '/api/',
        'defaultquery'          => 'fetch=full&where[online]=1',
        'api_type'              => 'islive',
        'whitelabel_url'        => 'http://www.islive.nl/splash1',
        'whitelabel_format'     => '%whitelabel%/?p=%p%&pi=%pi%&m=%name%',
        'cache_ttl'             => 300,
        'fallback_filter'       => function(array $array) {unset($array['woonplaats']); return $array;},
    )
);
