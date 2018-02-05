<?php

namespace Core\Database;

use Core\Database\Connection;
use Core\Database\QueryBuilder;
use Core\Collection\Collection;

class Model {

  /**
   * Instância do QueryBuilder
   */
  protected $db;

  /**
   * Tabela relacionada ao modelo
   */
  protected $table;
  
  /**
   * Regras de validação
   */
  protected $rules;

  /**
   * Resposta da validação dos dados do modelo
   */
  public $isValid = true;

  /**
   * Dados do modelo
   */
  public $parameters = [];

  /**
   * Campos aceitos pelo método hydrate
   */
  protected $hydratable = ['id', 'created_at', 'updated_at'];

  /**
   * Helper para construção da query "where"
   */
  protected $getOperators = [
    "field"    =>    "id",
    "operator" =>    "=",
    "value"    =>    null
  ];
  
  /**
   * __construct
   * ---
   * Cria uma nova instância do modelo e
   * inicializa a conexão com
   * o banco de dados.
   */
  public function __construct()
  {
    $this->db = new QueryBuilder(Connection::make());
  }

  /**
   * hydrate
   * ---
   * Inicializa o modelo e associa dados
   * de uma Array ao modelo.
   * @param Array $data Dados a serem associados ao modelo
   * @return \Core\Database\Model
   */
  public static function hydrate(Array $data) {
    $model = new static;
    $model->assignParameters($data);

    return $model;
  }

  /**
   * assignParameters
   * ---
   * Associa dados de uma Array ao modelo limitando-se aos
   * campos definidos na variável "hydratable"
   * e nas regras de validação.
   * @return \Core\Database\Model
   */
  public function assignParameters(Array $data) {
    if(isset($this->rules)) {
      $fields = array_merge($this->hydratable, array_keys($this->rules));
    }

    foreach($data as $field => $value) {
      if(in_array($field, $fields)) $this->$field = $value;
    }

    return $this;
  }

  /**
   * validate
   * ---
   * Realiza a validação dos dados, filtrando os valores contidos
   * no modelos, mantendo apenas os campos definidos na
   * variável "hydratable" e nas regras de validação.
   * @return \Core\Database\Model
   */
  public function validate() {
    $response = validate($this->parameters, $this->rules);

    if($response->isValid()) {
      $this->assignParameters( $response->data() );
    }

    $this->isValid = $response->isValid();

    return $this;
  }

  /**
   * all
   * ---
   * Consulta o banco de dados e retorna uma coleção
   * contendo todos os modelos existentes na
   * tabela associada ao modelo.
   * @return \Core\Collection\Collection
   */
  public static function all() {
    $model = new static;
    
    return new Collection($model->db->all($model->table, get_class($model)));
  }

  /**
   * find
   * ---
   * Consulta o banco de dados e retorna a entrada
   * com o id especificado.
   * @return \Core\Database\Model
   */
  public static function find($id) {
    $model = new static;
    $model = $model->db->find($model->table, $id, static::class)[0];

    return $model;
  }

  /**
   * where
   * ---
   * Estrutura uma restrição para a consulta ao banco de
   * dados do tipo "WHERE $field $operator $value"
   * (e.g. WHERE id = 1).
   * @return \Core\Database\Model
   */
  public static function where($field, $operator = null, $value = null) {
    $model = new static;

    if(is_null($operator) && is_null($value)) {
      $model->getOperators["value"] = $field;
    } else if (!is_null($operator) && is_null($value)) {
      $model->getOperators["field"] = $field;
      $model->getOperators["value"] = $operator;
    } else if (!is_null($operator) && !is_null($value)) {
      $model->getOperators["field"] = $field;
      $model->getOperators["operator"] = $operator;
      $model->getOperators["value"] = $value;
    }

    return $model;
  }

  /**
   * get
   * ---
   * Consulta o banco de dados e retorna uma coleção contendo
   * os modelos que satisfazem a restrição "WHERE $field
   * $operator $value".
   * @return \Core\Collection\Collection
   */
  public function get() {
    return new Collection($this->db->get(
      $this->table,
      $this->getOperators["field"],
      $this->getOperators["operator"],
      $this->getOperators["value"],
      get_class($this)
    ));
  }

  /**
   * get
   * ---
   * Consulta o banco de dados e retorna o primeiro modelo
   * que satisfaz a restrição "WHERE $field
   * $operator $value".
   * @return \Core\Database\Model
   */
  public function first() {
    return $this->db->first(
      $this->table,
      $this->getOperators["field"],
      $this->getOperators["operator"],
      $this->getOperators["value"],
      get_class($this)
    )[0];
  }

  /**
   * save
   * ---
   * Cria ou atualiza uma entrada no banco de dados
   * com os dados contidos no modelo.
   * @return \Core\Database\Model
   */
  public function save()
  {
    if(!$this->isValid) abort(503, "Dados inválidos");

    $createdAt = (new \DateTime())->format('Y-m-d H:i:s');
    if(!array_key_exists('id', $this->parameters)) $this->parameters["created_at"] = $createdAt;
    $this->parameters["updated_at"] = $createdAt;

    $this->parameters = (!array_key_exists('id', $this->parameters))
        ? $this->db->insert($this->table, $this->parameters, get_class($this))
        : $this->db->update($this->table, $this->parameters, get_class($this));

    return $this;
  }

  /**
   * delete
   * ---
   * Deleta a entrada no banco de dados
   * associada ao modelo.
   */
  public function delete() {
    $this->db->delete(
      $this->table,
      $this->parameters['id']
    );

    $this->parameters = [];
  }

  /**
   * __toString
   * ---
   * Método mágico que retorna os parâmetros
   * do modelo na forma de JSON.
   * @return String  JSON contendo dados do modelo
   */ 
  public function __toString()
  {
    return json_encode($this->parameters);
  }
  
  /**
   * __get
   * ---
   * Método mágico para facilitar o acesso aos
   * dados do modelo (e.g. $modelo->nome).
   * @param   String  $property  Nome da propriedade
   * @return  Mixed   Dado do modelo
   */
  public function __get($property)
  {
    if (array_key_exists($property, $this->parameters)) {
      return $this->parameters[$property];
    }
  }

  /**
   * __set
   * ---
   * Método mágico para facilitar o acesso a
   * manipulação dos dados do modelo
   * (e.g. $modelo->nome = "nome").
   * @param   String  $property  Nome da propriedade
   * @param   Mixed   $value     Dado para ser associado
   */
  public function __set($property, $value)
  {
    $this->parameters[$property] = $value;
  }

}
