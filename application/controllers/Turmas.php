<?php

use LDAP\ResultEntry;

 defined('BASEPATH') OR exit('No direct script access allowed');


 class Turma extends CI_Controller {

/*
Validação dos tipos de retornos nas validações (Código de erro)
1  - Operação realizada no banco de dados com sucesso (Inserção, Alteração, Consulta ou Exclusão)
2  - Conteúdo passado nulo ou vazio
3  - Conteúdo zerado
4  - Conteúdo não inteiro
5  - Conteúdo não é um texto
6  - Data em formato inválido
12 - Na atualização, pelo menos um atributo deve ser passado
99 - Parâmetros passados do front não correspondem ao método
*/
 private $codigo;
 private $descricao;
 private $capacidade;
 private $dataInicio;
 private $estatus;

 public function getCodigo(){
    return $this->codigo;
 }

 public function getDescricao(){
    return $this->descricao;
 }
 public function getCapacidade (){
    Return $this->capacidade;
 }

  public function getDataInicio (){
    Return $this-> dataInicio;
 }

  public function getEstatus (){
    Return $this-> estatus;
 }






 }

?>