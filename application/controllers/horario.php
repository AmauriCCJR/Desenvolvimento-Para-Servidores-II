<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Horario extends CI_Controller
{

    /*
Validação dos tipos de retornos nas validações (Código de erro)
1 - Operação realizada no banco de dados com sucesso (Inserção, Alteração, Consulta ou Exclusão)
2 - Conteúdo passado nulo ou vazio
3 - Conteúdo zerado
4 - Conteúdo não inteiro
5 - Conteúdo não é um texto
6 - Data em formato inválido
7 - Hora em formato inválido
12 - Na atualização, pelo menos um atributo deve ser passado
13 - Hora Final menor que a Hora Inicial
14 - Data Final menor que a Data Inicial.
99 - Parâmetros passados do front não correspondem ao método
*/

    private $codigo;
    private $descricao;
    private $horaInicial;
    private $horaFinal;
    private $estatus;

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function getHoraInicial()
    {
        return $this->horaInicial;
    }

    public function getHoraFinal()
    {
        return $this->horaFinal;
    }

    public function getEstatus()
    {
        return $this->estatus;
    }

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function setHoraInicial($horaInicial)
    {
        $this->horaInicial = $horaInicial;
    }

    public function setHoraFinal($horaFinal)
    {
        $this->horaFinal = $horaFinal;
    }

    public function setEstatus($estatus)
    {
        $this->estatus = $estatus;
    }

    public function inserir()
    {
        $erros = [];
        $sucesso = false;

        try {
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);
            $lista = [
                "descricao" => '0',
                "horaInicial" => '0',
                "horaFinal" => '0'
            ];

            if (verificarParametro($resultado, $lista) != 1) {
                $erros[] = ['codigo' => 99, 'msg' => 'Campos inexistentes ou incorretos no frontEnd'];
            } else {
                $retornoDescricao = validarDados($resultado->descricao, 'string', true);
                $retornoHoraInicial = validarDados($resultado->horaInicial, 'hora', true);
                $retornoHoraFinal = validarDados($resultado->horaFinal, 'hora', true);
                $retornoComparacaoHoras = compararDataHora($resultado->horaInicial, $resultado->horaFinal, 'hora');

                if ($retornoDescricao['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoDescricao['codigoHelper'],
                        'campo' => 'Descrição',
                        'msg' => $retornoDescricao['msg']
                    ];
                }

                if ($retornoHoraInicial['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoHoraInicial['codigoHelper'],
                        'campo' => 'Hora Inicial',
                        'msg' => $retornoHoraInicial['msg']
                    ];
                }

                if ($retornoHoraFinal['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoHoraFinal['codigoHelper'],
                        'campo' => 'Hora Final',
                        'msg' => $retornoHoraFinal['msg']
                    ];
                }

                // Validar se a hora inicial é maior que a hora final
                if ($retornoComparacaoHoras['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoComparacaoHoras['codigoHelper'],
                        'campo' => 'Hora Inicial e Hora Final',
                        'msg' => $retornoComparacaoHoras['msg']
                    ];
                }

                //Se não encontrar erros
                if (empty($erros)) {
                    $this->setDescricao($resultado->descricao);
                    $this->setHoraInicial($resultado->horaInicial);
                    $this->setHoraFinal($resultado->horaFinal);

                    $this->load->model('M_horario');
                    $resBanco = $this->M_horario->inserir(
                        $this->getDescricao(),
                        $this->getHoraInicial(),
                        $this->getHoraFinal()
                    );

                    if ($resBanco['codigo'] == 1) {
                        $sucesso = true;
                    } else {
                        $erros[] = [
                            "codigo" => $resBanco['codigo'],
                            "msg" => $resBanco['msg']
                        ];
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
                "descricao" => '0',
                "horaInicial" => '0',
                "horaFinal" => '0'
            ];

            if (verificarParametro($resultado, $lista) != 1) {
                // Validar vindos de forma correta do frontend (Helper)
                $erros[] = ['codigo' => 99, 'msg' => 'Campos inexistentes ou incorretos no FrontEnd.'];
            } else {
                // Validar campos quanto ao tipo de dado e tamanho (Helper)
                $retornoCodigo = validarDadosConsulta($resultado->codigo, 'int');
                $retornoDescricao = validarDadosConsulta($resultado->descricao, 'string');
                $retornoHoraInicial = validarDadosConsulta($resultado->horaInicial, 'hora');
                $retornoHoraFinal = validarDadosConsulta($resultado->horaFinal, 'hora');
                $retornoComparacaoHoras = compararDataHora($resultado->horaInicial, $resultado->getHoraFinal, 'hora');

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

                if ($retornoHoraInicial['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoHoraInicial['codigoHelper'],
                        'campo' => 'Hora Inicial',
                        'msg' => $retornoHoraInicial['msg']
                    ];
                }

                if ($retornoHoraFinal['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoHoraFinal['codigoHelper'],
                        'campo' => 'Hora Final',
                        'msg' => $retornoHoraFinal['msg']
                    ];
                }

                // Validar se a hora inicial é maior que a hora final
                if ($retornoComparacaoHoras['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoComparacaoHoras['codigoHelper'],
                        'campo' => 'Hora Inicial e Hora Final',
                        'msg' => $retornoComparacaoHoras['msg']
                    ];
                }
            }

            //Se não encontrar erros
            if (empty($erros)) {
                $this->setCodigo($resultado->codigo);
                $this->setDescricao($resultado->descricao);
                $this->setHoraInicial($resultado->horaInicial);
                $this->setHoraFinal($resultado->horaFinal);

                $this->load->model('M_horario');
                $resBanco = $this->M_horario->consultar(
                    $this->getCodigo(),
                    $this->getDescricao(),
                    $this->getHoraInicial(),
                    $this->getHoraFinal()
                );


                if ($resBanco['codigo'] == 1) {
                    $sucesso = true;
                } else {
                    $erros[] = [
                        "codigo" => $resBanco['codigo'],
                        "msg" => $resBanco['msg']
                    ];
                }
            }
        } catch (Exception $e) {
            $erros[] = ['codigo' => 0, 'msg' => 'Erro inesperado: ' . $e->getMessage()];
        }

        if ($sucesso == true) {
            $retorno = ['sucesso' => $sucesso, 'codigo' => $resBanco['codigo'], 'msg' => $resBanco['msg'], 'dados' => $resBanco['dados']];
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
                "horaInicial" => '0',
                "horaFinal" => '0',
            ];

            if (verificarParametro($resultado, $lista) != 1) {
                // Validar vindos de forma correta do frontend (Helper)
                $erros[] = ['codigo' => 99, 'msg' => 'Campos inexistentes ou incorretos no FrontEnd.'];
            } else {
                if (trim($resultado->descricao) == '' && trim($resultado->horaInicial) == '' && trim($resultado->horaFinal) == '') {
                    $erros[] = ['codigo' => 12, 'msg' => 'Pelo menos um campo deve ser informado para atualização.'];
                } else {
                    $retornoCodigo = validarDadosConsulta($resultado->codigo, 'int', true);
                    $retornoDescricao = validarDadosConsulta($resultado->descricao, 'string');
                    $retornoHoraInicial = validarDadosConsulta($resultado->horaInicial, 'hora');
                    $retornoHoraFinal = validarDadosConsulta($resultado->horaFinal, 'hora');
                    $retornoEstatus = validarDadosConsulta($resultado->estatus, 'int');
                    $retornoComparacaoHoras = compararDataHora($resultado->horaInicial, $resultado->horaFinal, 'hora');


                    // Validar campos quanto ao tipo de dado e tamanho (Helper)
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

                    if ($retornoHoraInicial['codigoHelper'] != 0) {
                        $erros[] = [
                            'codigo' => $retornoHoraInicial['codigoHelper'],
                            'campo' => 'Hora Inicial',
                            'msg' => $retornoHoraInicial['msg']
                        ];
                    }

                    if ($retornoHoraFinal['codigoHelper'] != 0) {
                        $erros[] = [
                            'codigo' => $retornoHoraFinal['codigoHelper'],
                            'campo' => 'Hora Final'
                        ];
                    };
                    if ($retornoComparacaoHoras['codigoHelper'] != 0) {
                        $erros[] = [
                            'codigo' => $retornoComparacaoHoras['codigoHelper'],
                            'campo' => 'Hora Inicial e Hora Final',
                            'msg' => $retornoComparacaoHoras['msg']
                        ];
                    }
                    if (empty($erros)) {
                        $this->setCodigo($resultado->codigo);
                        $this->setDescricao($resultado->descricao);
                        $this->setHoraInicial($resultado->horaInicial);
                        $this->setHoraFinal($resultado->horaFinal);

                        $this->load->model('M_horario');
                        $resBanco = $this->M_horario->alterar(
                            $this->getCodigo(),
                            $this->getDescricao(),
                            $this->getHoraInicial(),
                            $this->getHoraFinal(),
                        );

                        if ($resBanco['codigo'] == 1) {
                            $sucesso = true;
                        } else {
                            $erros[] = [
                                "codigo" => $resBanco['codigo'],
                                "msg" => $resBanco['msg']
                            ];
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $erros[] = ['codigo' => 0, 'msg' => 'Erro inesperado: ' . $e->getMessage()];
        }

        if($sucesso == true){
            $retorno = ['sucesso' => $sucesso, 'codigo' => $resBanco['codigo'], 'msg' => $resBanco['msg']];
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
            $lista = [
                "codigo" => '0'
            ];

            if (verificarParametro($resultado, $lista) != 1) {
                // Validar vindos de forma correta do frontend (Helper)
                $erros[] = ['codigo' => 99, 'msg' => 'Campos inexistentes ou incorretos no FrontEnd.'];
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

                    $this->load->model('M_horario');
                    $resBanco = $this->M_horario->desativar($this->getCodigo());

                    if ($resBanco['codigo'] == 1) {
                        $sucesso = true;
                    } else {
                        $erros[] = [
                            "codigo" => $resBanco['codigo'],
                            "msg" => $resBanco['msg']
                        ];
                    }
                }
             }
        } catch (Exception $e) {
            $erros[] = ['codigo' => 0, 'msg' => 'Erro inesperado: ' . $e->getMessage()];
        }

        if($sucesso == true){
            $retorno = ['sucesso' => $sucesso, 'codigo' => $resBanco['codigo'], 'msg' => $resBanco['msg']];
        } else {
            $retorno = ['sucesso' => $sucesso, 'erros' => $erros];
        }

        echo json_encode($retorno);
    }
}
