<?php

namespace Application\Controllers;

use System\Core\RHMVC\AbstractController;
use System\Core\RHMVC\View;

class BlogController extends AbstractController
{

    public function indexAction()
    {
        /* @var $Article Article */
        $Article = $this->loadModel('Article');
        $view = new View('blog/index.phtml');
        $view->setVars([
            'articles' => $Article->find('all', ['limit' => 5])
        ]);

        return $view->parse();
    }

    public function adminAction($handler)
    {
        if ($handler == 'articles') {
            return $this->adminArticleAction();
        } else if ($handler == 'categories') {

        } elseif ($handler == 'comments') {

        } else {
            return $this->adminArticleAction();
        }
    }

    private function adminArticleAction()
    {
        /* @var $Article Article */
        $Article = $this->loadModel('Article');
        $view = new View('blog/admin.phtml');
        $view->setVars([
            'articles' => $Article->find('all')
        ]);

        return $view->parse();
    }

    public function showArticleAction($article_id)
    {
        /* @var $Article Article */
        $Article = $this->loadModel('Article');
        $view = new View('blog/single.phtml');

        $article = $Article->find($article_id);

        $view->setVars([
            'article' => $article,
            'comments' => $article->allow_comments ? $this->invokeController('CommentController', 'indexAction', [$article_id]) : null,
            'commentform' => $article->allow_comments ? $this->invokeController('CommentController', 'showCommentFormAction', [$article_id]) : null,
        ]);

        return $view->parse();
    }

    public function showCategoryMenuAction()
    {
        /* @var $Category Category */
        $Category = $this->loadModel('Category');
        $view = new View('blog/category_menu.phtml');
        $view->setVars([
            'categories' => $Category->find('all')
        ]);

        return $view->parse();
    }

    public function showArchiveMenuAction()
    {
        /* @var $Article Article */
        $Article = $this->loadModel('Article');
        $view = new View('blog/category_menu.phtml');
        $view->setVars([
            'archivedates' => $Article->find_by_sql('SELECT publish_date, MONTH(publish_date) AS `month` FROM articles ORDER BY publish_date DESC GROUP BY month')
        ]);

        return $view->parse();
    }

    public function showAdminMenuAction()
    {
        $items = $this->getConfig('adminmenu')['blog'];
        $view = new View('blog/admin_menu.phtml');
        $view->setVars([
            'items' => $items
        ]);

        return $view->parse();
    }

}
