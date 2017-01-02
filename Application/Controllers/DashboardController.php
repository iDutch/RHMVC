<?php

namespace Application\Controllers;

use System\Core\RHMVC\AbstractController;
use System\Core\RHMVC\View;
use Application\Models\Comment;
use Application\Models\Category;

class DashboardController extends AbstractController
{

    public function indexAction()
    {
        $view = new View('dashboard/index.phtml');
        $view->setVars([
            'latest_comments' => Comment::find('all', ['limit' => 5, 'order' => 'post_date desc'])
        ]);

        return $view->parse();
    }

    public function categoryMenuAction()
    {
        $view = new View('blog/category_menu.phtml');
        $view->setVars([
            'categories' => Category::find('all')
        ]);

        return $view->parse();
    }

}
