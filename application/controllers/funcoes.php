<?php 
defined('BASEPATH') OR exit ('No direct script access allowed');


class Funcoes extends CI_Controller {

public function index(){
    $this->load->view('login');
}

public function indexPagina(){
    $this->load->view('index');
}

public function encerraSistema(){
    header('Location: '.base_url());
}

public function abreSala(){
    $this->load->view('sala');
}


public function abreProfessor(){
    $this->load->view('professor');
}

}
?>