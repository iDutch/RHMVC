<?php

use core\RHMVC\AbstractController;
use core\RHMVC\View;

class BlogController extends AbstractController
{

    public function indexAction()
    {
        /* @var $Article Article */
        $Article = $this->loadModel('Article');
        $view = new View('blog/index.phtml');
        $view->setVars([
            'articles'  => $Article->find('all', ['limit' => 5])
        ]);

        return $view->parse();
    }

    public function articleAction($article_id)
    {
        /* @var $Article Article */
        $Article = $this->loadModel('Article');
        $view = new View('blog/single.phtml');

        $article = $Article->find($article_id);

        $view->setVars([
            'article'  => $article,
            'comments' => $article->allow_comments ? $this->invokeController('CommentController', 'indexAction', [$article_id]) : null,
            'commentform' => $article->allow_comments ? $this->invokeController('CommentController', 'showCommentFormAction', [$article_id]) : null,
        ]);

        return $view->parse();
    }

    public function categoryMenuAction()
    {
        /* @var $Category Category */
        $Category = $this->loadModel('Category');
        $view = new View('blog/category_menu.phtml');
        $view->setVars([
            'categories'  => $Category->find('all')
        ]);

        return $view->parse();
    }

}
