<?php

class ToolController extends AbstractController
{

    private $randomdelays = array(1000, 3000, 5000, 10000);
    private $effects = array(
        'scale'         => 'growl',
        'jelly'         => 'growl',
        'slide'         => 'growl',
        'genie'         => 'growl',
        'flip'          => 'attached',
        'bouncyflip'    => 'attached',
        'slidetop'      => 'bar',
        'exploader'     => 'bar',
        'thumbslider'   => 'other',
        'boxspinner'    => 'other',
    );

    public function notificationAction($product, $platform, $ext)
    {
        $this->loadModel('ProfileModel');
        $this->loadModel('MapperModel');

        $P = new ProfileModel($platform);

        $platform_settings = $P->getPlatformSettings();

        $view = new View(__DIR__ . '/../../application/views/tools/notification.phtml');
        $view->setVars(array(
            'tracker'           => $this->invokeController('TrackerController', 'toolTrackerAction', array('notification_tool', $platform)),
            'layout'            => isset($_GET['notificationeffect']) ? $this->effects[$_GET['notificationeffect']] : 'growl',
            'effect'            => isset($_GET['notificationeffect']) ? $_GET['notificationeffect'] : 'scale',
            'type'              => isset($_GET['notificationtype']) ? $_GET['notificationtype'] : 'notice',
            'position'          => isset($_GET['notificationposition']) ? $_GET['notificationposition'] : 'topleft',
            'message'           => isset($_GET['noficationtext']) ? urldecode($_GET['noficationtext']) : '',
            'link'              => isset($_GET['noficationlinktext']) && !empty($_GET['noficationlinktext']) ? $_GET['noficationlinktext'] : false,
            'ttl'               => isset($_GET['notificationttl']) ? ($_GET['notificationttl'] * 1000) : '10000',
            'delay'             => isset($_GET['notificationdelay']) && is_numeric($_GET['notificationdelay']) ? ($_GET['notificationdelay'] * 1000) : $this->randomdelays[rand(0,3)],
            'hideImage'         => isset($_GET['notificationhideimage']) ? $_GET['notificationhideimage'] : '0',
            'foreground'        => isset($_GET['foreground']) ? MapperModel::hex2rgb($_GET['foreground']) : array(255, 255, 255),
            'background'        => isset($_GET['background']) ? MapperModel::hex2rgb($_GET['background']) : array(91, 192, 222),
            //Partner data
            'p'                 => isset($_GET['p']) ? $_GET['p'] : $platform_settings['p'],
            'pi'                => isset($_GET['pi']) ? $_GET['pi'] : '',
            'whitelabel'        => isset($_GET['whitelabel']) ? rtrim($_GET['whitelabel'], '/') : rtrim($platform_settings['whitelabel_url'], '/'),

            'example'           => isset($_GET['example']) ? 'true' : '',
            'platform_settings' => $P->getPlatformSettings(),
            'platform'          => $platform,
        ));

        if ($ext === 'js') {
            return $view->parseJS();
        }
        return $view->parse();

    }

    public function popupAction($product, $platform, $ext)
    {

        die($ext);

        $this->loadModel('ProfileModel');
        $this->loadModel('MapperModel');

        $P = new ProfileModel($platform);

        $platform_settings = $P->getPlatformSettings();

        //Use only Country when there are no profiles found in a city with IP2Location
        $profiles = $P->get($_GET);
        if (!count($profiles)) {
            $profiles = $P->get($platform_settings['fallback_filter']($_GET));
        }

        //Shuffle and then slice array to get desired amount of random profiles
        shuffle($profiles);

        $profile_count = isset($_GET['notificationprofilecount']) ? intval($_GET['notificationprofilecount']) : 1;
        $profiles = array_slice($profiles, 0 - $profile_count);

        //JSON encode the array for Javascript
        $profiles = json_encode($profiles);



        $view = new View(__DIR__ . '/../../application/views/tools/notification.phtml');
        $view->setVars(array(
            'tracker'           => $this->invokeController('TrackerController', 'toolTrackerAction', array('notification_tool', $platform)),
            'layout'            => isset($_GET['notificationeffect']) ? $this->effects[$_GET['notificationeffect']] : 'growl',
            'effect'            => isset($_GET['notificationeffect']) ? $_GET['notificationeffect'] : 'scale',
            'type'              => isset($_GET['notificationtype']) ? $_GET['notificationtype'] : 'notice',
            'position'          => isset($_GET['notificationposition']) ? $_GET['notificationposition'] : 'topleft',
            'message'           => isset($_GET['noficationtext']) ? urldecode($_GET['noficationtext']) : '',
            'link'              => isset($_GET['noficationlinktext']) && !empty($_GET['noficationlinktext']) ? $_GET['noficationlinktext'] : false,
            'ttl'               => isset($_GET['notificationttl']) ? ($_GET['notificationttl'] * 1000) : '10000',
            'delay'             => isset($_GET['notificationdelay']) && is_numeric($_GET['notificationdelay']) ? ($_GET['notificationdelay'] * 1000) : $this->randomdelays[rand(0,3)],
            'hideImage'         => isset($_GET['notificationhideimage']) ? $_GET['notificationhideimage'] : '0',
            'foreground'        => isset($_GET['foreground']) ? MapperModel::hex2rgb($_GET['foreground']) : array(255, 255, 255),
            'background'        => isset($_GET['background']) ? MapperModel::hex2rgb($_GET['background']) : array(91, 192, 222),
            //Partner data
            'p'                 => isset($_GET['p']) ? $_GET['p'] : $platform_settings['p'],
            'pi'                => isset($_GET['pi']) ? $_GET['pi'] : '',
            'whitelabel'        => isset($_GET['whitelabel']) ? rtrim($_GET['whitelabel'], '/') : rtrim($platform_settings['whitelabel_url'], '/'),

            'example'           => isset($_GET['example']) ? 'true' : '',
            'platform_settings' => $P->getPlatformSettings(),
            'platform'          => $platform,
            'profiles'          => $profiles,
        ));
        if ($ext === 'js') {
            return $view->parseJS();
        } else {
            return $view->parse();
        }
    }



} 