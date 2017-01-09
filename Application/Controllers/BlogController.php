<?php

namespace Application\Controllers;

use System\Core\RHMVC\AbstractController;
use System\Core\RHMVC\View;
use Application\Models\Article;
use Application\Models\Category;
use Application\Models\Language;
use DateTime;
use Exception;

class BlogController extends AbstractController
{

    public function indexAction()
    {
        $Article = new Article();
        $view = new View('blog/index.phtml');
        $DateTime = new DateTime();
        $view->setVars([
            'articles' => $Article->find('all', ['limit' => 7, 'order' => 'publish_date DESC', 'conditions' => ['publish_date < ? AND is_online = ?', $DateTime->format('Y-m-d H:i:s'), 1]])
        ]);

        return $view->parse();
    }

    public function archiveAction($year, $month)
    {
        $Article = new Article();
        $view = new View('blog/index.phtml');
        $view->setVars([
            'articles' => $Article->find('all', ['order' => 'publish_date DESC', 'conditions' => ['YEAR(publish_date) = ? AND MONTH(publish_date) = ? AND is_online = ?', $year, $month, 1]])
        ]);

        return $view->parse();
    }

    public function categoryAction($category_id)
    {
        $Article = new Article();
        $view = new View('blog/index.phtml');
        $view->setVars([
            'articles' => $Article->find('all', ['conditions' => ['category_id = ?', $category_id]])
        ]);

        return $view->parse();
    }

    public function adminAction($handler, $action = null, $item_id = null)
    {
        $this->sendWebSocketMessage("The admin page has been loaded...", "", "glyphicon glyphicon-warning-sign", "warning");
        if ($handler == 'articles') {
            if ($action == 'add') {
                return $this->adminArticleFormAction();
            }
            if ($action == 'edit') {
                return $this->adminArticleFormAction($item_id);
            }
            if ($action == 'delete') {
                $this->adminArticleDeleteAction();
            }
            return $this->adminArticleIndexAction();
        } else if ($handler == 'categories') {
            if ($action == 'add') {
                return $this->adminCategoryFormAction();
            }
            if ($action == 'edit') {
                return $this->adminCategoryFormAction($item_id);
            }
            if ($action == 'delete') {
                $this->adminCategoryDeleteAction();
            }
            return $this->adminCategoryIndexAction();
        } elseif ($handler == 'comments') {
            $this->redirect('/404');
        } else {
            return $this->adminArticleIndexAction();
        }
    }

    private function adminArticleIndexAction()
    {
        $Article = new Article();
        $view = new View('blog/admin_articles.phtml');
        $view->setVars([
            'articles' => $Article->find('all')
        ]);

        return $view->parse();
    }

    private function adminArticleFormAction($article_id = null)
    {
        $Category = new Category();
        $Language = new Language();
        if (!is_null($article_id)) {
            $Article = Article::find($article_id);
        } else {
            $Article = new Article();
        }

        if (isset($_POST['save_article'])) {
            if ($Article->saveThroughTransaction($_POST)) {
                $this->redirect('/admin/blog/articles/');
            }
        }

        $view = new View('blog/admin_articles_form.phtml');
        $view->setVars([
            'categories' => $Category->find('all', ['conditions' => ['is_enabled = ?', 1]]),
            'languages'  => $Language->find('all'),
            'post'       => isset($_POST['save_article']) ? $_POST : $Article->getFormData()
        ]);

        return $view->parse();
    }

    private function adminArticleDeleteAction()
    {
        $article_id = isset($_POST['item_id']) ? $_POST['item_id'] : null;
        $Article = Article::find($article_id);
        if (count($Article) && $Article->delete()) {
            $this->redirect('/admin/blog/articles');
        }
        return false;
    }

    private function adminCategoryIndexAction()
    {
        $Category = new Category();
        $view = new View('blog/admin_categories.phtml');
        $view->setVars([
            'categories' => $Category->find('all')
        ]);

        return $view->parse();
    }

    private function adminCategoryFormAction($category_id = null)
    {
        $Language = new Language();
        if (!is_null($category_id)) {
            $Category = Category::find($category_id);
        } else {
            $Category = new Category();
        }

        if (isset($_POST['save_category'])) {
            if ($Category->saveThroughTransaction($_POST)) {
                $this->redirect('/admin/blog/categories/');
            }
        }

        $view = new View('blog/admin_categories_form.phtml');
        $view->setVars([
            'languages' => $Language->find('all'),
            'post'      => isset($_POST['save_category']) ? $_POST : $Category->getFormData()
        ]);

        return $view->parse();
    }

    private function adminCategoryDeleteAction()
    {
        $category_id = isset($_POST['item_id']) ? $_POST['item_id'] : null;
        $Category = Category::find($category_id); //Can be an integer or array of integers
        if (count($Category) === 1) { //Single delete
            if (!$Category->delete()) {
                throw new Exception('Failed to delete Category with ID: ' . $Category->id, 500);
            }
            $this->redirect('/admin/blog/categories');
        } else if (count($Category) > 1) {
            foreach ($Category as $C) {
                if (!$C->delete()) {
                    throw new Exception('Failed to delete Category with ID: ' . $Category->id, 500);
                }
            }
            $this->redirect('/admin/blog/categories');
        }
        return false;
    }

    public function showArticleAction($article_id)
    {
        $Article = new Article();
        $view = new View('blog/single.phtml');
        $article = $Article->first(['conditions' => ['id = ?', $article_id]]);

        $view->setVars([
            'article'     => $article,
            'comments'    => $article->allow_comments ? $this->invokeController('CommentController', 'indexAction', [$article_id]) : null,
            'commentform' => $article->allow_comments ? $this->invokeController('CommentController', 'showCommentFormAction', [$article_id]) : null,
        ]);

        return $view->parse();
    }

    public function showCategoryMenuAction()
    {
        $Category = new Category();
        $view = new View('blog/category_menu.phtml');
        $view->setVars([
            'categories' => $Category->find('all')
        ]);

        return $view->parse();
    }

    public function showArchiveMenuAction()
    {
        $Article = new Article();
        $view = new View('blog/archive_menu.phtml');
        $view->setVars([
            'archivedates' => $Article->find_by_sql('SELECT publish_date, MONTH(publish_date) AS `month`, YEAR(publish_date) AS `year` FROM articles GROUP BY `year`,`month` ORDER BY publish_date DESC')
        ]);

        return $view->parse();
    }

    public function showAdminMenuAction($handler)
    {
        $items = $this->getConfig('adminmenu')['blog'];
        $view = new View('blog/admin_menu.phtml');
        $view->setVars([
            'items' => $items,
            'handler' => $handler
        ]);

        return $view->parse();
    }

}
