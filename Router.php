<?php

class Router
{
    protected static $routes = [];
    protected static $file;
    protected static $function;
    const METHOD_GET = "GET";
    const METHOD_POST = "POST";

    private static function addRoute($path, $action, $method)
    {
        self::$routes[$method][$path] = $action;
    }
    private static function routeExists($uri, $method)
    {
        foreach (self::$routes[$method] as $route => $action) {
            $pattern = "/^". str_replace("/", "\/", preg_replace('/{[\w-]+}/', '[\w-]+', $route)) ."$/";
            if(preg_match($pattern, $uri, $matches)) {
                return $action;
            }
        }
        throw new Exception("The requested rote {$uri} not defined as a {$method} method!");
    }
    private static function fileExists($file) 
    {
        is_file($file) ? require_once($file) :
        throw new Exception("Requested File {$file} Not Found!");
    }
    private static function funcExists($function) 
    {
        is_callable($function) ? call_user_func($function) :
        throw new Exception("Requested Function {$function} Not Found!");
    }
    private static function call($entity, $param)
    {
        try {
            call_user_func(["Router" ,$entity], $param);
        } 
        catch (Exception $e) {
            echo $e->getMessage();
            die();
        }
    }
    private static function handleFile($action)
    {
        if(preg_match("/^[^@]+@[^@]+$/", $action)) {
            $exploded = explode("@", $action);
            $file = $exploded[0];
            $function = $exploded[1];
            self::call("fileExists", $file);
            self::call("funcExists", $function);
        }
        else{
            self::call("fileExists", $action);
        }
    }
    public static function get($path, $action)
    {
        self::addRoute($path, $action, self::METHOD_GET);
    }
    public static function post($path, $action)
    {
        self::addRoute($path, $action, self::METHOD_POST);
    }
    public static function routes()
    {
        echo "<pre>";
        print_r(self::$routes);
        echo "</pre>";
        die();
    }

    public static function dispatch()
    {
        $uri = $_SERVER["REQUEST_URI"];
        $method = $_SERVER["REQUEST_METHOD"];
        try{
            $action = self::routeExists($uri, $method);
            if (is_callable($action)) {
                call_user_func($action);
            }
            elseif(is_string($action)) {
                self::handleFile($action);
            }
            else{
                die("Invalid Argument");
            }
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }
}
