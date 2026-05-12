<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends CI_Controller {

    private $idUsuario;
    private $nome;
    private $email;
    private $usuario;
    private $senha;

    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function getSenha()
    {
        return $this->senha;
    }

    public function setIdUsuario($idUsuarioFront)
    {
        $this->idUsuario = $idUsuarioFront;
    }

    public function setNome($nomeFront)
    {
        $this->nome = $nomeFront;
    }

    public function setEmail($emailFront)
    {
        $this->email = $emailFront;
    }

    public function setUsuario($usuarioFront)
    {
        $this->usuario = $usuarioFront;
    }

    public function setSenha($senhaFront)
    {
        $this->senha = $senhaFront;
    }

    public function inserir(){
        $erros = [];
        $sucesso = false;

        try {
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            $lista = array(
                "nome"    => '0',
                "email"   => '0',
                "usuario" => '0',
                "senha"   => '0'
            );

            if (verificarParam($resultado, $lista) != 1) {
                $erros[] = ['codigo' => 99, 'msg' => 'Campos inexistentes ou incorretos no FrontEnd.'];
            } else {
                $retornoNome    = validarDados($resultado->nome, 'string', true);
                $retornoEmail   = validarDados($resultado->email, 'email', true);
                $retornoUsuario = validarDados($resultado->usuario, 'string', true);
                $retornoSenha   = validarDados($resultado->senha, 'string', true);

                if ($retornoNome['codigoHelper'] != 0) {
                    $erros[] = ['codigo' => $retornoNome['codigoHelper'],
                                'campo'  => 'Nome',
                                'msg'    => $retornoNome['msg']];
                }

                if ($retornoEmail['codigoHelper'] != 0) {
                    $erros[] = ['codigo' => $retornoEmail['codigoHelper'],
                                'campo'  => 'E-mail',
                                'msg'    => $retornoEmail['msg']];
                }

                if ($retornoUsuario['codigoHelper'] != 0) {
                    $erros[] = ['codigo' => $retornoUsuario['codigoHelper'],
                                'campo'  => 'Usuário',
                                'msg'    => $retornoUsuario['msg']];
                }

                if ($retornoSenha['codigoHelper'] != 0) {
                    $erros[] = ['codigo' => $retornoSenha['codigoHelper'],
                                'campo'  => 'Data Inicio',
                                'msg'    => $retornoSenha['msg']];
                }

                if (empty($erros)) {
                    $this->setNome($resultado->nome);
                    $this->setEmail($resultado->email);
                    $this->setUsuario($resultado->usuario);
                    $this->setSenha($resultado->senha);

                    $this->load->model('M_usuario');

                    $resBanco = $this->M_usuario->inserir($this->getNome(),
                                                          $this->getEmail(),
                                                          $this->getUsuario(),
                                                          $this->getSenha());

                    if ($resBanco['codigo'] == 1) {
                        $sucesso = true;
                    } else {
                        $erros[] = [
                            'codigo' => $resBanco['codigo'],
                            'msg'    => $resBanco['msg']
                        ];
                    }
                }
            }
        } catch (Exception $e) {
            $retorno = array('codigo' => 0,
                             'msg'    => 'ATENÇÃO: O seguinte erro aconteceu -> ',
                                         $e->getMessage());
        }

        if ($sucesso == true) {
            $retorno = ['sucesso' => $sucesso, 'codigo' => $resBanco['codigo'],
                        'msg'    => $resBanco['msg']];
        } else {
            $retorno = ['sucesso' => $sucesso, 'erros' => $erros];
        }

        echo json_encode($retorno);
    }

    public function consultar(){
        $erros = [];
        $sucesso = false;

        try {
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            $lista = array(
                "nome"    => '0',
                "email"   => '0',
                "usuario" => '0'
            );

            if (verificarParam($resultado, $lista) != 1) {
                $erros[] = ['codigo' => 99, 'msg' => 'Campos inexistentes ou incorretos no FrontEnd.'];
            } else {
                $retornoNome    = validarDadosConsulta($resultado->nome, 'string');
                $retornoEmail   = validarDadosConsulta($resultado->email, 'email');
                $retornoUsuario = validarDadosConsulta($resultado->usuario, 'string');

                if ($retornoNome['codigoHelper'] != 0) {
                    $erros[] = ['codigo' => $retornoNome['codigoHelper'],
                                'campo'  => 'Nome',
                                'msg'    => $retornoNome['msg']];
                }

                if ($retornoEmail['codigoHelper'] != 0) {
                    $erros[] = ['codigo' => $retornoEmail['codigoHelper'],
                                'campo'  => 'E-mail',
                                'msg'    => $retornoEmail['msg']];
                }

                if ($retornoUsuario['codigoHelper'] != 0) {
                    $erros[] = ['codigo' => $retornoUsuario['codigoHelper'],
                                'campo'  => 'Usuário',
                                'msg'    => $retornoUsuario['msg']];
                }

                if (empty($erros)) {
                    $this->setNome($resultado->nome);
                    $this->setEmail($resultado->email);
                    $this->setUsuario($resultado->usuario);

                    $this->load->model('M_usuario');

                    $resBanco = $this->M_usuario->consultar($this->getNome(),
                                                            $this->getEmail(),
                                                            $this->getUsuario());

                    if ($resBanco['codigo'] == 1) {
                        $sucesso = true;
                    } else {
                        $erros[] = [
                            'codigo' => $resBanco['codigo'],
                            'msg'    => $resBanco['msg']
                        ];
                    }
                }
            }
        } catch (Exception $e) {
            $retorno = array('codigo' => 0,
                             'msg'    => 'ATENÇÃO: O seguinte erro aconteceu -> ',
                                         $e->getMessage());
        }

        if ($sucesso == true) {
            $retorno = ['sucesso' => $sucesso, 'codigo' => $resBanco['codigo'],
                        'msg'    => $resBanco['msg'],
                        'dados'  => $resBanco['dados']];
        } else {
            $retorno = ['sucesso' => $sucesso, 'erros' => $erros];
        }

        echo json_encode($retorno);
    }

    public function alterar(){
        $erros = [];
        $sucesso = false;

        try {
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            $lista = array(
                "idUsuario" => '0',
                "nome"      => '0',
                "email"     => '0',
                "senha"     => '0'
            );

            if (verificarParam($resultado, $lista) != 1) {
                $erros[] = ['codigo' => 99, 'msg' => 'Campos inexistentes ou incorretos no FrontEnd.'];
            } else {
                if (trim($resultado->nome) == '' && trim($resultado->email) == '' &&
                    trim($resultado->senha) == '') {
                    $erros[] = ['codigo' => 12,
                                'msg'    => 'Pelo menos um parâmetro precisa ser passado para atualização'];
                } else {
                    $retornoIdUsuario = validarDados($resultado->idUsuario, 'int');
                    $retornoNome      = validarDadosConsulta($resultado->nome, 'string');
                    $retornoEmail     = validarDadosConsulta($resultado->email, 'email');
                    $retornoSenha     = validarDadosConsulta($resultado->senha, 'string');

                    if ($retornoIdUsuario['codigoHelper'] != 0) {
                        $erros[] = ['codigo' => $retornoIdUsuario['codigoHelper'],
                                    'campo'  => 'ID Usuário',
                                    'msg'    => $retornoIdUsuario['msg']];
                    }

                    if ($retornoNome['codigoHelper'] != 0) {
                        $erros[] = ['codigo' => $retornoNome['codigoHelper'],
                                    'campo'  => 'Nome',
                                    'msg'    => $retornoNome['msg']];
                    }

                    if ($retornoEmail['codigoHelper'] != 0) {
                        $erros[] = ['codigo' => $retornoEmail['codigoHelper'],
                                    'campo'  => 'E-mail',
                                    'msg'    => $retornoEmail['msg']];
                    }

                    if ($retornoSenha['codigoHelper'] != 0) {
                        $erros[] = ['codigo' => $retornoSenha['codigoHelper'],
                                    'campo'  => 'Senha',
                                    'msg'    => $retornoSenha['msg']];
                    }

                    if (empty($erros)) {
                        $this->setIdUsuario($resultado->idUsuario);
                        $this->setNome($resultado->nome);
                        $this->setEmail($resultado->email);
                        $this->setSenha($resultado->senha);

                        $this->load->model('M_usuario');

                        $resBanco = $this->M_usuario->alterar($this->getIdUsuario(),
                                                              $this->getNome(),
                                                              $this->getEmail(),
                                                              $this->getSenha());

                        if ($resBanco['codigo'] == 1) {
                            $sucesso = true;
                        } else {
                            $erros[] = [
                                'codigo' => $resBanco['codigo'],
                                'msg'    => $resBanco['msg']
                            ];
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $retorno = array('codigo' => 0,
                             'msg'    => 'ATENÇÃO: O seguinte erro aconteceu -> ',
                                         $e->getMessage());
        }

        if ($sucesso == true) {
            $retorno = ['sucesso' => $sucesso, 'codigo' => $resBanco['codigo'],
                        'msg'    => $resBanco['msg']];
        } else {
            $retorno = ['sucesso' => $sucesso, 'erros' => $erros];
        }

        echo json_encode($retorno);
    }

    public function desativar(){
        $erros = [];
        $sucesso = false;

        try {
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            $lista = array(
                "idUsuario" => '0'
            );

            if (verificarParam($resultado, $lista) != 1) {
                $erros[] = ['codigo' => 99, 'msg' => 'Campos inexistentes ou incorretos no FrontEnd.'];
            } else {
                $retornoIdUsuario = validarDados($resultado->idUsuario, 'int');

                if ($retornoIdUsuario['codigoHelper'] != 0) {
                    $erros[] = ['codigo' => $retornoIdUsuario['codigoHelper'],
                                'campo'  => 'ID Usuário',
                                'msg'    => $retornoIdUsuario['msg']];
                }

                if (empty($erros)) {
                    $this->setIdUsuario($resultado->idUsuario);

                    $this->load->model('M_usuario');

                    $resBanco = $this->M_usuario->desativar($this->getIdUsuario());

                    if ($resBanco['codigo'] == 1) {
                        $sucesso = true;
                    } else {
                        $erros[] = [
                            'codigo' => $resBanco['codigo'],
                            'msg'    => $resBanco['msg']
                        ];
                    }
                }
            }
        } catch (Exception $e) {
            $retorno = array('codigo' => 0,
                             'msg'    => 'ATENÇÃO: O seguinte erro aconteceu -> ',
                                         $e->getMessage());
        }

        if ($sucesso == true) {
            $retorno = ['sucesso' => $sucesso, 'codigo' => $resBanco['codigo'],
                        'msg'    => $resBanco['msg']];
        } else {
            $retorno = ['sucesso' => $sucesso, 'erros' => $erros];
        }

        echo json_encode($retorno);
    }

    public function logar() {
        $erros = [];
        $sucesso = false;

        try {
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            $lista = array(
                "usuario" => '0',
                "senha"   => '0'
            );

            if (verificarParam($resultado, $lista) != 1) {
                $erros[] = ['codigo' => 99, 'msg' => 'Campos inexistentes ou incorretos no FrontEnd.'];
            } else {
                $retornoUsuario = validarDados($resultado->usuario, 'string', true);
                $retornoSenha   = validarDados($resultado->senha, 'string', true);

                if ($retornoUsuario['codigoHelper'] != 0) {
                    $erros[] = ['codigo' => $retornoUsuario['codigoHelper'],
                                'campo'  => 'Usuário',
                                'msg'    => $retornoUsuario['msg']];
                }

                if ($retornoSenha['codigoHelper'] != 0) {
                    $erros[] = ['codigo' => $retornoSenha['codigoHelper'],
                                'campo'  => 'Senha',
                                'msg'    => $retornoSenha['msg']];
                }

                if (empty($erros)) {
                    $this->setUsuario($resultado->usuario);
                    $this->setSenha($resultado->senha);

                    $this->load->model('M_usuario');

                    $resBanco = $this->M_usuario->validaLogin($this->getUsuario(),
                                                              $this->getSenha());

                    if ($resBanco['codigo'] == 1) {
                        $sucesso = true;
                    } else {
                        $erros[] = [
                            'codigo' => $resBanco['codigo'],
                            'msg'    => $resBanco['msg']
                        ];
                    }
                }
            }
        } catch (Exception $e) {
            $retorno = array('codigo' => 0,
                             'msg'    => 'ATENÇÃO: O seguinte erro aconteceu -> ',
                                         $e->getMessage());
        }

        if ($sucesso == true) {
            $retorno = ['sucesso' => $sucesso, 'codigo' => $resBanco['codigo'],
                        'msg'    => $resBanco['msg']];
        } else {
            $retorno = ['sucesso' => $sucesso, 'erros' => $erros];
        }

        echo json_encode($retorno);
    }
}
?>
