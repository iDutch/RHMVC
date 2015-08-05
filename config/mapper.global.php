<?php

return array(
    'flirtplek' => array(
        'pre_filter' => array(),
        'mapper' => array(
            'request' => array(
                'geslacht'      => function ($value){return array('male' => $value != 'v', 'female' => $value != 'm',);},
                'leeftijd'      => 'age',
                'land'          => function($value) {return $value === 'NL' ? array('country' => 'Nederland') : ($value === 'BE' ? array('country' => 'Belgie') : array('country' => $value));},
                'provincie'     => function($value) {return $value === 'a' || empty($value) ? array() : array('living-area' => $value);},
                'woonplaats'    => function($value) {return $value === 'a' || empty($value) ? array() : array('city' => $value);},
            ),
            'response' => array(
                'thumb'         => 'profileimage',
                'leeftijd'      => 'age',
                'clientname'    => 'profilename',
                'woonplaats'    => function($value) {return array('city' => ucwords(strtolower($value)));},
            ),
        ),
    ),
    'islive' => array(
        'pre_filter' => array('total_pages', 'current_page'),
        'mapper' => array(
            'request' => array(
                'geslacht'      => 'where[geslacht][eq]',
                'leeftijd'      => function($value){$age = explode(',' ,$value); return array('where[leeftijd][gteq]' => $age[0], 'where[leeftijd][lteq]' => $age[1]);},
                'land'          => function($value){return $value === 'Nederland' ? array('where[land][eq]' => 'NL') : array();},
                'woonplaats'    => function($value) {return empty($value) ? array() : array('where[woonplaats][eq]' => $value);},
            ),

            'response' => array(
                'leeftijd'      => 'age',
                'modelnaam'     => function($value) {return array('profilename' => $value, 'profileimage' => 'http://images.islive.nl/snapshot/' . $value . '/99x84.jpg');},
                'woonplaats'    => function($value) {return array('city' => ucwords(strtolower($value)));},
            ),
        ),
    ),
);
