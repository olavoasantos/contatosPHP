<?php

namespace Core\Database;

use PDO;
use PDOStatement;
use PDOException;

class QueryBuilder {

  /**
   * Conexão com o banco de dados
   */
  protected $connection;

  /**
   * __construct
   * ---
   * Cria uma nova instância do QueryBuilder.
   * Requer uma conexão PDO.
   * @param  PDO  $connection  Conexão com o banco de dados
   */
  public function __construct(PDO $connection)
  {
    $this->connection = $connection;
  }

  /**
   * all
   * ---
   * Consulta o banco de dados e obtém todos
   * os registros de uma tabela.
   * @param  String $table  Nome da tabela
   * @param  String $class  Namespace do objeto que os registros devem ser associados
   * @return  Array  Array contendo objetos com os dados do banco de dados
   */
  public function all(String $table, String $class = null)
  {
    $query = "SELECT * FROM %s";

    $statement = $this->execute(sprintf($query, $table), $class);

    return isset($class) ? $statement->fetchAll(PDO::FETCH_CLASS, $class) : $statement->fetchAll(PDO::FETCH_OBJ);
  }

  /**
   * get
   * ---
   * Consulta o banco de dados e obtém todos os registros
   * de uma tabela que satisfazem a restrição
   * "WHERE $field $operator $value".
   * @param  String $table     Nome da tabela
   * @param  String $field     Nome do campo
   * @param  String $operator  Operador lógico
   * @param  Mixed  $value     Valor limitante
   * @param  String $class     Namespace do objeto que os registros devem ser associados
   * @return  Array  Array contendo objetos com os dados do banco de dados
   */
  public function get(String $table, String $field, String $operator, $value, String $class = null)
  {
    $query = "SELECT * FROM %s WHERE %s %s %s";
    $value = is_string($value) ? "\"{$value}\"" : $value;

    $statement = $this->execute(sprintf($query, $table, $field, $operator, $value), $class);

    return isset($class) ? $statement->fetchAll(PDO::FETCH_CLASS, $class) : $statement->fetchAll(PDO::FETCH_OBJ);
  }

  /**
   * first
   * ---
   * Consulta o banco de dados e obtém o primeiro registro
   * de uma tabela que satisfaz a restrição
   * "WHERE $field $operator $value".
   * @param  String $table     Nome da tabela
   * @param  String $field     Nome do campo
   * @param  String $operator  Operador lógico
   * @param  Mixed  $value     Valor limitante
   * @param  String $class     Namespace do objeto que os registros devem ser associados
   * @return  Array  Array contendo um objeto com os dados do banco de dados
   */
  public function first(String $table, String $column, String $operator, $value, String $class = null)
  {
    $query = "SELECT * FROM %s WHERE %s %s %s";
    $value = is_string($value) ? "\"{$value}\"" : $value;

    $statement = $this->execute(sprintf($query, $table, $column, $operator, $value), $class);

    return isset($class) ? $statement->fetchAll(PDO::FETCH_CLASS, $class) : $statement->fetch(PDO::FETCH_OBJ);
  }

  /**
   * find
   * ---
   * Consulta o banco de dados e obtém um
   * registro com um id específico.
   * @param  String $table  Nome da tabela
   * @param  Mixed  $id     Nome da tabela
   * @param  String $class  Namespace do objeto que os registros devem ser associados
   * @return  Array  Array contendo um objeto com os dados do banco de dados
   */
  public function find(String $table, $id, String $class = null)
  {
    $query = "SELECT * FROM %s WHERE id = %s";

    $statement = $this->execute(sprintf($query, $table, $id), $class);

    return isset($class) ? $statement->fetchAll(PDO::FETCH_CLASS, $class) : $statement->fetch(PDO::FETCH_OBJ);
  }

  /**
   * insert
   * ---
   * Insere dados no banco de dados.
   * @param  String $table       Nome da tabela
   * @param  Array  $parameters  Dados a serem inseridos
   * @param  String $class       Namespace do objeto que os registros devem ser associados
   * @return  Object  Objeto contendo os dados inseridos banco de dados
   */
  public function insert(String $table, Array $parameters, String $class = null)
  {
    $query = "INSERT INTO %s (%s) VALUES (%s)";
    $columns = array_keys($parameters);

    $this->execute(
      sprintf(
        $query,
        $table,
        implode(", ", $columns),
        ":" . implode(", :", $columns)
      ),
      $class,
      $parameters
    );

    $parameters["id"] = $this->connection->lastInsertId();

    return $parameters;
  }

  /**
   * update
   * ---
   * Insere dados no banco de dados no
   * registro com um id específico.
   * @param  String $table       Nome da tabela
   * @param  Array  $parameters  Dados a serem inseridos
   * @param  String $class       Namespace do objeto que os registros devem ser associados
   * @return  Object  Objeto contendo os dados inseridos banco de dados
   */
  public function update(String $table, Array $parameters, String $class = null)
  {
    $query = "UPDATE %s SET %s WHERE %s";
    $columns = array_filter(array_map(function($value) {
      if($value !== "id") return sprintf("%s = :%s", $value, $value);
    }, array_keys($parameters)), function($value) {
      return !is_null($value);
    });

    $this->execute(
      sprintf(
        $query,
        $table,
        implode(', ', $columns),
        sprintf("%s = :%s", "id", "id")
      ),
      $class,
      $parameters
    );

    return $parameters;
  }

  /**
   * delete
   * ---
   * Deleta registro do banco de dados com um id específico.
   * @param  String $table  Nome da tabela
   * @param  Array  $id     id do registro
   */
  public function delete(String $table, $id)
  {
    $query = "DELETE FROM %s WHERE id = :id";

    return $this->execute(
      sprintf(
        $query,
        $table
      ),
      null,
      ["id" => $id]
    );
  }

  /**
   * execute
   * ---
   * Executa queries no banco de dados.
   * @param String  $query    Query a ser executada
   * @param String  $class    Namespace do objeto que os registros devem ser associados
   * @param Array   $options  Opções de execução do PDO
   */
  protected function execute(String $query, String $class = null, Array $options = [])
  {
    try {
      $statement = $this->connection->prepare($query);
      $statement->execute($options);

      return $statement;
    } catch(PDOException $e) {
      env('ENV_TYPE') === 'local'
      ? die($e->getMessage())
      : die("Não foi possível conectar.");
    }
  }

}