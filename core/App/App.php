<?php

namespace Core\App;

class App {

  /**
   * Lista de serviços do container
   */
  protected static $services = [];

  /**
   * register
   * ---
   * Registra serviços no container.
   * @param String $key     Identificador de acesso do serviço
   * @param Mixed  $service Serviço
   */
  public static function register(String $key, $service){
    static::$services[$key] = $service;
  }

  /**
   * get
   * ---
   * Busca serviço no container.
   * @param String $key Identificador de acesso do serviço
   * @return  Mixed  Serviço
   */
  public static function get(String $key)
  {
    if(!array_key_exists($key, static::$services)) {
      throw new Exception("Serviço {$key} não existe.");
    }

    return static::$services[$key];
  }
  
}
