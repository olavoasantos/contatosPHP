<?php

namespace Core\Validation\Rules;

use Core\Validation\Contracts\Rule;

class Required extends Rule {


  /**
   * message
   * ---
   * Retorna a mensagem de erro.
   * @param  Array  $request  Valores enviados
   * @param  Array  $options  Opções passadas para a regra
   * @return  String  Mensagem de erro
   */
  protected static function message(Array $request, Array $options) {
    return "O campo {$options['field']} é obrigatório.";
  }

  /**
   * validate
   * ---
   * Verifica se o campo está presente.
   * @param  Array  $request  Valores enviados
   * @param  Array  $options  Opções passadas para a regra
   * @return  Array  Array com dados do teste
   */
  public static function validate(Array $request, Array $options)
  {
    if(
      array_key_exists($options["field"], $request)
      && !is_null($options["field"])
      && !empty($options["field"])
      && $options["field"] !== ""
    ) {
      return self::pass($request, $options);
    }

    return self::fail($request, $options);
  }
}