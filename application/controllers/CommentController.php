<?php

use core\RHMVC\AbstractController;
use core\RHMVC\View;

class CommentController extends AbstractController {

    public function indexAction($article_id)
    {
        /* @var $Article Article */
        $Comment = $this->loadModel('Comment');
        $view = new View('comment/index.phtml');
        $view->setVars([
            'comments' => $Comment->find('all', ['limit' => 10, 'conditions' => ['article_id = ?', $article_id]])
        ]);

        return $view->parse();
    }

    public function showCommentFormAction($article_id)
    {
        /* @var $Comment Comment */
        $Comment = $this->loadModel('Comment');

        if (isset($_POST['add_comment'])) {
            $date = new \DateTime();
            if (empty($_SESSION['user']->id)) {
                $Comment->author_name = $_POST['author_name'];
                $Comment->user_id = null;
            } else {
                $Comment->author_name = null;
                $Comment->user_id = $_SESSION['user']->id;
            }
            $Comment->content = $_POST['content'];
            $Comment->article_id = $article_id;

            if ($Comment->save()) {
                header('Location: /blog/article/' . $article_id);
            }
        }

        $view = new View('comment/form.phtml');
        $view->setVars([
            'comment' => $Comment,
        ]);

        return $view->parse();
    }

}
