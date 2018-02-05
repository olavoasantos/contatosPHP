<?php

namespace Core\Validation\Rules;

use Core\Validation\Contracts\Rule;

class isJson extends Rule {

  /**
   * validate
   * ---
   * Avalia se um capo é JSON. Se a opção "transform"
   * estiver ativada, o campo é transformado
   * em JSON.
   * @param  Array  $request  Valores enviados
   * @param  Array  $options  Opções passadas para a regra
   * @return  Array  Array com dados do teste
   */
  public static function validate(Array $request, Array $options)
  {
    $value = $request[$options["field"]];

    if(array_key_exists("transform", $options) && !self::isJson($value)) {
      $value = self::transform($value);
      $request[$options["field"]] = $value;

      return self::pass($request, $options);
    }

    if(self::isJson($value)) {
      return self::pass($request, $options);
    }

    return self::fail($request, $options);
  }

  /**
   * isJson
   * ---
   * Checa se uma constante é JSON.
   * @param  Mixed  $value
   * @return  Bool
   */
  public static function isJson($value)
  {
    return is_string($value) && ( is_object(json_decode($value) ) || is_array(json_decode($value)) );
  }

  /**
   * transform
   * ---
   * Converte uma constante em JSON.
   * @param  Mixed
   * @return  String  Constante no formato JSON
   */
  public static function transform($data)
  {
    return json_encode($data);
  }
}