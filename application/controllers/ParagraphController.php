<?php

class ParagraphController extends AbstractController
{

    public function getContentById($id)
    {
        $ContentModel = $this->loadModel('ContentModel');

        $view = new View(__DIR__ . '/../../application/views/content/show.phtml');
        $view->setVars(array(
            'content' => $ContentModel->getContentById($id),
        ));

        return $view->parse();
    }



}