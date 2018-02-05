<?php

namespace Core\Helpers;

class DotEnv {

  /**
   * Linhas do arquivo
   */
  protected $lines;

  /**
   * Path para o arquivo
   */
  protected $filepath = "../.env";

  /**
   * Variáveis do arquivo
   */
  protected $variables = [];

  /**
   * load
   * ---
   * Carrega o arquivo .env e extrai
   * as variáveis contidas nele.
   * @return \Core\Helpers\DotEnv
   */
  public function load()
  {
    $this->validateFile()
         ->readLines()
         ->each(function ($line) {
           if($this->isNotComment($line)) {
             $this->setEnvVariable($line);
           }
         });

    return $this;
  }

  /**
   * get
   * ---
   * Busca valores de variáveis contidas
   * no arquivo .env.
   * @param  String  $name     Nome da variável
   * @param  String  $default  Valor padrão caso não exista a variável
   * @return  Mixed  Valor da variável
   */
  public function get(String $name, $default)
  {
    return $this->variables[$name] ?? $default;
  }

  /**
   * validateFile
   * ---
   * Valida o arquivo .env associado à path.
   * @return  \Core\Helpers\DotEnv
   */
  protected function validateFile()
  {
    $filepath = $this->filepath;

    if(
      !is_file($filepath)
      ||!is_readable($filepath)
      || !file_exists($filepath)
    ) {
      throw new Exception('Não foi possível ler o arquivo .env');
    }

    return $this;
  }

  /**
   * readLines
   * ---
   * Carrega linhas do arquivo .env.
   * @return  \Core\Helpers\DotEnv
   */
  protected function readLines()
  {
    $autodetect = ini_get('auto_detect_line_endings');
    ini_set('auto_detect_line_endings', '1');
    $lines = file($this->filepath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    ini_set('auto_detect_line_endings', $autodetect);

    $this->lines = $lines;

    return $this;
  }

  /**
   * isNotComment
   * ---
   * Analisa se a linha é um comentário.
   * @param  String  $line  Linha do arquivo
   * @return  Bool  true se NÃO for um comentário
   *                false se for um comentário
   */
  protected function isNotComment(String $line)
  {
    $firstChar = ltrim($line)[0];
    
    return (isset($firstChar) && $firstChar !== "#");
  }

  /**
   * setEnvVariable
   * ---
   * Define variáveis do arquivo .env
   * @param  String  $line  Linha do arquivo
   */
  protected function setEnvVariable(String $line)
  {
    list($name, $value) = $this->splitLine($line);
    $this->assignVariable($name, $value);
  }

  /**
   * splitLine
   * ---
   * Divide linha do arquivo no caractere "=".
   * @param  String  $line  Linha do arquivo
   * @return  Array  Nome e valor da variável
   */
  protected function splitLine(String $line)
  {
    return array_map('trim', explode('=', $line, 2));
  }

  /**
   * assignVariable
   * ---
   * Armazena a variável.
   * @param  String  $name   Nome da variável
   * @param  String  $value  Valor da variável
   */
  protected function assignVariable(String $name, String $value)
  {
    $this->variables[$name] = $value;
  }
  
  /**
   * each
   * ---
   * Wrapper do loop foreach para
   * tornar a função fluente.
   * @param  Callable  $callback  Função para manipulação dos dados
   * @return  \Core\Helpers\DotEnv 
   */
  protected function each (Callable $callback)
  {
    foreach($this->lines as $line) {
      $callback($line);
    }

    return $this;
  }

}