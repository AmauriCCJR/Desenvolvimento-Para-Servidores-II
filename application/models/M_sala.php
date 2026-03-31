<?php 
defined('BASEPATH') or exit ('No direct script access allowed');



class M_sala extends CI_Model {

public function inserir($codigo, $descricao, $andar, $capacidade){


    $retornoConsulta = $this->consultaSala($codigo);

    try {
    if (($retornoConsulta['codigo'] != 9) && ($retornoConsulta['codigo'] != 10)) {
        $this->db->query("insert into tbl_sala (codigo, descricao, andar, capacidade) values
        ($codigo, '$descricao', $andar, $capacidade)");

        if ($this->db->affected_rows() > 0){
            $dados = array ('codigo' => 1, 'msg' => 'Sala cadastrada corretamente');
        } else {
            $dados = array(
                'codigo' => 8,
                'msg' => 'Houve algum problema na inserção na tabela de salas' 
            );
        } 
    } 
    else {
            $dados = array('codigo' => $retornoConsulta['codigo'],
            'msg' => $retornoConsulta['msg']);
        }
    }
    catch (Exception $e){
        $dados = array(
            'codigo' => 0,
            'msg' => 'Atenção: O seguinte erro aconteceu -> '. $e->getMessage()
        );
    }
    return $dados;
}


private function consultaSala($codigo){
    try {
        $sql = "select * from tbl_sala where codigo = $codigo ";
        $retornoSala = $this->db->query($sql);


        if ($retornoSala->num_rows() > 0){
            $linha = $retornoSala->row();
            if (trim($linha->estatus) == "D"){
                $dados = array(
                    'codigo' => 9,
                    'msg' => 'Sala desativada, caso precise reativar a mesma, fale com o adm'
                );
            } else {
                $dados = array(
                    'codigo' => 10,
                    'msg' => 'Sala ja cadastrada no sistema'
                );
            }
        } else {
            $dados = array(
                'codigo' => 98,
                'msg' => 'Sala não encontrada'
            );
        }
    } catch (Exception $e){
        $dados = array(
            'codigo' => 0,
            'msg' => 'Atenção: O seguinte erro aconteceu -> '.$e->getMessage()
        );
    }
    return $dados;
}

public function consultar ($codigo, $descricao, $andar, $capacidade){

try {
    $sql = "select * from tbl_sala where estatus = '' ";

    if(trim($codigo) != ''){
        $sql = $sql. " and codigo = '$codigo' ";
    }

    if(trim($andar) != ''){
        $sql = $sql. " and andar = '$andar' ";
    }

    if (trim($descricao) != ''){
        $sql = $sql. " and descricao like %'$descricao'% ";
    }

    if (trim($capacidade)!= ''){
        $sql = $sql. " and capacidade = '$capacidade' ";
    }

    $sql = $sql. " order by codigo";

    $retorno = $this->db->query($sql);

    if ($retorno->num_rows()>0){

    $dados = array(
        'codigo' => 1,
        'msg' => 'Consulta efetuada com sucesso!',
        'dados' => $retorno->result()
    );
    } else {
        $dados = array(
            'codigo' => 11,
            'msg' => 'Sala não encontrada'
        );
    }
} catch (Exception $e){
    $dados = array(
        'codigo' => 00,
        'msg' => 'Atenção: O seguinte erro aconteceu ->' . $e->getMessage()
    );
}
return $dados;
}

};