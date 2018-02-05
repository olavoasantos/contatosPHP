<?php

namespace Core\Validation;

class ValidationResponse {

  /**
   * Dados valiados
   */
  protected $data = [];

  /**
   * Erros da validação
   */
  protected $errors = [];
  
  /**
   * Satus da validação
   */
  protected $isValid = true;
  
  /**
   * fail
   * ---
   * Fala a validação
   */
  public function fail() {
    $this->isValid = false;
  }

  /**
   * pushData
   * ---
   * Armazena dado validado
   * @param  String  $field  Nome do campo
   * @param  Mixed   $value  Valor do campo
   */
  public function pushData(String $field, $value)
  {
    $this->data[$field] = $value;
  }

  /**
   * pushError
   * ---
   * Armazena erro
   * @param  String  $field    Nome do campo
   * @param  String  $message  Mensagem de erro
   */
  public function pushError(String $field, String $message)
  {
    $this->errors[$field] = $message;
  }

  /**
   * isValid
   * ---
   * Getter para o status da validação.
   * @return  Bool
   */
  public function isValid() {
    return $this->isValid;
  }

  /**
   * isValid
   * ---
   * Getter para os errors da validação.
   * @return  Array
   */
  public function errors() {
    return $this->errors;
  }

  /**
   * data
   * ---
   * Getter para os dados da validação.
   * @return  Array
   */
  public function data() {
    return $this->data;
  }

}