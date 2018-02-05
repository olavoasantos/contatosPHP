<?php

namespace Core\Validation\Contracts;

abstract class Rule {

  /**
   * message
   * ---
   * Retorna a mensagem de erro.
   * @param  Array  $request  Valores enviados
   * @param  Array  $options  Opções passadas para a regra
   * @return  String  Mensagem de erro
   */
  protected static function message(Array $request, Array $options) {
    return "";
  }

  /**
   * fail
   * ---
   * Falha o teste e retorna a array contendo o erro.
   * @param  Array  $request  Valores enviados
   * @param  Array  $options  Opções passadas para a regra
   * @return  Array  Array com dados do teste
   */
  protected static function fail(Array $request, Array $options) {
    return [
      "passed" => false,
      "data" => $request,
      "message" => static::message($request, $options)
    ];
  }

  /**
   * pass
   * ---
   * Passa o teste.
   * @param  Array  $request  Valores enviados
   * @param  Array  $options  Opções passadas para a regra
   * @return  Array  Array com dados do teste
   */
  protected static function pass(Array $request, Array $options) {
    return [ "passed" => true, "data" => $request ];
  }

  /**
   * validate
   * ---
   * Rotina de validação da regra.
   * @param  Array  $request  Valores enviados
   * @param  Array  $options  Opções passadas para a regra
   * @return  Array  Array com dados do teste
   */
  abstract public static function validate(Array $request, Array $options);
}