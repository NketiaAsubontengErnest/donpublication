<?php
/**
 * 
 * main app file
 */

 class App
 {
     protected $controller = "home";
     protected $method = "index";
     protected $params = array();
 
     public function __construct(){
        $url = $this->parseUrl();

        if (!empty($url[0])) {
            
            $controller = ucfirst($url[0]);
            
            if (file_exists("private/controllers/" . $controller . ".php")) {
                $this->controller = $controller;

                unset($url[0]);
            }
        }
        $this->controller = ucwords($this->controller);
        require "private/controllers/" . $this->controller . ".php";
         
        $this->controller = new $this->controller();
 
        if (!empty($url[1])) {
            $methodName = ucfirst($url[1]);
            if (method_exists($this->controller, $methodName)) {
                $this->method = $methodName;
                unset($url[1]);
            }
        }
 
        $this->params = array_values($url);

        call_user_func_array([$this->controller, $this->method], $this->params);
     }
 
     private function parseUrl()
     {
         $url = isset($_GET['url']) ? $_GET['url'] : "home";
         return explode("/", filter_var(trim($url, "/")), FILTER_SANITIZE_URL);
     }
 }