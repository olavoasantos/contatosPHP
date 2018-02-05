<?php

namespace Core\Validation;

use Core\Validation\ValidationResponse;

class Validation {

  /**
   * Regras de validação
   */
  protected $rules = [
    "allowed" => "Core\Validation\Rules\Allowed",
    "required" => "Core\Validation\Rules\Required",
    "is_json" => "Core\Validation\Rules\isJson",
  ];

  /**
   * check
   * ---
   * Realiza a verificação de um conjunto de parâmetro
   * de acordo com um conjunto de regras.
   * @param  Array  $parameters  Parâmetros
   * @param  Array  $rules       Regras
   * @return \Core\Validation\ValidationResponse
   */
  public function check(Array $parameters, Array $rules) {
    $response = new ValidationResponse;

    foreach($rules as $name => $ruleList) {
      foreach($this->parseRule($ruleList) as $rule) {
        $rule["options"]["field"] = $name;
        $result = $this->validate(
          $rule["name"],
          $rule["options"],
          $parameters
        );
        if($result["passed"]) {
          $response->pushData($name, $result["data"][$name]);
        } else {
          $response->fail();
          $response->pushError($name, $result["message"]);
          break;
        }
      }
    }

    return $response;
  }

  /**
   * parseRule
   * ---
   * Interpreta a string da regra de acordo com a sintaxe:
   * {regra 1}:{opções}|{regra 2}:{opções}
   * @param  String  $ruleString  String da regra
   * @return  Array  Array com a lista de regras e opções
   */
  protected function parseRule(String $ruleString)
  {
    $rules = [];
    foreach(explode("|", $ruleString) as $rule) {
      $_ = explode(":", $rule);
      $rule = $_[0];
      $options = (isset($_[1])) ? $this->parseOptions($_[1]) : [];

      $rules[] = ["name" => $rule, "options" => $options];
    }

    return $rules;
  }

  /**
   * parseOptions
   * ---
   * Interpreta a sintaxe das opções:
   * {opção 1}={valor},{nome 2}={valor}
   * @param  String  $options  String das opções
   */
  protected function parseOptions(String $options)
  {
    return array_filter(array_map(function($option) {
      $_ = explode("=", $option);

      if(isset($_[1])) {
        $optionName = $_[0];
        $optionValue = $_[1];
        
        return [$optionName => $optionValue];
      } else if ($_[0] !== "") {
        $optionName = $_[0];
        $optionValue = true;
        
        return [$optionName => $optionValue];
      }
    }, explode(",", $options)), function($value) {
      return !is_null($value);
    })[0];
  }

  /**
   * validate
   * ---
   * Aciona a validação das regras.
   * @param  String  $rule     Nome da regra
   * @param  Array   $options  Opções da rota
   * @param  Array   $value    Parâmetros
   */
  protected function validate(String $rule, Array $options, Array $parameters) {
    if(array_key_exists($rule, $this->rules)) {
      return ($this->rules[$rule])::validate($parameters, $options);
    } else {
      abort(503, "Regra de validação {$rule} não existe");
    }
  }

}
