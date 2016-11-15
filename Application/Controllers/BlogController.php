<?php

namespace Application\Controllers;

use System\Core\RHMVC\AbstractController;
use System\Core\RHMVC\View;
use Application\Models\Article;
use Application\Models\ArticleContent;
use Application\Models\Category;
use Application\Models\CategoryContent;
use Application\Models\Language;

class BlogController extends AbstractController
{

    public function indexAction()
    {
        $Article = new Article();
        $view = new View('blog/index.phtml');
        $DateTime = new \DateTime();
        $view->setVars([
            'articles' => $Article->find('all', ['limit' => 7, 'order' => 'publish_date DESC', 'conditions' => ['publish_date < ? AND is_online = ?', $DateTime->format('Y-m-d H:i:s'), 1]])
        ]);

        return $view->parse();
    }

    public function adminAction($handler, $action = null, $article_id = null)
    {
        if ($handler == 'articles') {
            if ($action == 'add') {
                return $this->adminArticleAddAction();
            }
            if ($action == 'edit') {
                return $this->adminArticleEditAction($article_id);
            }
            return $this->adminArticleIndexAction();
        } else if ($handler == 'categories') {
            if ($action == 'add') {
                return $this->adminCategoryAddAction();
            }
            return $this->adminCategoryIndexAction();
        } elseif ($handler == 'comments') {
            $this->redirect('/404');
        } else {
            $this->redirect('/404');
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

    public function adminArticleAddAction()
    {
        $Category = new Category();
        $Language = new Language();

        if (isset($_POST['add_article'])) {
            $Article = new Article();
            if ($Article->saveThroughTransaction($_POST)) {
                $this->redirect('/admin/articles/');
            }
        }

        $view = new View('blog/admin_articles_add.phtml');
        $view->setVars([
            'categories' => $Category->find('all'),
            'languages'  => $Language->find('all'),
            'post'       => $_POST ?? [],
            'msg'        => $this->flashMessage()
        ]);

        return $view->parse();
    }

    public function adminArticleEditAction($article_id)
    {
        $Category = new Category();
        $Language = new Language();
        $Article = Article::find($article_id);

        if (isset($_POST['edit_article'])) {

            if ($Article->saveThroughTransaction($_POST)) {
                $this->redirect('/admin/articles/');
            }
        }
        $view = new View('blog/admin_articles_add.phtml');
        $view->setVars([
            'categories' => $Category->find('all'),
            'languages'  => $Language->find('all'),
            'post'       => isset($_POST['edit_article']) ? $_POST : $Article->get(),
            'msg'        => $this->flashMessage()
        ]);

        return $view->parse();
    }

    private function adminCategoryIndexAction()
    {
        /* @var $Category \Application\Models\Category */
        $Category = $this->loadModel('Category');
        $view = new View('blog/admin_categories.phtml');
        $view->setVars([
            'categories' => $Category->find('all')
        ]);

        return $view->parse();
    }

    public function adminCategoryAddAction()
    {

        $Language = new Language();

        if (isset($_POST['add_category'])) {
            $Category = new Category();
            $Category->is_enabled = false;
            $Category->is_online = false;

            foreach ($_POST as $key => $value) {
                if ($key == 'is_enabled' || $key == 'is_online') {
                    $Category->{$key} = !empty($value) ? $value : null;
                }
                if ($key == 'language') {
                    foreach ($value as $language_id => $lang_data) {
                        $CategoryContent[$language_id] = new CategoryContent();
                        foreach ($lang_data as $lang_key => $lang_value) {
                            if ($lang_key == 'name') {
                                $CategoryContent[$language_id]->{$lang_key} = $lang_value;
                            }
                        }
                        $CategoryContent[$language_id]->language_id = $language_id;
                    }
                }
            }

            $conn = $Category->connection();
            $conn->transaction();
            $C = $Category->save();

            foreach ($CategoryContent as $language_id => $value) {
                $CategoryContent[$language_id]->category_id = $Category->id;
                $CC[$language_id] = $CategoryContent[$language_id]->save();
            }


            if (!$C || in_array(false, $CC)) {
                $conn->rollback();
            } else {
                $conn->commit();
                $this->redirect('/admin/blog/categories');
            }
        }

        $view = new View('blog/admin_categories_add.phtml');
        $view->setVars([
            'category'         => $Category ?? null,
            'category_content' => $CategoryContent ?? null,
            'languages'        => $Language->find('all')
        ]);

        return $view->parse();
    }

    public function showArticleAction($article_id)
    {
        /* @var $Article \Application\Models\Article */
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
