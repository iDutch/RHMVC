<?php

namespace Application\Controllers;

use System\Core\RHMVC\AbstractController;
use System\Core\RHMVC\Router;
use System\Core\RHMVC\View;
use Application\Models\Article;
use Application\Models\Category;
use Application\Models\Language;
use DateTime;
use Exception;

class BlogController extends AbstractController
{

    use PermissionTrait;

    public function indexAction()
    {
        $view = new View('blog/index.phtml');
        $DateTime = new DateTime();
        $view->setVars([
            'articles' => Article::all([
                'select' => 'articles.id, articles.publish_date, article_contents.title, article_contents.content, cc.name',
                'joins' => ['article_contents', 'category', 'JOIN category_contents cc ON (cc.category_id = categories.id)'],
                'conditions' => ['articles.is_online = 1 AND article_contents.is_online = 1 AND categories.is_online = 1 AND article_contents.language_id = ? AND cc.language_id = ? AND articles.publish_date <= ?', $_SESSION['language']['id'], $_SESSION['language']['id'], $DateTime->format('Y-m-d H:i:s')],
                'order' => 'articles.publish_date DESC',
                'limit' => 10
            ])
        ]);

        return $view->parse();
    }

    public function archiveAction($year, $month)
    {
        $view = new View('blog/index.phtml');
        $view->setVars([
            'articles' => Article::all([
                'select' => 'articles.id, articles.publish_date, article_contents.title, article_contents.content, cc.name',
                'joins' => ['article_contents', 'category', 'JOIN category_contents cc ON (cc.category_id = categories.id)'],
                'conditions' => ['articles.is_online = 1 AND article_contents.is_online = 1 AND categories.is_online = 1 AND article_contents.language_id = ? AND cc.language_id = ? AND YEAR(publish_date) = ? AND MONTH(publish_date) = ?', $_SESSION['language']['id'], $_SESSION['language']['id'], $year, $month]
            ])
        ]);

        return $view->parse();
    }

    public function categoryAction($category_id)
    {
        $view = new View('blog/index.phtml');
        $view->setVars([
            'articles' => Article::all([
                'select' => 'articles.id, articles.publish_date, article_contents.title, article_contents.content, cc.name',
                'joins' => ['article_contents', 'category', 'JOIN category_contents cc ON (cc.category_id = categories.id)'],
                'conditions' => ['articles.is_online = 1 AND article_contents.is_online = 1 AND categories.is_online = 1 AND article_contents.language_id = ? AND cc.language_id = ? AND articles.category_id = ?', $_SESSION['language']['id'], $_SESSION['language']['id'], $category_id]
            ])
        ]);

        return $view->parse();
    }

    public function adminAction($handler, $action = null, $item_id = null)
    {
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
        if (!$this->hasPermission(self::$ALLOW_READ)) {
            $this->redirect('/admin');
        }

        $Article = new Article();
        $view = new View('blog/admin_articles.phtml');
        $view->setVars([
            'articles' => $Article->find('all'),
            'permissions' => $this->getPermissionsArray()
        ]);

        return $view->parse();
    }

    private function adminArticleFormAction($article_id = null)
    {
        if (!is_null($article_id)) {
            if (!$this->hasPermission(self::$ALLOW_UPDATE)) {
                $this->redirect('/admin/blog/articles');
            }
            $Article = Article::find($article_id);
        } else {
            if (!$this->hasPermission(self::$ALLOW_CREATE)) {
                $this->redirect('/admin/blog/articles');
            }
            $Article = new Article();
        }

        if (isset($_POST['save_article'])) {
            if ($Article->saveThroughTransaction($_POST)) {
                $this->redirect('/admin/blog/articles');
            }
        }

        $view = new View('blog/admin_articles_form.phtml');
        $view->setVars([
            'categories' => Category::all(['conditions' => ['is_enabled = ?', 1]]),
            'languages'  => Language::all(),
            'post'       => isset($_POST['save_article']) ? $_POST : $Article->getFormData()
        ]);

        return $view->parse();
    }

    private function adminArticleDeleteAction()
    {
        if (!$this->hasPermission(self::$ALLOW_DELETE)) {
            $this->redirect('/admin/blog/articles');
        }

        $article_id = isset($_POST['item_id']) ? $_POST['item_id'] : null;
        $Article = Article::find($article_id);
        if (count($Article) === 1) {
            if (!$Article->delete()) {
                throw new Exception('Failed to delete Article with ID: ' . $Category->id, 500);
            }
            $this->redirect('/admin/blog/articles');
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

    private function adminCategoryIndexAction()
    {
        if (!$this->hasPermission(self::$ALLOW_READ)) {
            $this->redirect('/admin');
        }

        $view = new View('blog/admin_categories.phtml');
        $view->setVars([
            'categories' => Category::all(),
            'permissions' => $this->getPermissionsArray()
        ]);

        return $view->parse();
    }

    private function adminCategoryFormAction($category_id = null)
    {
        if (!is_null($category_id)) {
            if (!$this->hasPermission(self::$ALLOW_UPDATE)) {
                $this->redirect('/admin/blog/categories');
            }
            $Category = Category::find($category_id);
        } else {
            if (!$this->hasPermission(self::$ALLOW_CREATE)) {
                $this->redirect('/admin/blog/categories');
            }
            $Category = new Category();
        }

        if (isset($_POST['save_category'])) {
            if ($Category->saveThroughTransaction($_POST)) {
                $this->redirect('/admin/blog/categories');
            }
        }

        $view = new View('blog/admin_categories_form.phtml');
        $view->setVars([
            'languages' => Language::all(),
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
        $view = new View('blog/archive_menu.phtml');
        $view->setVars([
            'archivedates' => Article::all([
                'select' => 'articles.publish_date, MONTH(articles.publish_date) AS `month`, YEAR(articles.publish_date) AS `year`',
                'joins' => ['article_contents', 'category'],
                'conditions' => ['articles.is_online = 1 AND article_contents.is_online = 1 AND categories.is_online = 1 AND article_contents.language_id = ?', $_SESSION['language']['id']],
                'group' => '`year`,`month`',
                'order' => 'articles.publish_date DESC'
            ])
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
