<?php

namespace Core\Validation\Rules;

use Core\Validation\Contracts\Rule;

class Allowed extends Rule {

  /**
   * Apenas permite que esse campo seja
   * enviado para o banco de dados.
   * @param  Array  $request  Valores enviados
   * @param  Array  $options  Opções passadas para a regra
   * @return  Array  Array com dados do teste
   */
  public static function validate(Array $request, Array $options)
  {
    return self::pass($request, $options);
  }
}