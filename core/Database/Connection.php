<?php

namespace Core\Database;

use PDO;

class Connection {

  /**
   * make
   * ---
   * Método para estabelecimento de uma
   * conexão com o banco de dados.
   * @return  \PDO
   */
  public static function make() {
    try {
      return new PDO(
        "mysql:host=". config('database', 'host') . ";dbname=". config('database', 'database'),
        config('database', 'username'), config('database', 'password'),
        config('database', 'options')
      );
    } catch (PDOException $e) {
      env('ENV_TYPE') === 'local'
      ? die($e->getMessage())
      : die("Não foi possível conectar.");
    }
  }

}
