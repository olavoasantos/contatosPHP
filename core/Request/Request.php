<?php

namespace Core\Request;

class Request {

  /**
   * URI do request
   */
  public $uri;
  
  /**
   * Método do request
   */
  public $method;
  
  /**
   * Dados contidos no request
   */
  protected $data = [];
  
  /**
   * Objeto global $_SERVER
   */
  protected $request;

  /**
   * Métodos aceitos pelo App
   */
  protected $methods = [
    "GET", "POST",
    "DELETE", "PATCH", "PUT"
  ];

  /**
   * __construct
   * ---
   * Retorna uma nova instância do Request
   */
  public function __construct()
  {
    $this->request = $_SERVER;
    $this->init();
  }

  /**
   * init
   * ---
   * Rotina de inicialização da classe Request
   */
  protected function init() {
    $this->mapRequest();
    $this->mapJson();
    $this->getUri();
    $this->getMethod();
    $this->checkSpecialMethods();
  }
  
  /**
   * getUri
   * ---
   * Pega o URI do request e normaliza a rota.
   */
  protected function getUri()
  {
    $uri = $this->request['REQUEST_URI'];
    $prefix = config('router', 'base_uri');
    if (substr($uri, 0, strlen($prefix)) == $prefix) {
      $uri = substr($uri, strlen($prefix));
    }

    $this->uri = rtrim($uri, "/");
  }
  
  /**
   * getMethod
   * ---
   * Pega o método do request e checa se é um método
   * especial (PUT, PATCH ou DELETE).
   */
  protected function getMethod()
  {
    $this->method = strtoupper($this->request['REQUEST_METHOD']);
    $this->checkSpecialMethods();
  }

  /**
   * checkSpecialMethods
   * ---
   * Checa se o request é um método especial
   * (PUT, PATCH ou DELETE).
   */
  protected function checkSpecialMethods()
  {
    if($this->request['REQUEST_METHOD'] === "POST" && isset($this->data["_method"])) {
      if(in_array($this->data["_method"], $this->methods)) {
        $this->method = $this->data["_method"];
        unset($this->data["_method"]);
        return;
      }
    
      abort(405, 'Método inválido');
    }
  }

  /**
   * mapRequest
   * ---
   * Mapeia os dados enviados no request.
   */
  protected function mapRequest() {
    foreach($_REQUEST as $key => $value) {
      $this->data[$key] = $value;
    }
  }

  /**
   * mapJson
   * ---
   * Mapeia os dados enviados no request por JSON.
   */
  protected function mapJson() {
    $post = json_decode(file_get_contents('php://input'), true);
    if(is_null($post)) return;
    foreach($post as $key => $value) {
      $this->data[$key] = $value;
    }
  }

  /**
   * data
   * ---
   * Accessor para os dados enviados no request
   */
  public function data()
  {
    return $this->data;
  }

}