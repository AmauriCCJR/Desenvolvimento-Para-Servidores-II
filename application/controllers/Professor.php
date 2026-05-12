<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Professor extends CI_Controller
{

    /*
Validação dos tipos de retornos nas validações (Código de erro)
1  - Operação realizada no banco de dados com sucesso (Inserção, Alteração, Consulta ou Exclusão)
2  - Conteúdo passado nulo ou vazio
3  - Conteúdo zerado
4  - Conteúdo não inteiro
5  - Conteúdo não é um texto
6  - Data em formato inválido
7  - Hora em formato inválido
12 - Na atualização, pelo menos um atributo deve ser passado
15 - CPF com menos de 11 digitos
16 - CPF com todos os digitos iguais
17 - CPF com digitos verificadores incorretos
99 - Parâmetros passados do front não correspondem ao método
*/

    private $codigo;
    private $nome;
    private $cpf;
    private $tipo;
    private $estatus;

    public function getCodigo()
    {
        return $this->codigo;
    }
    public function setCodigo($codigoFront)
    {
        $this->codigo = $codigoFront;
    }


    public function getNome()
    {
        return $this->nome;
    }
    public function setNome($nomeFront)
    {
        $this->nome = $nomeFront;
    }


    public function getCpf()
    {
        return $this->cpf;
    }
    public function setCpf($cpfFront)
    {
        $this->cpf = $cpfFront;
    }


    public function getTipo()
    {
        return $this->tipo;
    }
    public function setTipo($tipoFront)
    {
        $this->tipo = $tipoFront;
    }


    public function getEstatus()
    {
        return $this->estatus;
    }
    public function setEstatus($estatusFront)
    {
        $this->estatus = $estatusFront;
    }


    public function inserir()
    {
        $erros = [];
        $sucesso = false;

        try {

            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            $lista = [
                "codigo" => '0',
                "nome" => '0',
                "cpf" => '0',
                "tipo" => '0'
            ];


            if (verificarParametro($resultado, $lista) != 1) {
                $erros[] = ['codigo' => 99, 'msg' => 'Campos inexistentes ou incorretos no FrontEnd'];
            } else {

                $retornoCodigo = validarDados($resultado->codigo, 'int', true);
                $retornoNome = validarDados($resultado->nome, 'string', true);
                $retornoCPF = validarDados($resultado->cpf, 'string', true);
                $retornoCPFNroValidos = validarCPF($resultado->cpf);
                $retornoTipo = validarDados($resultado->tipo, 'string', true);


                if ($retornoCodigo['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoCodigo['codigoHelper'],
                        'campo' => 'Codigo',
                        'msg' => $retornoCodigo['msg']
                    ];
                }
                if ($retornoNome['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoNome['codigoHelper'],
                        'campo' => 'Nome',
                        'msg' => $retornoNome['msg']
                    ];
                }
                if ($retornoCPF['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoCPF['codigoHelper'],
                        'campo' => 'CPF',
                        'msg' => $retornoCPF['msg']
                    ];
                }
                if ($retornoCPFNroValidos['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoCPFNroValidos['codigoHelper'],
                        'campo' => 'CPF validação numero',
                        'msg' => $retornoCPFNroValidos['msg']
                    ];
                }
                if ($retornoTipo['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoTipo['codigoHelper'],
                        'campo' => 'Tipo',
                        'msg' => $retornoTipo['msg']
                    ];
                }


                if (empty($erros)) {
                    $this->setCodigo($resultado->codigo);
                    $this->setNome($resultado->nome);
                    $this->setCpf($resultado->cpf);
                    $this->setTipo($resultado->tipo);

                    $this->load->model('M_professor');
                    $resBanco = $this->M_professor->inserir(
                        $this->getCodigo(),
                        $this->getNome(),
                        $this->getCpf(),
                        $this->getTipo()
                    );

                    if ($resBanco['codigo'] == 1) {
                        $sucesso = true;
                    } else {
                        $erros[] = ['codigo' => $resBanco['codigo'], 'msg' => $resBanco['msg']];
                    }
                }
            }
        } catch (Exception $e) {
            $erros[] = ['codigo' => 0, 'msg' => 'Erro Inesperado: ' . $e->getMessage()];
        }

        if ($sucesso == true) {
            $retorno = ['sucesso' => $sucesso, 'codigo' => $resBanco['codigo'], 'msg' => $resBanco['msg']];
        } else {
            $retorno = ['sucesso' => $sucesso, 'erros' => $erros];
        }

        echo json_encode($retorno);
    }


    public function consultar()
    {
        $erros = [];
        $sucesso = false;

        try {

            $json = file_get_contents('php://input');
            $resultado = json_decode($json);

            $lista = [
                "codigo" => '0',
                "nome" => '0',
                "cpf" => '0',
                "tipo" => '0'
            ];

            if (verificarParametro($resultado, $lista) != 1) {
                $erros[] = ['codigo' => 99, 'msg' => 'Campos inexistentes ou incorretos no FrontEnd'];
            } else {

                $retornoCodigo = validarDadosConsulta($resultado->codigo, 'int');
                $retornoNome = validarDadosConsulta($resultado->nome, 'string');
                $retornoCPF = validarDadosConsulta($resultado->cpf, 'string');
                $retornoTipo = validarDadosConsulta($resultado->tipo, 'string');

                // ====== VERIFICAR ERROS DE VALIDAÇÃO ======
                if ($retornoCodigo['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoCodigo['codigoHelper'],
                        'campo' => 'Codigo',
                        'msg' => $retornoCodigo['msg']
                    ];
                }
                if ($retornoNome['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoNome['codigoHelper'],
                        'campo' => 'Nome',
                        'msg' => $retornoNome['msg']
                    ];
                }
                if ($retornoCPF['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoCPF['codigoHelper'],
                        'campo' => 'CPF',
                        'msg' => $retornoCPF['msg']
                    ];
                }
                if ($retornoTipo['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoTipo['codigoHelper'],
                        'campo' => 'Tipo',
                        'msg' => $retornoTipo['msg']
                    ];
                }

                if ($resultado->cpf != '') {
                    $retornoCPFNroValidos = validarCPF($resultado->cpf);
                    if ($retornoCPFNroValidos['codigoHelper'] != 0) {
                        $erros[] = [
                            'codigo' => $retornoCPFNroValidos['codigoHelper'],
                            'campo' => 'CPF validação numero',
                            'msg' => $retornoCPFNroValidos['msg']
                        ];
                    }
                }
                if (empty($erros)) {
                    $this->setCodigo($resultado->codigo);
                    $this->setNome($resultado->nome);
                    $this->setCpf($resultado->cpf);
                    $this->setTipo($resultado->tipo);

                    $this->load->model('M_professor');
                    $resBanco = $this->M_professor->consultar(
                        $this->getCodigo(),
                        $this->getNome(),
                        $this->getCpf(),
                        $this->getTipo()
                    );

                    if ($resBanco['codigo'] == 1) {
                        $sucesso = true;
                    } else {
                        $erros[] = ['codigo' => $resBanco['codigo'], 'msg' => $resBanco['msg']];
                    }
                }
            }
        } catch (Exception $e) {
            $erros[] = ['codigo' => 0, 'msg' => 'Erro inesperado: ' . $e->getMessage()];
        }


        if ($sucesso == true) {
            $retorno = [
                'sucesso' => $sucesso, 'codigo' => $resBanco['codigo'],'msg' => $resBanco['msg'],'dados' => $resBanco['dados']];
        } else {
            $retorno = ['sucesso' => $sucesso,'erros' => $erros];
        }

        echo json_encode($retorno);
    }

    public function alterar()
    {
        $erros = [];
        $sucesso = false;

        try {

            $json = file_get_contents('php://input');
            $resultado = json_decode($json);
            $lista = [
                "codigo" => '0',
                "nome" => '0',
                "cpf" => '0',
                "tipo" => '0'
            ];

            if (verificarParametro($resultado, $lista) != 1) {
                $erros[] = ['codigo' => 99, 'msg' => 'Campos inexistentes ou incorretos no FrontEnd'];
            } else {

                if (trim($resultado->nome) == '' && trim($resultado->cpf) == '' && trim($resultado->tipo) == '') {
                    $erros[] = ['codigo' => 12, 'msg' => 'Pelo menos um parametro precisa ser passado para a atualização!'];
                } else {
                    $retornoCodigo = validarDados($resultado->codigo,'int', true);
                    $retornoNome = validarDadosConsulta($resultado->nome,'string');
                    $retornoCPF = validarDadosConsulta($resultado->cpf,'string');
                    $retornoTipo = validarDadosConsulta($resultado->tipo,'string');

                    if ($retornoCodigo['codigoHelper'] != 0) {
                        $erros[] = [
                            'codigo' => $retornoCodigo['codigoHelper'],
                            'campo' => 'Codigo',
                            'msg' => $retornoCodigo['msg']
                        ];
                    }
                    if ($retornoNome['codigoHelper'] != 0) {
                        $erros[] = [
                            'codigo' => $retornoNome['codigoHelper'],
                            'campo' => 'Nome',
                            'msg' => $retornoNome['msg']
                        ];
                    }
                    if ($retornoCPF['codigoHelper'] != 0) {
                        $erros[] = [
                            'codigo' => $retornoCPF['codigoHelper'],
                            'campo' => 'CPF',
                            'msg' => $retornoCPF['msg']
                        ];
                    } 

                    if ($retornoTipo['codigoHelper'] != 0) {
                        $erros[] = [
                            'codigo' => $retornoTipo['codigoHelper'],
                            'campo' => 'Tipo',
                            'msg' => $retornoTipo['msg']
                        ];
                    }

                    if ($resultado->cpf != '') {
                        $retornoCPFNroValidos = validarCPF($resultado->cpf);
                        if ($retornoCPFNroValidos['codigoHelper'] != 0) {
                            $erros[] = [
                                'codigo' => $retornoCPFNroValidos['codigoHelper'],
                                'campo' => 'CPF validação numero',
                                'msg' => $retornoCPFNroValidos['msg']
                            ];
                        }
                    }

                    if (empty($erros)) {
                        $this->setCodigo($resultado->codigo);
                        $this->setNome($resultado->nome);
                        $this->setCpf($resultado->cpf);
                        $this->setTipo($resultado->tipo);

                        $this->load->model('M_professor');
                        $resBanco = $this->M_professor->alterar(
                            $this->getCodigo(),
                            $this->getNome(),
                            $this->getCpf(),
                            $this->getTipo()
                        );

                        if ($resBanco['codigo'] == 1) {
                            $sucesso = true;
                        } else {
                            $erros[] = ['codigo' => $resBanco['codigo'], 'msg' => $resBanco['msg']];
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $erros[] = ['codigo' => 0, 'msg' => 'Erro Inesperado: ' . $e->getMessage()];
        }

        if ($sucesso == true) {
            $retorno = ['sucesso' => $sucesso, 'codigo' => $resBanco['codigo'], 'msg' => $resBanco['msg']];
        } else {
            $retorno = ['sucesso' => $sucesso, 'erros' => $erros];
        }

        echo json_encode($retorno);
    }


    public function desativar()
    {
        $erros = [];
        $sucesso = false;

        try {
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);
            $lista = [
                "codigo" => '0'
            ];

            if (verificarParametro($resultado, $lista) != 1) {
                $erros[] = ['codigo' => 99, 'msg' => 'Campos inexistentes ou incorretos no FrontEnd'];
            } else {

                $retornoCodigo = validarDados($resultado->codigo, 'int', true);

                if ($retornoCodigo['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoCodigo['codigoHelper'],
                        'campo' => 'Codigo',
                        'msg' => $retornoCodigo['msg']
                    ];
                }

                if (empty($erros)) {
                    $this->setCodigo($resultado->codigo);

                    $this->load->model('M_professor');
                    $resBanco = $this->M_professor->desativar($this->getCodigo());

                    if ($resBanco['codigo'] == 1) {
                        $sucesso = true;
                    } else {
                        $erros[] = ['codigo' => $resBanco['codigo'], 'msg' => $resBanco['msg']];
                    }
                }
            }
        } catch (Exception $e) {
            $erros[] = ['codigo' => 0, 'msg' => 'Erro inesperado: ' . $e->getMessage()];
        }

        if ($sucesso == true) {
            $retorno = ['sucesso' => $sucesso, 'codigo' => $resBanco['codigo'], 'msg' => $resBanco['msg']];
        } else {
            $retorno = ['sucesso' => $sucesso, 'erros' => $erros];
        }

        echo json_encode($retorno);
    }
}
