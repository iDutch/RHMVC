<?php

class TrackerController extends AbstractController
{

    public function toolTrackerAction($tool_type, $platform)
    {
        $view = new View(__DIR__ . '/../../application/views/tracker/tool.phtml');
        $view->setVars(array(
            'tracker_domain'        => '', //TODO: Get from config
            'tracker_website_id'    => '', //TODO: Get from config
            'tool_type'             => $tool_type,
            'platform'              => $platform,
        ));

        return $view->parse();
    }

    public function bannerTrackerAction($tool_type, $platform, $resolution, $banner_nr)
    {
        $view = new View(__DIR__ . '/../../application/views/tracker/banner.phtml');
        $view->setVars(array(
            'tracker_domain'        => '', //TODO: Get from config
            'tracker_website_id'    => '', //TODO: Get from config
            'tool_type'             => $tool_type,
            'platform'              => $platform,
            'resolution'            => $resolution,
            'banner_nr'             => $banner_nr,
        ));

        return $view->parse();
    }

} 