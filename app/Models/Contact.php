<?php

namespace App\Models;

use Core\Database\Model;

class Contact extends Model {
  
  /**
   * Tabela relacionada ao modelo
   */
  protected $table = 'contacts';

  /**
   * Regras de validação do modelo
   */
  protected $rules = [
    "name" => "required",
    "email" => "required",
    "address" => "required",
    "phone" => "required|is_json:transform",
  ];

}
