<?php

namespace php_rest\src\controller;

use php_rest\src\interfaces\FilesIF;
use php_rest\src\interfaces\RequestIF;
use php_rest\src\interfaces\ViewIF;

class FilesController implements FilesIF
{
    private $fPath = 'src/views';
    private $cPath = 'php_rest\src\views';

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
        $class = $this->cPath . "\\{$viewName}\\{$viewVersion}\\{$viewName}View";
        $file = "{$this->fPath}/{$viewName}/{$viewVersion}/{$viewName}View.php";

        $fExists = file_exists($file);
        $cExists = class_exists($class);

        if (!$fExists || !$cExists)
            return false;

        return new $class();
    }
}
