<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_professor extends CI_Model
{


    public function inserir($codigo, $nome, $cpf, $tipo)
    {

        try {
            $retornoConsulta = $this->consultaProfessorCPF($cpf);

            if (($retornoConsulta['codigo'] != 9) && ($retornoConsulta['codigo'] != 10)) {

                $this->db->query("INSERT INTO tbl_professor (codigo, nome, cpf, tipo)
                VALUES ($codigo, '$nome', '$cpf', '$tipo')");

                if ($this->db->affected_rows() > 0) {
                    $dados = array('codigo' => 1, 'msg' => 'Professor cadastrado corretamente');
                } else {
                    $dados = array(
                        'codigo' => 8,
                        'msg' => 'Houve algum problema na inserção na tabela de professores'
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

    private function consultaProfessorCPF($cpf)
    {
        try {
            $sql = "SELECT * FROM tbl_professor WHERE cpf = '$cpf'";
            $retornoProfessor = $this->db->query($sql);

            if ($retornoProfessor->num_rows() > 0) {
                $linha = $retornoProfessor->row();
                if (trim($linha->estatus) == "D") {
                    $dados = array(
                        'codigo' => 9,
                        'msg' => 'Professor desativado, caso precise reativá-lo, fale com o adm'
                    );
                } else {
                    $dados = array(
                        'codigo' => 10,
                        'msg' => 'CPF já cadastrado para outro professor no sistema'
                    );
                }
            } else {
                $dados = array(
                    'codigo' => 98,
                    'msg' => 'Professor não encontrado'
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

    private function consultaProfessorCod($codigo){

        try {
            $sql = "SELECT * FROM tbl_professor WHERE codigo = $codigo and estatus = ''";

            $retornoProfessor = $this->db->query($sql);

            if ($retornoProfessor->num_rows() > 0) {
                $dados = array('codigo' => 1, 'msg' => 'Consulta efetuada com sucesso');
                
            } else {
                $dados = array(
                    'codigo' => 98,
                    'msg' => 'Professor não encontrado'
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


    public function consultar($codigo, $nome, $cpf, $tipo)
    {
        try {
            $sql = "SELECT * FROM tbl_professor WHERE estatus = '' ";

            if (trim($codigo) != '') {
                $sql .= " AND codigo = '$codigo' ";
            }

            if (trim($nome) != '') {
                $sql .= " AND nome LIKE '%$nome%' ";
            }

            if (trim($cpf) != '') {
                $sql .= " AND cpf = '$cpf' ";
            }

            if (trim($tipo) != '') {
                $sql .= " AND tipo = '$tipo' ";
            }

            $sql .= " ORDER BY nome";

            $retorno = $this->db->query($sql);

            if ($retorno->num_rows() > 0) {
                $dados = array(
                    'codigo' => 1,
                    'msg' => 'Consulta efetuada com sucesso!',
                    'dados' => $retorno->result()
                );
            } else {
                $dados = array(
                    'codigo' => 11,
                    'msg' => 'Professor não encontrado'
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

    public function alterar($codigo, $nome, $cpf, $tipo)
    {
        try {
           
            $retornoConsulta = $this->consultaProfessorCod($codigo);

            if ($retornoConsulta['codigo'] == 1) {
                $query = "update tbl_professor set ";

                if ($nome !=''){
                    $query .= "nome = '$nome', ";
                }
                if ($cpf !=''){
                    $query .= "cpf = '$cpf', ";
                }
                if ($tipo !=''){
                    $query .= "tipo = '$tipo', ";
                }

                $queryFinal = rtrim($query, ', ') . " where codigo = $codigo";



                $this->db->query($queryFinal);

                if ($this->db->affected_rows() > 0) {
                    $dados = array(
                        'codigo' => 1,
                        'msg' => 'Professor atualizado corretamente'
                    );
                } else {
                    $dados = array(
                        'codigo' => 8,
                        'msg' => 'Houve um problema na atualização na tabela de professores'
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

    public function desativar($codigo)
    {
        try {
            $retornoConsulta = $this->consultaProfessorCod($codigo);

            if ($retornoConsulta['codigo'] == 1) {
                $this->db->query("UPDATE tbl_professor SET estatus = 'D' WHERE codigo = $codigo");

                if ($this->db->affected_rows() > 0) {
                    $dados = array(
                        'codigo' => 1,
                        'msg' => 'Professor desativado corretamente!'
                    );
                } else {
                    $dados = array(
                        'codigo' => 5,
                        'msg' => 'Houve algum problema na desativação do Professor!'
                    );
                }
            } else {
                $dados = array(
                    'codigo' => 6,
                    'msg' => 'Professor não cadastrado no sistema, não possivel exclusão'
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

    /**
     * MÉTODO PRIVADO: consultaProfessor
     * Verifica a existência e situação de um professor pelo código
     * Retorna código 9 (desativado), 10 (ativo/encontrado) ou 98 (não encontrado)
     */
    private function consultaProfessor($codigo)
    {
        try {
            $sql = "SELECT * FROM tbl_professor WHERE codigo = $codigo";
            $retornoProfessor = $this->db->query($sql);

            if ($retornoProfessor->num_rows() > 0) {
                $linha = $retornoProfessor->row();
                if (trim($linha->estatus) == "D") {
                    $dados = array(
                        'codigo' => 9,
                        'msg'    => 'Professor desativado, caso precise reativá-lo, fale com o adm'
                    );
                } else {
                    $dados = array(
                        'codigo' => 10,
                        'msg'    => 'Professor já cadastrado no sistema'
                    );
                }
            } else {
                $dados = array(
                    'codigo' => 98,
                    'msg'    => 'Professor não encontrado'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 0,
                'msg'    => 'Atenção: O seguinte erro aconteceu -> ' . $e->getMessage()
            );
        }
        return $dados;
    }

    /**
     * MÉTODO PRIVADO: consultaCpf
     * Verifica se um CPF já está cadastrado na tabela de professores
     * O parâmetro $codigoIgnorar é opcional e serve para excluir o próprio professor
     * da verificação durante uma alteração
     * Retorna código 10 se o CPF já pertence a outro professor, 98 caso contrário
     */
    private function consultaCpf($cpf, $codigoIgnorar = null)
    {
        try {
            $sql = "SELECT * FROM tbl_professor WHERE cpf = '$cpf' AND estatus != 'D' ";

            if ($codigoIgnorar !== null) {
                $sql .= " AND codigo != $codigoIgnorar ";
            }

            $retorno = $this->db->query($sql);

            if ($retorno->num_rows() > 0) {
                $dados = array(
                    'codigo' => 10,
                    'msg'    => 'CPF já cadastrado para outro professor no sistema'
                );
            } else {
                $dados = array(
                    'codigo' => 98,
                    'msg'    => 'CPF disponível para cadastro'
                );
            }
        } catch (Exception $e) {
            $dados = array(
                'codigo' => 0,
                'msg'    => 'Atenção: O seguinte erro aconteceu -> ' . $e->getMessage()
            );
        }
        return $dados;
    }
}
