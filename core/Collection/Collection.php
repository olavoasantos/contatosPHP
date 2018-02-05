<?php

namespace Core\Collection;

class Collection {

  /**
   * Ítens da collection
   */
  public $records;

  /**
   * __construct
   * ---
   * Cria uma nova instância da classe
   */
  public function __construct(Array $array)
  {
    $this->records = $array;
  }

  /**
   * __toString
   * ---
   * Método mágico para retornar a coleção
   * e os parâmetros dos modelos
   * na forma de JSON.
   * @return {String} String de JSON
   */
  public function __toString()
  {
    $bag = [];
    foreach($this->records as $model) {
      $bag[] = $model->parameters;
    }
    return json_encode($bag);
  }

  /**
   * each
   * ---
   * Wrapper para o loop foreach para percorrer
   * os modelos contidos na coleção.
   * @param Callable $callback Função para manipulação dos modelos
   * @return \Core\Collection\Collection
   */
  public function each(Callable $callback)
  {
    foreach($this->records as $key => $model) {
      $callback($model, $key);
    }

    return $this;
  }

  /**
   * filter
   * ---
   * Wrapper para a função array_filter para
   * filtrar os modelos contidos
   * na coleção.
   * @param Callable $callback Função para manipulação dos modelos
   * @return \Core\Collection\Collection
   */
  public function filter(Callable $callback)
  {
    $this->records = array_filter($this->records, $callback);

    return $this;
  }

  /**
   * map
   * ---
   * Wrapper para a função array_map para
   * mapear os modelos contidos
   * na coleção.
   * @param Callable $callback Função para manipulação dos modelos
   * @return \Core\Collection\Collection
   */
  public function map(Callable $callback)
  {
    $this->records = array_map($callback, $this->records, array_keys($this->records));

    return $this;
  }

}
