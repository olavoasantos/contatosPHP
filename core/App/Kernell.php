<?php

namespace Core\App;

class Kernell {

  /**
   * autoload
   * ---
   * Carrega as classes especificadas no
   * arquivo de configurações.
   * @return  \Core\App\Kernell;
   */
  public function autoload() {
    $dirs = require __DIR__ . "/../../" . "config/autoload.php";

    foreach($dirs as $dir) {
      require_once __DIR__ . $dir;
    }

    return $this;
  }

  /**
   * registerClasses
   * ---
   * Registra classes no container.
   * @return  \Core\App\Kernell;
   */
  public function registerClasses() {
    app('env', new \Core\Helpers\DotEnv);
    app('request', new \Core\Request\Request);
    app('route', new \Core\Router\Router);
    app('validation', new \Core\Validation\Validation);

    return $this;
  }
  
}
