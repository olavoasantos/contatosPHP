<?php

  /**
   * Declaração de rotas
   */
  /** Rota principal do App */
  route()->get("/", "HomeController@index");
  
  /** Rotas de manipulação dos dados dos contatos */
  route()->post("/contatos", "ContactController@store");
  route()->patch("/contatos", "ContactController@update");
  route()->delete("/contatos", "ContactController@destroy");
