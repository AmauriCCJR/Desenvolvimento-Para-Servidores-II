<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mapa extends CI_Controller
{

    /*
1 - operação realizada no bancod de dados com sucesso
2 - conteudo vazio ou nulo
3 - conteudo zerado
4 - conteudo não inteiro
5 - conteudo não é um texto
6 - data em formato inválido
12 - na atualização, pelo menos um atributo precisa ser passado
99 - parametros passados no frontend não correspondem ao metodo

*/

    private $codigo;
    private $dataReserva;
    private $codigo_sala;
    private $codigo_horario;
    private $codigo_turma;
    private $codigo_professor;
    private $estatus;

    private $dataInicio;
    private $dataFim;
    private $diaSemana;


   

    // Getters e Setters

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function setCodigo($codigoFront)
    {
        $this->codigo = $codigoFront;
    }

    public function getDataReserva()
    {
        return $this->dataReserva;
    }

    public function setDataReserva($dataReservaFront)
    {
        $this->dataReserva = $dataReservaFront;
    }

    public function getCodigoSala()
    {
        return $this->codigo_sala;
    }

    public function setCodigoSala($codigo_salaFront)
    {
        $this->codigo_sala = $codigo_salaFront;
    }

    public function getCodigoHorario()
    {
        return $this->codigo_horario;
    }

    public function setCodigoHorario($codigo_horarioFront)
    {
        $this->codigo_horario = $codigo_horarioFront;
    }

    public function getCodigoTurma()
    {
        return $this->codigo_turma;
    }

    public function setCodigoTurma($codigo_turmaFront)
    {
        $this->codigo_turma = $codigo_turmaFront;
    }

    public function getCodigoProfessor()
    {
        return $this->codigo_professor;
    }

    public function setCodigoProfessor($codigo_professorFront)
    {
        $this->codigo_professor = $codigo_professorFront;
    }

    public function getEstatus()
    {
        return $this->estatus;
    }

    public function setEstatus($estatusFront)
    {
        $this->estatus = $estatusFront;
    }

    public function getDataInicio()
    {
        return $this->dataInicio;
    }

    public function setDataInicio($dataInicioFront)
    {
        $this->dataInicio = $dataInicioFront;
    }

    public function getDataFim()
    {
        return $this->dataFim;
    }

    public function setDataFim($dataFimFront)
    {
        $this->dataFim = $dataFimFront;
    }

    public function getDiaSemana()
    {
        return $this->diaSemana;
    }

    public function setDiaSemana($diaSemanaFront)
    {
        $this->diaSemana = $diaSemanaFront;
    }


    public function inserir()
    {

        $erros = [];
        $sucesso = false;


        try {
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);
            $lista = [
                "dataReserva" => '0',
                "codSala" => '0',
                "codHorario" => '0',
                "codTurma" => '0',
                "codProfessor" => '0'
            ];

            if (verificarParametro($resultado, $lista) != 1) {
                $erros[] = ['codigo' => 99, 'msg' => 'Campos inexistentes ou incorretos no FrontEnd'];
            } else {
                $retornoDataReserva = validarDados($resultado->dataReserva, 'date', true);
                $retornoCodSala = validarDados($resultado->codSala, 'int', true);
                $retornoCodHorario = validarDados($resultado->codHorario, 'int', true);
                $retornoCodTurma = validarDados($resultado->codTurma, 'int', true);
                $retornoCodProfessor = validarDados($resultado->codProfessor, 'int', true);

                if ($retornoDataReserva['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoDataReserva['codigoHelper'],
                        'campo' => 'Data da Reserva',
                        'msg' => $retornoDataReserva['msg']
                    ];
                }

                if ($retornoCodSala['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoCodSala['codigoHelper'],
                        'campo' => 'Codigo da Sala',
                        'msg' => $retornoCodSala['msg']
                    ];
                }

                if ($retornoCodHorario['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoCodHorario['codigoHelper'],
                        'campo' => 'Codigo do Horario',
                        'msg' => $retornoCodHorario['msg']
                    ];
                }

                if ($retornoCodTurma['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoCodTurma['codigoHelper'],
                        'campo' => 'Codigo da Turma',
                        'msg' => $retornoCodTurma['msg']
                    ];
                }

                if ($retornoCodProfessor['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoCodProfessor['codigoHelper'],
                        'campo' => 'Codigo do Professor',
                        'msg' => $retornoCodProfessor['msg']
                    ];
                }

                if (empty($erros)) {
                    $this->setDataReserva($resultado->dataReserva);
                    $this->setCodigoSala($resultado->codSala);
                    $this->setCodigoHorario($resultado->codHorario);
                    $this->setCodigoTurma($resultado->codTurma);
                    $this->setCodigoProfessor($resultado->codProfessor);




                    
                    $this->load->model('M_mapa');
                    $resBanco = $this->M_mapa->inserir(
                        $this->getDataReserva(),
                        $this->getCodigoSala(),
                        $this->getCodigoTurma(),
                        $this->getCodigoProfessor(),
                        $this->getCodigoHorario(),
                        
                        
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
            };
        } catch (Exception $e) {
            $erros[] = [
                'codigo' => 0,
                'msg' => 'Atenção: O seguinte erro aconteceu -> ' . $e->getMessage()
            ];
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
                "dataReserva" => '0',
                "codSala" => '0',
                "codHorario" => '0',
                "codTurma" => '0',
                "codProfessor" => '0'
            ];


            if (verificarParametro($resultado, $lista) != 1) {
                $erros[] = ['codigo' => 99, 'msg' => 'Campos inexistentes ou incorretos no FrontEnd'];
            } else {
                $retornoCodigo = validarDadosConsulta($resultado->codigo, 'int');
                $retornoDataReserva = validarDadosConsulta($resultado->dataReserva, 'date');
                $retornoCodSala = validarDadosConsulta($resultado->codSala, 'int');
                $retornoCodHorario = validarDadosConsulta($resultado->codHorario, 'int');
                $retornoCodTurma = validarDadosConsulta($resultado->codTurma, 'int');
                $retornoCodProfessor = validarDadosConsulta($resultado->codProfessor, 'int');

                if ($retornoCodigo['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoCodigo['codigoHelper'],
                        'campo' => 'Codigo',
                        'msg' => $retornoCodigo['msg']
                    ];
                }

                if ($retornoDataReserva['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoDataReserva['codigoHelper'],
                        'campo' => 'Data da Reserva',
                        'msg' => $retornoDataReserva['msg']
                    ];
                }

                if ($retornoCodSala['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoCodSala['codigoHelper'],
                        'campo' => 'Codigo da Sala',
                        'msg' => $retornoCodSala['msg']
                    ];
                }

                if ($retornoCodHorario['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoCodHorario['codigoHelper'],
                        'campo' => 'Codigo do Horário',
                        'msg' => $retornoCodHorario['msg']
                    ];
                }

                if ($retornoCodTurma['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoCodTurma['codigoHelper'],
                        'campo' => 'Codigo da Turma',
                        'msg' => $retornoCodTurma['msg']
                    ];
                }

                if ($retornoCodProfessor['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoCodProfessor['codigoHelper'],
                        'campo' => 'Codigo do Professor',
                        'msg' => $retornoCodProfessor['msg']
                    ];
                }

                if (empty($erros)) {
                    $this->setCodigo($resultado->codigo);
                    $this->setDataReserva($resultado->dataReserva);
                    $this->setCodigoSala($resultado->codSala);
                    $this->setCodigoHorario($resultado->codHorario);
                    $this->setCodigoTurma($resultado->codTurma);
                    $this->setCodigoProfessor($resultado->codProfessor);

                    $this->load->model('M_mapa');
                    $resBanco = $this->M_mapa->consultar(
                        $this->getCodigo(),
                        $this->getDataReserva(),
                        $this->getCodigoSala(),
                        $this->getCodigoHorario(),
                        $this->getCodigoTurma(),
                        $this->getCodigoProfessor()
                    );

                    if ($resBanco['codigo'] == 1) {
                        $sucesso = true;
                    } else {
                        $erros = [
                            'codigo' => $resBanco['codigo'],
                            'msg' => $resBanco['msg']
                        ];
                    }
                }
            }
        } catch (Exception $e) {
            $erros[] = [
                'codigo' => 0,
                'msg' => 'Atençao: O seguinte erro aconteceu -> ' . $e->getMessage()
            ];
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
                "dataReserva" => '0',
                "codSala" => '0',
                "codHorario" => '0',
                "codTurma" => '0',
                "codProfessor" => '0'
            ];

            if (verificarParametro($resultado, $lista) != 1) {
                $erros[] = ['codigo' => 99, 'msg' => 'Campos inexistentes ou incorretos no FrontEnd'];
            } else {
                if (
                    trim($resultado->dataReserva) == '' && trim($resultado->codSala) == '' && trim($resultado->codHorario) == ''
                    && trim($resultado->codTurma) == '' && trim($resultado->codProfessor) == ''
                ) {
                    $erros[] = ['codigo' => 12, 'msg' => 'Pelo menos um parametro precisa ser passado para a atualização!'];
                } else {
                    $retornoCodigo = validarDados($resultado->codigo, 'int', true);
                    $retornoDataReserva = validarDadosConsulta($resultado->dataReserva, 'date');
                    $retornoCodSala = validarDadosConsulta($resultado->codSala, 'int');
                    $retornoCodHorario = validarDadosConsulta($resultado->codHorario, 'int');
                    $retornoCodTurma = validarDadosConsulta($resultado->codTurma, 'int');
                    $retornoCodProfessor = validarDadosConsulta($resultado->codProfessor, 'int');

                    if ($retornoCodigo['codigoHelper'] != 0) {
                        $erros[] = [
                            'codigo' => $retornoCodigo['codigoHelper'],
                            'campo' => 'Codigo',
                            'msg' => $retornoCodigo['msg']
                        ];
                    }

                    if ($retornoDataReserva['codigoHelper'] != 0) {
                        $erros[] = [
                            'codigo' => $retornoDataReserva['codigoHelper'],
                            'campo' => 'Data da Reserva',
                            'msg' => $retornoDataReserva['msg']
                        ];
                    }

                    if ($retornoCodSala['codigoHelper'] != 0) {
                        $erros[] = [
                            'codigo' => $retornoCodSala['codigoHelper'],
                            'campo' => 'Codigo da Sala',
                            'msg' => $retornoCodSala['msg']
                        ];
                    }

                    if ($retornoCodHorario['codigoHelper'] != 0) {
                        $erros[] = [
                            'codigo' => $retornoCodHorario['codigoHelper'],
                            'campo' => 'Codigo do Horário',
                            'msg' => $retornoCodHorario['msg']
                        ];
                    }

                    if ($retornoCodTurma['codigoHelper'] != 0) {
                        $erros[] = [
                            'codigo' => $retornoCodTurma['codigoHelper'],
                            'campo' => 'Codigo da Turma',
                            'msg' => $retornoCodTurma['msg']
                        ];
                    }

                    if ($retornoCodProfessor['codigoHelper'] != 0) {
                        $erros[] = [
                            'codigo' => $retornoCodProfessor['codigoHelper'],
                            'campo' => 'Codigo do Professor',
                            'msg' => $retornoCodProfessor['msg']
                        ];
                    }

                    if (empty($erros)) {
                        $this->setCodigo($resultado->codigo);
                        $this->setDataReserva($resultado->dataReserva);
                        $this->setCodigoSala($resultado->codSala);
                        $this->setCodigoHorario($resultado->codHorario);
                        $this->setCodigoTurma($resultado->codTurma);
                        $this->setCodigoProfessor($resultado->codProfessor);

                        $this->load->model('M_mapa');
                        $resBanco = $this->M_mapa->alterar(
                            $this->getCodigo(),
                            $this->getDataReserva(),
                            $this->getCodigoSala(),
                            $this->getCodigoHorario(),
                            $this->getCodigoTurma(),
                            $this->getCodigoProfessor()
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
            $erros[] = [
                'codigo' => 0,
                'msg' => 'Atençao: O seguinte erro aconteceu -> ' . $e->getMessage()
            ];
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

                    $this->load->model('M_mapa');
                    $resBanco = $this->M_mapa->desativar($this->getCodigo());

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
            $erros[] = [
                'codigo' => 0,
                'msg' => 'Atençao: O seguinte erro aconteceu -> ' . $e->getMessage()
            ];
        }

        if ($sucesso == true) {
            $retorno = ['sucesso' => $sucesso, 'codigo' => $resBanco['codigo'], 'msg' => $resBanco['msg']];
        } else {
            $retorno = ['sucesso' => $sucesso, 'erros' => $erros];
        }

        echo json_encode($retorno);

}
}
