<?php

require '../core/App/Kernell.php';
require '../core/App/App.php';

/**
 * Inicializa o container do App
 */
use Core\App\App;
function app($service, $value=null) {
  if(is_null($value)) return App::get($service);
  
  return App::register($service, $value);
}

/** Inicializa o núcleo do App */
$kernell = new Core\App\Kernell;
$kernell->autoload()          // Inclui classes
        ->registerClasses();  // Registra classes no container
