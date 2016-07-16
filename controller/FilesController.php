<?php

namespace php_rest\controller;

use php_rest\interfaces\FilesIF;
use php_rest\interfaces\RequestIF;
use php_rest\interfaces\ViewIF;

class FilesController implements FilesIF
{
    private $path = 'php_rest/views';

    public function getView(RequestIF $request)
    {
        if ($request->issetParameter('view')) {
            $viewVersion = $request->getParameter('version');
            $viewName = $request->getParameter('view');
            $view = $this->loadView($viewVersion, $viewName);
            if ($view instanceof ViewIF) {
                return $view;
            }
        }
        return null;
    }

    protected function loadView($viewVersion, $viewName)
    {
        $class = str_replace("/", "\\", $this->path) . "\\{$viewName}\\{$viewVersion}\\{$viewName}View";
        $file = "{$this->path}/{$viewName}/{$viewVersion}/{$viewName}View.php";

        if (!file_exists($file) || !class_exists($class))
            return false;

        return new $class();
    }
}
