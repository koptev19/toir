<?php

class TOIR
{
    public static function &controller($controllerName)
    {
        $controllerName = "Toir" . $controllerName . "Controller";

        $filepath = $_SERVER["DOCUMENT_ROOT"] . TOIR_PATH . "includes/Controllers/" . $controllerName . ".php";
        if (file_exists($filepath)) {
            require_once $filepath;
        } else {
            die ('Controller ' . $controllerName . ' doesn`t exists');
        }

        $className = $controllerName;

        $object = new $className;

        return $object;
    }
}