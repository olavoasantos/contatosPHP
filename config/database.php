<?php

/**
 * Configurações do banco de dados
 */
return [
  "host"      => env('DB_HOST'),
  "database"  => env('DB_DATABASE'),
  "username"  => env('DB_USERNAME'),
  "password"  => env('DB_PASSWORD'),
  "options"   => [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  ]
];
