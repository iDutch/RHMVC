<?php

class ContentController
{

    public function getContentById($id)
    {
        $view = new View(__DIR__ . '/../../application/views/content/show.phtml');
        $view->setVars(array(
            'data' => $id,
        ));

        return $view->parse();
    }

}