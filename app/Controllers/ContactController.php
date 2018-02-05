<?php

namespace App\Controllers;

use App\Models\Contact;

class ContactController {
  
  /**
   * store
   * ---
   * Rota para criação de um novo contato.
   */
  public function store()
  {
    $contact = Contact::hydrate(request()->data());
    $contact->validate();
    $contact->save();

    echo $contact;
  }
  
  /**
   * update
   * ---
   * Rota para atualização de um contato.
   */
  public function update()
  {
    $contact = Contact::find( request()->data()["id"] );
    $contact->assignParameters( request()->data() );
    $contact->validate();
    $contact->save();

    echo $contact;
  }

  /**
   * destroy
   * ---
   * Rota para deleção de um contato.
   */
  public function destroy()
  {
    $contact = Contact::find( request()->data()["id"] );
    $contact->delete();

    echo true;
  }

}