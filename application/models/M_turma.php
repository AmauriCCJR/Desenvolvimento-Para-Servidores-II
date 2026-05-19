<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_turma extends CI_Model
{
   public function inserir($codigo, $descricao, $capacidade, $dataInicio)
{
    $retornoConsulta = $this->consultaTurma($codigo);

    try {
        if ($retornoConsulta['codigo'] != 9 && $retornoConsulta['codigo'] != 10) {
            $this->db->query("INSERT INTO tbl_turma (codigo, descricao, capacidade, dataInicio)
                VALUES ($codigo, '$descricao', $capacidade, '$dataInicio')");

            if ($this->db->affected_rows() > 0) {
                $dados = array('codigo' => 1, 'msg' => 'Turma cadastrada corretamente');
            } else {
                $dados = array('codigo' => 9, 'msg' => 'Houve algum problema na insercao da turma');
            }
        } else {
            $dados = array(
                'codigo' => $retornoConsulta['codigo'],
                'msg'    => $retornoConsulta['msg']
            );
        }
    } catch (Exception $e) {
        $dados = array(
            'codigo' => 0,
            'msg'    => 'Erro: ' . $e->getMessage()
        );
    }

    return $dados;
}

    private function consultaTurma($codigo)
{
    try {
        $sql = "SELECT * FROM tbl_turma WHERE codigo = $codigo";
        $retorno = $this->db->query($sql);

        if ($retorno->num_rows() > 0) {
            $linha = $retorno->row();

            if (trim($linha->estatus) == 'D') {
                $dados = array(
                    'codigo' => 9,
                    'msg'    => 'Turma desativada no sistema'
                );
            } else {
                $dados = array(
                    'codigo' => 10,
                    'msg'    => 'Turma ja cadastrada no sistema'
                );
            }
        } else {
            $dados = array(
                'codigo' => 98,
                'msg'    => 'Turma nao encontrada'
            );
        }
    } catch (Exception $e) {
        $dados = array(
            'codigo' => 0,
            'msg'    => 'Erro: ' . $e->getMessage()
        );
    }

    return $dados;
}
    public function consultar($codigo, $descricao, $capacidade, $dataInicio)

    {
        
        try {
            $sql = "SELECT codigo, descricao, capacidade, dataInicio, date_format(dataInicio, '%d/%m/%Y') as dataInicioObra FROM tbl_turma WHERE estatus = '' ";

            if (trim($codigo) != '') {
                $sql .= " AND codigo = $codigo";
            }
            if (trim($descricao) != '') {
                $sql .= " AND descricao LIKE '%$descricao%'";
            }
            if (trim($capacidade) != '') {
                $sql .= " AND capacidade = $capacidade";
            }
            if (trim($dataInicio) != '') {
                $sql .= " AND dataInicio = '$dataInicio'";
            }

            $retorno = $this->db->query($sql);
            if ($retorno->num_rows() > 0) {
                $dados = array(
                    'codigo' => 1,
                    'msg' => 'Consulta efetuada com sucesso',
                    'dados' => $retorno->result()
                );
            } else {
                
                $dados = array(
                    'codigo' => 11,
                    'msg' => 'Turma não encontrada'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 0,
                'msg' => 'Atenção: O seguinte erro aconteceu -> ' . $e->getMessage()
            );
        }

        return $dados;
    }

    public function alterar($codigo, $descricao, $capacidade, $dataInicio)
    {
        try {
            $retornoConsulta = $this->consultarTurmaCod($codigo);

            if ($retornoConsulta['codigo'] == 10) {
                $query = "UPDATE tbl_turma SET ";
                $updates = [];

                if ($descricao != '') {
                    $updates[] = "descricao = '$descricao'";
                }
                if ($capacidade != '') {
                    $updates[] = "capacidade = $capacidade";
                }
                if ($dataInicio != '') {
                    $updates[] = "dataInicio = '$dataInicio'";
                }

                $query .= implode(', ', $updates) . " WHERE codigo = $codigo";


                $params = [];

                if ($descricao != '') {
                    $params[] = $descricao;
                }
                if ($capacidade != '') {
                    $params[] = $capacidade;
                }
                if ($dataInicio != '') {
                    $params[] = $dataInicio;
                }

                $params[] = $codigo;

                $this->db->query($query, $params);

                if ($this->db->affected_rows() > 0) {
                    $dados = array(
                        'codigo' => 1,
                        'msg' => 'Turma alterada corretamente'
                    );
                } else {
                    $dados = array(
                        'codigo' => 8,
                        'msg' => 'Houve algum problema na atualização da tabela da turma'
                    );
                }
            } else {
                $dados = array(
                    'codigo' => 5,
                    'msg' => 'Turma não cadastrada no sistema'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 0,
                'msg' => 'Atenção: O seguinte erro aconteceu -> ' . $e->getMessage()
            );
        }
        return $dados;
    }

    private function consultarTurmaCod($codigo)
    {
        try {
            $sql = "SELECT * FROM tbl_turma WHERE codigo = $codigo";
            $retornoTurma = $this->db->query($sql);

            if ($retornoTurma->num_rows() > 0) {
                $linha = $retornoTurma->row();
                if (trim($linha->estatus) == 'D'){
                    $dados = array(
                        'codigo' => 9,
                        'msg' => 'Turma desativada, caso precise reativá-la, fale com o adm'
                    );
                } else {
                    $dados = array(
                        'codigo' => 10,
                        'msg' => 'Consulta efetuada com sucesso'
                    );
                }
            } else {
                $dados = array(
                    'codigo' => 12, 'msg' => 'Turma não encontrada'
                );
            }
        } catch (Exception $e) {
            return array(
                'codigo' => 0,
                'msg' => 'Atenção: O seguinte erro aconteceu -> ' . $e->getMessage()
            );
        }
        return $dados;
    }

    public function consultarTurmaCodPublic($codigo){
        return $this->consultarTurmaCod($codigo);
    }

    public function desativar($codigo){
        try {
            $retornoConsulta = $this->consultarTurmaCod($codigo);

            if ($retornoConsulta['codigo'] == 10) {
                $this->db->query("UPDATE tbl_turma SET estatus = 'D' WHERE codigo = $codigo");

                if ($this->db->affected_rows() > 0) {
                    $dados = array(
                        'codigo' => 1,
                        'msg' => 'Turma desativada corretamente!'
                    );
                } else {
                    $dados = array(
                        'codigo' => 8,
                        'msg' => 'Houve algum problema na desativação da Turma!'
                    );
                }
            } else {
                $dados = array(
                    'codigo' => $retornoConsulta['codigo'],
                    'msg' => $retornoConsulta['msg']
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 0,
                'msg' => 'Atenção: O seguinte erro aconteceu -> ' . $e->getMessage()
            );
        }
        return $dados;



    }
}
