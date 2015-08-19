<?php
/**
 * Created by PhpStorm.
 * User: richard
 * Date: 8/19/15
 * Time: 11:25 AM
 */

class APIController extends AbstractController
{

    public function __construct($layout)
    {
        $this->layout = $layout;
        $this->loadModel('ProfileModel');
        $this->loadModel('MapperModel');
    }

    public function getProfilesAction($platform, $count, $format)
    {
        $P = new ProfileModel($platform);

        $platform_settings = $P->getPlatformSettings();

        //Use only Country when there are no profiles found in a city with IP2Location
        $profiles = $P->get($_GET);
        if (!count($profiles)) {
            $profiles = $P->get($platform_settings['fallback_filter']($_GET));
        }

        //Shuffle and then slice array to get desired amount of random profiles
        shuffle($profiles);
        $profiles = array_slice($profiles, 0 - $count);

        if ($format === 'json') {
            return json_encode($profiles);
        } else {
            return 'XML not implemented yet.';
        }


    }


} 