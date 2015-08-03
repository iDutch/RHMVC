<?php

class ToolController extends AbstractController
{

    public function notificationAction()
    {
        $view = new View(__DIR__ . '/../../application/views/tools/notification.phtml');
    }

} 