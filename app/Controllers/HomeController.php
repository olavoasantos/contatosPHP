<?php

namespace App\Controllers;

use App\Models\Contact;

class HomeController {
  
  /**
   * index
   * ---
   * Rota principal do App.
   */
  public function index() {
    return view("index", [
      "contacts"    =>    Contact::all()
    ]);
  }
  
}