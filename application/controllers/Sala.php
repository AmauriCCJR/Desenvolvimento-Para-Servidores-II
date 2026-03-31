<?php
defined('BASEPATH') or exit('No direct script access allowed');



class Sala extends CI_Controller
{


    private $codigo;
    private $descricao;
    private $andar;
    private $capacidade;
    private $estatus;

    // Getter e Setter para Código
    public function getCodigo()
    {
        return $this->codigo;
    }
    public function setCodigo($codigoFront)
    {
        $this->codigo = $codigoFront;
    }

    // Getter e Setter para Descrição
    public function getDescricao()
    {
        return $this->descricao;
    }
    public function setDescricao($descricaoFront)
    {
        $this->descricao = $descricaoFront;
    }

    // Getter e Setter para Andar
    public function getAndar()
    {
        return $this->andar;
    }
    public function setAndar($andarFront)
    {
        $this->andar = $andarFront;
    }

    // Getter e Setter para Capacidade
    public function getCapacidade()
    {
        return $this->capacidade;
    }
    public function setCapacidade($capacidadeFront)
    {
        $this->capacidade = $capacidadeFront;
    }

    // Getter e Setter para Estatus
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
                "descricao" => '0',
                "andar" => '0',
                "capacidade" => '0'
            ];

            if (verificarParametro($resultado, $lista) != 1) {
                $erros[] = ['codigo' => 99, 'msg' => 'Campos inexistentes ou incorretos no FrontEnd'];
            } else {
                $retornoCodigo = validarDados($resultado->codigo, 'int', true);
                $retornoDescricao = validarDados($resultado->descricao, 'string', true);
                $retornoAndar = validarDados($resultado->andar, 'int', true);
                $retornoCapacidade = validarDados($resultado->capacidade, 'int', true);


                if ($retornoCodigo['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoCodigo['codigoHelper'],
                        'campo' => 'Codigo',
                        'msg' => $retornoCodigo['msg']
                    ];
                }
                if ($retornoDescricao['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoDescricao['codigoHelper'],
                        'campo' => 'Descricao',
                        'msg' => $retornoDescricao['msg']
                    ];
                }
                if ($retornoAndar['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoAndar['codigoHelper'],
                        'campo' => 'andar',
                        'msg' => $retornoAndar['msg']
                    ];
                }
                if ($retornoCapacidade['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoCapacidade['codigoHelper'],
                        'campo' => 'capacidade',
                        'msg' => $retornoCapacidade['msg']
                    ];
                }

                if (empty($erros)) {
                    $this->setCodigo($resultado->codigo);
                    $this->setDescricao($resultado->descricao);
                    $this->setAndar($resultado->andar);
                    $this->setCapacidade($resultado->capacidade);

                    $this->load->model('M_sala');
                    $resBanco = $this->M_sala->inserir(
                        $this->getCodigo(),
                        $this->getDescricao(),
                        $this->getAndar(),
                        $this->getCapacidade()
                    );

                    if ($resBanco['codigo'] == 1) {
                        $sucesso = true;
                    } else {
                        $erros[] = [
                            'codigo' => $resBanco['codigo'],
                            'msg' => $resBanco['msg']
                        ];
                    }
                }
            }
        } catch (Exception $e) {
            $erros[] = ['codigo' => 0, 'msg' => 'Erro Inesperado: ' . $e->getMessage()];
        }

        if ($sucesso == true) {
            $retorno = ['sucesso' => $sucesso, 'msg' => 'Sala cadastrada corretamente'];
        } else {
            $retorno = ['sucesso' => $sucesso, 'erros' => $erros];
        }

        echo json_encode($retorno);
    }

    public function consultar()
    {

        $erros = [];
        $sucesso =  false;

        try {
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);
            $lista = [
                "codigo" => '0',
                "descricao" => '0',
                "andar" => '0',
                "capacidade" => '0'
            ];

            if (verificarParametro($resultado, $lista) != 1) {
                $erros[] = ['codigo' => 99, 'msg' => "Campos inexistentes! ou incorretos!"];
            } else {
                $retornoCodigo = validarDadosConsulta($resultado->codigo, 'int');
                $retornoDescricao = validarDadosConsulta($resultado->descricao, 'string');
                $retornoAndar = validarDadosConsulta($resultado->andar, 'int');
                $retornoCapacidade = validarDadosConsulta($resultado->capacidade, 'int');



                if ($retornoCodigo['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoCodigo['codigoHelper'],
                        'campo' => 'Codigo',
                        'msg' => $retornoCodigo['msg']
                    ];
                }
                if ($retornoDescricao['codigoHelper'] != 0) {
                    $erros = [
                        'codigo' => $retornoDescricao['codigoHelper'],
                        'campo' => 'Descrição',
                        'msg' => $retornoDescricao['msg']
                    ];
                }
                if ($retornoAndar['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoAndar['codigoHelper'],
                        'campo' => 'Andar',
                        'msg' => $retornoAndar['msg']
                    ];
                }
                if ($retornoCapacidade['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoCapacidade['codigoHelper'],
                        'campo' => 'capacidade',
                        'msg' => $retornoCapacidade['msg']
                    ];
                }
                if (empty($erros)) {
                    $this->setCodigo($resultado->codigo);
                    $this->setDescricao($resultado->descricao);
                    $this->setAndar($resultado->andar);
                    $this->setCapacidade($resultado->capacidade);

                    $this->load->model('M_sala');
                    $resBanco = $this->M_sala->consultar(
                        $this->getCodigo(),
                        $this->getAndar(),
                        $this->getDescricao(),
                        $this->getCapacidade()
                    );
                    if ($resBanco['codigo'] == 1) {
                        $sucesso = true;
                    } else {
                        $erros[] = [
                            'codigo' => $resBanco['codigo'],
                            'msg' => $resBanco['msg']
                        ];
                    }
                }
            }
        } catch (Exception $e) {
            $erros[] = ['codigo' => 0, 'msg' => 'Erro inesperado: ' . $e->getMessage()];
        }
        if ($sucesso == true) {
            $retorno = [
                'sucesso' => $sucesso,
                'codigo' => $resBanco['codigo'],
                'msg' => $resBanco['msg'],
                'dados' => $resBanco['dados']
            ];
        } else {
            $retorno = ['sucesso' => $sucesso, 'erros' => $erros];
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
                "descricao" => '0',
                "andar" => '0',
                "capacidade" => '0'
            ];


            if (verificarParametro($resultado, $lista) != 1) {
                $erros[] = ['codigo' => 99, 'msg' => "Campos Inexistentes ou incorretos no front end!"];
            } else {
                if (trim($resultado->descricao) == '' && trim($resultado->andar) == '' && trim($resultado->capacidade) == '') {
                    $erros[] = ['codigo' => 12, 'msg' => 'Pelo menos um parametro precisa ser passado para a atualização!'];
                } else {
                    $retornoCodigo = validarDados($resultado->codigo, 'int', true);
                    $retornoDescricao = validarDadosConsulta($resultado->descricao, 'string');
                    $retornoAndar = validarDadosConsulta($resultado->andar, 'int');
                    $retornoCapacidade = validarDadosConsulta($resultado->capacidade, 'int');

                    if ($retornoCodigo['codigoHelper'] != 0) {
                        $erros[] = [
                            'codigo' => $retornoCodigo['codigoHelper'],
                            'campo' => 'Codigo',
                            'msg' => $retornoCodigo['msg']
                        ];
                    }
                    if ($retornoDescricao['codigoHelper'] != 0) {
                        $erros[] = [
                            'codigo' => $retornoDescricao['codigoHelper'],
                            'campo' => 'Descrição',
                            'msg' => $retornoDescricao['msg']
                        ];
                    }
                    if ($retornoAndar['codigoHelper'] != 0) {
                        $erros[] = [
                            'codigo' => $retornoAndar['codigoHelper'],
                            'campo' => 'Andar',
                            'msg' => $retornoAndar['msg']
                        ];
                    }

                    if ($retornoCapacidade['codigoHelper'] != 0) {
                        $erros[] = [
                            'codigo' => $retornoCapacidade['codigoHelper'],
                            'campo' => 'Capacidade',
                            'msg' => $retornoCapacidade['msg']
                        ];
                    }

                    if (empty($erros)) {
                        $this->setCodigo($resultado->codigo);
                        $this->setDescricao($resultado->descricao);
                        $this->setAndar($resultado->andar);
                        $this->setCapacidade($resultado->Capacidade);

                        $this->load->model('M_sala');
                        $resBanco = $this->M_sala->alterar(
                            $this->getCodigo(),
                            $this->getDescricao(),
                            $this->getAndar(),
                            $this->getCapacidade()
                        );

                        if ($resBanco['codigo'] == 1) {
                            $sucesso = true;
                        } else {
                            $erros[] = [
                                'codigo' => $resBanco['codigo'],
                                'msg' => $resBanco['msg']
                            ];
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
                $erros[] = ['codigo' => 99, 'msg' => "Campos Inexistentes ou incorretos no front end!"];
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
                    $this->load->model('M_sala');
                    $resBanco = $this->M_sala->desativar($this->getCodigo());

                    if ($resBanco['codigo'] == 1) {
                        $sucesso = true;
                    } else {
                        $erros[] = [
                            'codigo' => $resBanco['codigo'],
                            'msg' => $resBanco['msg']
                        ];
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
};
