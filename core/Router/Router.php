<?php

namespace Core\Router;

class Router {

  /**
   * Lista de rotas registradas
   */
  protected $routes = [];

  /**
   * Namespace dos Controllers
   */
  protected $controllerNamespace = "App\\Controllers";

  /**
   * load
   * ---
   * Carrega arquivo de rotas
   * @return Core\Router\Router
   */
  public function load($routes) {
    require root_path($routes);

    return $this;
  }

  /**
   * get
   * ---
   * Registra uma nova rota com método GET.
   * @param  String  $uri               URI associado a rota
   * @param  String  $controllerString  String contendo {nome do controller}@{método}
   */
  public function get(String $uri, String $controllerString) {
    list($controller, $method) = $this->evaluateControllerString($controllerString);
    $this->register('get', rtrim($uri, "/"), $controller, $method);
  }

  /**
   * post
   * ---
   * Registra uma nova rota com método POST.
   * @param  String  $uri               URI associado a rota
   * @param  String  $controllerString  String contendo {nome do controller}@{método}
   */
  public function post(String $uri, String $controllerString) {
    list($controller, $method) = $this->evaluateControllerString($controllerString);
    $this->register('post', rtrim($uri, "/"), $controller, $method);
  }

  /**
   * delete
   * ---
   * Registra uma nova rota com método DELETE.
   * @param  String  $uri               URI associado a rota
   * @param  String  $controllerString  String contendo {nome do controller}@{método}
   */
  public function delete(String $uri, String $controllerString) {
    list($controller, $method) = $this->evaluateControllerString($controllerString);
    $this->register('delete', rtrim($uri, "/"), $controller, $method);
  }

  /**
   * put
   * ---
   * Registra uma nova rota com método PUT.
   * @param  String  $uri               URI associado a rota
   * @param  String  $controllerString  String contendo {nome do controller}@{método}
   */
  public function put(String $uri, String $controllerString) {
    list($controller, $method) = $this->evaluateControllerString($controllerString);
    $this->register('put', rtrim($uri, "/"), $controller, $method);
  }

  /**
   * patch
   * ---
   * Registra uma nova rota com método PATCH.
   * @param  String  $uri               URI associado a rota
   * @param  String  $controllerString  String contendo {nome do controller}@{método}
   */
  public function patch(String $uri, String $controllerString) {
    list($controller, $method) = $this->evaluateControllerString($controllerString);
    $this->register('patch', rtrim($uri, "/"), $controller, $method);
  }

  /**
   * register
   * ---
   * Registra uma nova rota
   * @param  String  $method            Método associado à rota
   * @param  String  $uri               URI associado a rota
   * @param  String  $controller        Controller
   * @param  String  $controllerMethod  Método
   */
  protected function register(String $method, String $uri, String $controller, String $controllerMethod) {
    $this->routes[strtoupper($method)][($uri !== "") ? $uri : "/"] = [$controller, $controllerMethod];
  }

  /**
   * evaluateControllerString
   * ---
   * Separa o nome da classe do controller e o
   * nome do método de uma $controllerString.
   * @param  String  $controllerString  String contendo {nome do controller}@{método}
   */
  protected function evaluateControllerString(String $controllerString)
  {
    return explode("@", $controllerString, 2);
  }

  /**
   * direct
   * ---
   * Direciona o acesso para a rota associado
   * ao método e ao URI.
   * @param  String  $method  Método
   * @param  String  $uri     URI
   */
  public function direct(String $method, String $uri)
  {
    $uri = (substr( $uri, 0, 1 ) === "/") ? $uri : "/{$uri}";
    list($route, $params) = $this->discoverRoute($method, $uri);
    if(!is_null($route)) {
      list($controller, $controllerMethod) = $route;
      $controllerClass = $this->instantiate($controller);
      
      return $controllerClass->{$controllerMethod}(...$params);
    }

    abort('404', 'Ooops! Página não encontrada');
  }

  /**
   * discoverRoute
   * ---
   * Compara o URI e com as rotas registradas
   * em um dado método.
   * @param  String  $method  Método
   * @param  String  $uri     URI
   * @return  Array  Array contendo os dados do Controller associado
   *                 à rota e os parâmetros do URI. Caso a rota
   *                 não exista retorna-se [null, null].
   */
  protected function discoverRoute(String $method, String $uri) {
    if(!array_key_exists($method, $this->routes)) {
      abort('405', 'Método inválido');
    }

    $parsedUri = $this->parseURI($uri);
    foreach($this->routes[$method] as $route => $data) {
      $regex = $this->prepareRegex($route);

      if(preg_match("#^{$regex}$#", $parsedUri, $params)) {
        array_shift($params);
        
        return [$data, $params];
      }
    }

    return [null, null];
  }

  /**
   * parseURI
   * ---
   * Remove a porção associada ao GET do URI
   * (e.g. /index?name='Name' ~~> /index).
   * @param  String  $uri  URI
   * @return  String  URI
   */
  protected function parseURI(String $uri)
  {
    return parse_url($uri, PHP_URL_PATH);
  }

  /**
   * prepareRegex
   * ---
   * Prepara o RegEx das rotas registradas para
   * extração de parâmetros. (e.g. /user/{id})
   * @param  String  $route  Rota
   * @return  String  RegEx da rota
   */
  protected function prepareRegex(String $route) {
    return preg_replace("/{([A-Za-z0-9\-]+)}/", "([A-Za-z0-9\-_]+)", $route);
  }

  /**
   * instatiate
   * ---
   * Instancia a classe do controller.
   * @param  String  $controller  Nome da classe do controller.
   * @return  Object  Controller
   */
  protected function instantiate(String $controller) {
    $controllerClass = "{$this->controllerNamespace}\\{$controller}";

    return new $controllerClass();
  }
  
}