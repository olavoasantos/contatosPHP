<?php

/**
 * dd
 * ---
 * Die and dump
 */
function dd(...$args) {
  var_dump(...$args);
  die();
}

/**
 * config
 * ---
 * Pega configurações dos arquivos.
 * @param  String  $name   Nome do arquivo
 * @param  String  $value  Nome da configuração
 * @return  Mixed  Configuração
 */
function config(String $name, String $config) {
  $configs = require "../config/{$name}.php";

  return $configs[$config];
}

/**
 * abort
 * ---
 * Carrega a view de erro e termina o processo.
 * @param  Int     $code     Código do erro
 * @param  String  $message  Mensagem de erro
 */
function abort(Number $code, String $message) {
  require "../app/Views/Errors/Error.view.php";
  die();
}

/**
 * view
 * ---
 * Carega uma view, seu layout e passa
 * as variáveis para a view.
 * @param  String  $path    Path para a view
 * @param  Array   $vars    Array com as variáveis
 * @param  String  $layout  Path para o layout
 */
function view(String $path, Array $vars, String $layout = "Layout.app") {
  $path = str_replace(".", "/", $path);
  $layout = str_replace(".", "/", "$layout");

  extract($vars);

  $__view_path__ = view_path("{$path}.view.php");
  $__layout_path__ = view_path("{$layout}.view.php");

  require $__layout_path__;
}

/**
 * env
 * ---
 * Helper para acesso às variáveis do env.
 * @param  String  $name     Nome da variável
 * @param  String  $default  Valor padrão caso não exista a variável
 */
function env(String $name, $default = "") {
  return app("env")->load()->get($name, $default);
}

/**
 * route
 * ---
 * Helper para acesso ao Router.
 * @return  \Core\Router\Router
 */
function route() {
  return app("route");
}

/**
 * request
 * ---
 * Helper para acesso ao request.
 * @return  \Core\Request\Request
 */
function request() {
  return app("request");
}

/**
 * validate
 * ---
 * Helper para acesso à classe de
 * validação de inputs.
 * @param Array $values Variáveis para serem validadas
 * @param Array $rules  Lista de regras
 * @return  \Core\Validation\ValidationResponse
 */
function validate($values, $rules) {
  return app("validation")->check($values, $rules);
}

/**
 * root_path
 * ---
 * Path relativa ao root do App.
 * @param  String  $path  Path para arquivo
 * @return  String  Path relativo à root
 */
function root_path($path) {
  return __DIR__ . "/../../" . ltrim($path, "/");
}

/**
 * view_path
 * ---
 * Path relativa à pasta de views.
 * @param  String  $path  Path para arquivo
 * @return  String  Path relativo à pasta view
 */
function view_path($path) {
  return __DIR__ . "/../../app/Views/" . ltrim($path, "/");
}
