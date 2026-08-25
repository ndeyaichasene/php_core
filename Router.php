<?php

class Router
{
    private array $routes = [];

    public function __construct()
    {
        $this->routes = [
            '/' => [
                'controller' => 'InscriptionController',
                'action' => 'dashboard'
            ],
             '/formulaire' => [
                'controller' => 'InscriptionController',
                'action' => 'saveInscription'
            ]
        ];
    }

    public function redirection(): void
    {
        
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

       
        if (!isset($this->routes[$uri])) {
            http_response_code(404);
            echo "Page introuvable";
            exit;
        }

        
        $controllerClass = $this->routes[$uri]['controller'];
        $action = $this->routes[$uri]['action'];

        
        $controllerFile = dirname(__DIR__) . "/Controller/" . $controllerClass . ".php";

        
        if (!file_exists($controllerFile)) {
            http_response_code(404);
            echo "Erreur : Le fichier du contrôleur '$controllerClass.php' est introuvable.";
            exit;
        }

        
        require_once $controllerFile;

        
        if (!class_exists($controllerClass)) {
            http_response_code(500);
            echo "Erreur : La classe '$controllerClass' est introuvable.";
            exit;
        }

        
        $controllerInstance = new $controllerClass();

        
        if (!method_exists($controllerInstance, $action)) {
            http_response_code(500);
            echo "Erreur : La méthode '$action' est introuvable dans '$controllerClass'.";
            exit;
        }

        
        $controllerInstance->$action();
    }
}