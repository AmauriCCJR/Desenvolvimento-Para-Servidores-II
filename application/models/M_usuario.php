<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_usuario extends CI_Model {

    public function inserir($nome, $email, $usuario, $senha){
        try {
            $retornoUsuario = $this->validaUsuario($usuario);

            if ($retornoUsuario['codigo'] == 4) {
                $this->db->query("insert into tbl_usuario (nome, email, usuario, senha)
                                  values ('$nome', '$email', '$usuario', md5('$senha'))");

                if ($this->db->affected_rows() > 0) {
                    $dados = array('codigo' => 1,
                                   'msg'    => 'Usuário cadastrado corretamente.');
                } else {
                    $dados = array('codigo' => 8,
                                   'msg'    => 'Houve algum problema na inserção na tabela de usuário.');
                }
            } else {
                $dados = array(
                    'codigo' => $retornoUsuario['codigo'],
                    'msg'    => $retornoUsuario['msg']
                );
            }
        } catch (Exception $e) {
            $dados = array('codigo' => 0,
                           'msg'    => 'ATENÇÃO: O seguinte erro aconteceu -> ',
                                       $e->getMessage());
        }

        return $dados;
    }

    public function consultar($nome, $email, $usuario){
        try {
            $sql = "select id_usuario, nome, usuario, email
                    from tbl_usuario
                    where estatus != 'D'";

            if (trim($nome) != ''){
                $sql = $sql . "and nome like '%$nome%' ";
            }

            if (trim($email) != ''){
                $sql = $sql . "and email = '$email' ";
            }

            if (trim($usuario) != '') {
                $sql = $sql . "and usuario like '%$usuario%' ";
            }

            $retorno = $this->db->query($sql);

            if ($retorno->num_rows() > 0) {
                $dados = array('codigo' => 1,
                               'msg' => 'Consulta efetuada com sucesso.',
                               'dados'  => $retorno->result());
            } else {
                $dados = array('codigo' => 6,
                               'msg' => 'Dados não encontrados.');
            }
        } catch (Exception $e) {
            $dados = array('codigo' => 00,
                           'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',
                                       $e->getMessage());
        }

        return $dados;
    }

    public function alterar($idUsuario, $nome, $email, $senha){
        try {
            $retornoUsuario = $this->validaIdUsuario($idUsuario);

            if ($retornoUsuario['codigo'] == 1) {
                $query = "update tbl_usuario set ";

                if ($nome !== '') {
                    $query .= "nome = '$nome', ";
                }

                if ($email !== '') {
                    $query .= "email = '$email', ";
                }

                if ($senha !== '') {
                    $query .= "senha = md5('$senha'), ";
                }

                $queryFinal = rtrim($query, ", ") . " where id_usuario = $idUsuario";

                $this->db->query($queryFinal);

                if ($this->db->affected_rows() > 0) {
                    $dados = array('codigo' => 1,
                                   'msg' => 'Usuário atualizado corretamente.');
                } else {
                    $dados = array('codigo' => 8,
                                   'msg' => 'Houve algum problema na atualização na tabela de usuários.');
                }
            } else {
                $dados = array(
                    'codigo' => $retornoUsuario['codigo'],
                    'msg'    => $retornoUsuario['msg']
                );
            }
        } catch (Exception $e) {
            $dados = array('codigo' => 00,
                           'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',
                                       $e->getMessage());
        }

        return $dados;
    }

    public function desativar($idUsuario){
        try {
            $retornoUsuario = $this->validaIdUsuario($idUsuario);

            if ($retornoUsuario['codigo'] == 1) {
                $this->db->query("update tbl_usuario set estatus = 'D'
                                  where id_usuario = $idUsuario");

                if ($this->db->affected_rows() > 0) {
                    $dados = array('codigo' => 1,
                                   'msg' => 'Usuário DESATIVADO corretamente.');
                } else {
                    $dados = array('codigo' => 8,
                                   'msg' => 'Houve algum problema na DESATIVAÇÃO do usuário.');
                }
            } else {
                $dados = array(
                    'codigo' => $retornoUsuario['codigo'],
                    'msg'    => $retornoUsuario['msg']
                );
            }
        } catch (Exception $e) {
            $dados = array('codigo' => 00,
                           'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',
                                       $e->getMessage());
        }

        return $dados;
    }

    private function validaUsuario($usuario){
        try {
            $retorno = $this->db->query("select * from tbl_usuario
                                         where usuario = '$usuario'");

            $linha = $retorno->row();

            if ($retorno->num_rows() == 0) {
                $dados = array('codigo' => 4,
                               'msg' => 'Usuário não existe na base de dados.');
            } else {
                if (trim($linha->estatus) == "D") {
                    $dados = array('codigo' => 5,
                                   'msg' => 'Usuário DESATIVADO NA BASE DE DADOS, não pode ser utilizado!');
                } else {
                    $dados = array('codigo' => 1,
                                   'msg' => 'Usuário correto');
                }
            }
        } catch (Exception $e) {
            $dados = array('codigo' => 0,
                           'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ',
                                       $e->getMessage());
        }

        return $dados;
    }

    private function validaIdUsuario($idUsuario){
        try {
            $retorno = $this->db->query("select * from tbl_usuario where id_usuario = $idUsuario");

            $linha = $retorno->row();

            if ($retorno->num_rows() == 0) {
                $dados = array('codigo' => 4,
                               'msg' => 'Usuário não existe na base de dados.');
            } else {
                if (trim($linha->estatus) == "D") {
                    $dados = array('codigo' => 5,
                                   'msg' => 'Usuário JÁ DESATIVADO NA BASE DE DADOS!');
                } else {
                    $dados = array('codigo' => 1,
                                   'msg' => 'Usuário correto');
                }
            }
        } catch (Exception $e) {
            $dados = array('codigo' => 0,
                           'msg' => 'ATENÇÃO: O seguinte erro aconteceu -> ', $e->getMessage());
        }

        return $dados;
    }

    public function validaLogin($usuario, $senha){
        try {
            $retorno = $this->db->query("select * from tbl_usuario
                                         where usuario = '$usuario'
                                           and senha   = md5('$senha')");

            $linha = $retorno->row();

            if ($retorno->num_rows() == 0) {
                $dados = array('codigo' => 4,
                               'msg' => 'Usuário ou senha inválidos.');
            } else {
                if (trim($linha->estatus) == "D") {
                    $dados = array('codigo' => 5,
                                   'msg'    => 'Usuário DESATIVADO NA BASE DE DADOS!');
                } else {
                    $dados = array('codigo' => 1,
                                   'msg'    => 'Usuário correto');
                }
            }
        } catch (Exception $e) {
            $dados = array('codigo' => 00,
                           'msg'    => 'ATENÇÃO: O seguinte erro aconteceu -> ',
                                       $e->getMessage());
        }

        return $dados;
    }
}
?>
