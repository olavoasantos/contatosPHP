<?php
/**
 * Inicialização do App
 */
require "../bootstrap/bootstrap.php";

/**
 * Carregamento das rotas e direcionamento do acesso
 */
route()->load('routes/routes.php')
       ->direct(
        request()->method,
        request()->uri
       );
