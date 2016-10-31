<?php

use core\RHMVC\AbstractController;
use core\RHMVC\View;

class CommentController extends AbstractController
{

    public function indexAction($article_id)
    {
        /* @var $Article Article */
        $Comment = $this->loadModel('Comment');
        $view = new View('comment/index.phtml');
        $view->setVars([
            'comments'  => $Comment->find('all', ['limit' => 10])
        ]);

        return $view->parse();
    }

    public function addCommentAction($article_id)
    {
        /* @var $Comment Comment */
        $Comment = $this->loadModel('Comment');

        if (isset($_POST)) {
            $date = new DateTime();
            $Comment->author_name = $_POST['author_name'];
            $Comment->content = $_POST['message'];
            $Comment->post_date = $date->format('Y-m-d H:i:s');
            $Comment->article_id = $article_id;
            $Comment->save();
        }

        $view = new View('comment/form.phtml');

        return $view->parse();
    }

}
