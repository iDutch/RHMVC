<?php

namespace Application\Controllers;

use System\Core\RHMVC\AbstractController;
use System\Core\RHMVC\View;
use Application\Models\Comment;

class CommentController extends AbstractController
{

    /**
     *
     * @param type $article_id
     * @return type
     */
    public function indexAction($article_id)
    {
        $Comment = new Comment();
        $view = new View('comment/index.phtml');
        $view->setVars([
            'comments' => $Comment->find('all', ['limit' => 10, 'conditions' => ['article_id = ?', $article_id], 'order' => 'post_date desc'])
        ]);

        return $view->parse();
    }

    /**
     *
     * @param type $article_id
     * @return type
     */
    public function showCommentFormAction($article_id)
    {
        $Comment = new Comment();
        if (isset($_POST['add_comment'])) {
            //var_dump($_POST); exit;
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
                $this->redirect('/article/' . $article_id);
            }
        }

        $view = new View('comment/form.phtml');
        $view->setVars([
            'comment' => $Comment,
        ]);

        return $view->parse();
    }

}
