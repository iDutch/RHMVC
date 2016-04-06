<?php

class PageController extends AbstractController
{

    public function getPage() {
        /**
         * @var $PageModel PageModel
         */
        $PageModel = $this->loadModel('PageModel');
        if ($page = $PageModel->getPage()) {
            return $this->dispatch($page);
        } else {
            header('Location: /404');
        }
    }


    private function dispatch($page)
    {
        $template_vars = array();
        //Loop through route info
        foreach ($page->sections as $segment => $controllers) {
            if (is_array($controllers)) {
                $template_vars[$segment] = null;
                //Loop through controllers for each segment
                foreach ($controllers as $k => $controller) {
                    //Append content returned from controller to segment
                    $template_vars[$segment] .= $this->invokeController($controller->controller, $controller->action, (array) json_decode($controller->action_arguments));
                }
            }
        }
        $this->loadLayout($page->path);

        //Each layout should use these 3 vars
        $template_vars['title'] = $page->title;
        $template_vars['description'] = $page->description;
        $template_vars['keywords'] = $page->keywords;

        $this->layout->setVars($template_vars);

        return $this->layout->render();
    }

    private function loadLayout($layout)
    {
        $layout_file = __DIR__ . '/../layout/' . $layout;
        if (file_exists($layout_file)) {
            $this->layout = new View($layout_file);
        } else {
            throw new Exception('PageController error: File: \'' . $layout_file . '\' does not exists!');
        }
    }

}