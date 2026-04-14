<?php
defined('BASEPATH') or exit('No direct script access allowed');



/**
 * CONTROLLER: Sala
 * Realiza operações de CRUD (inserir, consultar, alterar, desativar) de salas
 * Comunica-se com o FrontEnd via JSON e valida todos os dados recebidos
 */
class Sala extends CI_Controller
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
99 - Parâmetros passados do front não correspondem ao método
*/

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

    /**
     * MÉTODO: inserir
     * Realiza inserção de uma nova sala no banco de dados
     * Recebe dados JSON do frontend, valida todos os campos e insere no banco
     */
    public function inserir()
    {
        // Inicializa array de erros e flag de sucesso
        $erros = [];
        $sucesso = false;

        try {
            // ====== RECEBER E DECODIFICAR JSON DO FRONTEND ======
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);
            // Define lista de campos obrigatórios esperados
            $lista = [
                "codigo" => '0',
                "descricao" => '0',
                "andar" => '0',
                "capacidade" => '0'
            ];

            // ====== VERIFICAR ESTRUTURA DO JSON ======
            if (verificarParametro($resultado, $lista) != 1) {
                $erros[] = ['codigo' => 99, 'msg' => 'Campos inexistentes ou incorretos no FrontEnd'];
            } else {
                // ====== VALIDAR CADA CAMPO ======
                $retornoCodigo = validarDados($resultado->codigo, 'int', true);
                $retornoDescricao = validarDados($resultado->descricao, 'string', true);
                $retornoAndar = validarDados($resultado->andar, 'int', true);
                $retornoCapacidade = validarDados($resultado->capacidade, 'int', true);

                // ====== VERIFICAR ERROS DE VALIDAÇÃO ======
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

                // ====== INSERIR NO BANCO SE NÃO HÁ ERROS ======
                if (empty($erros)) {
                    // Define os valores dos setters com dados validados
                    $this->setCodigo($resultado->codigo);
                    $this->setDescricao($resultado->descricao);
                    $this->setAndar($resultado->andar);
                    $this->setCapacidade($resultado->capacidade);

                    // Carrega o modelo de sala
                    $this->load->model('M_sala');
                    // Chama método inserir do modelo
                    $resBanco = $this->M_sala->inserir(
                        $this->getCodigo(),
                        $this->getDescricao(),
                        $this->getAndar(),
                        $this->getCapacidade()
                    );

                    // Verifica resultado da inserção no banco
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
            // Captura exceções inesperadas
            $erros[] = ['codigo' => 0, 'msg' => 'Erro Inesperado: ' . $e->getMessage()];
        }

        // ====== PREPARAR RESPOSTA JSON ======
        if ($sucesso == true) {
            $retorno = ['sucesso' => $sucesso, 'msg' => 'Sala cadastrada corretamente'];
        } else {
            $retorno = ['sucesso' => $sucesso, 'erros' => $erros];
        }

        // Envia resposta codificada em JSON
        echo json_encode($retorno);
    }

    /**
     * MÉTODO: consultar
     * Realiza consulta de salas com filtros opcionais
     * Recebe critérios de busca em JSON e retorna dados encontrados
     */
    public function consultar()
    {
        // Inicializa array de erros e flag de sucesso
        $erros = [];
        $sucesso =  false;

        try {
            // ====== RECEBER E DECODIFICAR JSON DO FRONTEND ======
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);
            // Define lista de campos esperados (podem estar vazios para consulta)
            $lista = [
                "codigo" => '0',
                "descricao" => '0',
                "andar" => '0',
                "capacidade" => '0'
            ];

            // ====== VERIFICAR ESTRUTURA DO JSON ======
            if (verificarParametro($resultado, $lista) != 1) {
                $erros[] = ['codigo' => 99, 'msg' => "Campos inexistentes ou incorretos!!"];
            } else {
                // ====== VALIDAR FILTROS (OPCIONAIS) ======
                // Valida os dados, mas sem exigir que sejam preenchidos
                $retornoCodigo = validarDadosConsulta($resultado->codigo, 'int');
                $retornoDescricao = validarDadosConsulta($resultado->descricao, 'string');
                $retornoAndar = validarDadosConsulta($resultado->andar, 'int');
                $retornoCapacidade = validarDadosConsulta($resultado->capacidade, 'int');

                // ====== VERIFICAR ERROS DE VALIDAÇÃO ======
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
                        'campo' => 'capacidade',
                        'msg' => $retornoCapacidade['msg']
                    ];
                }
                
                // ====== EXECUTAR CONSULTA SE NÃO HÁ ERROS ======
                if (empty($erros)) {
                    // Define os filtros da busca
                    $this->setCodigo($resultado->codigo);
                    $this->setDescricao($resultado->descricao);
                    $this->setAndar($resultado->andar);
                    $this->setCapacidade($resultado->capacidade);

                    // Carrega e executa método de consulta do modelo
                    $this->load->model('M_sala');
                    $resBanco = $this->M_sala->consultar(
                        $this->getCodigo(),
                        $this->getAndar(),
                        $this->getDescricao(),
                        $this->getCapacidade()
                    );
                    
                    // Verifica resultado da consulta
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
            // Captura exceções inesperadas
            $erros[] = ['codigo' => 0, 'msg' => 'Erro inesperado: ' . $e->getMessage()];
        }
        
        // ====== PREPARAR RESPOSTA JSON ======
        if ($sucesso == true) {
            // Retorna dados encontrados
            $retorno = [
                'sucesso' => $sucesso,
                'codigo' => $resBanco['codigo'],
                'msg' => $resBanco['msg'],
                'dados' => $resBanco['dados']
            ];
        } else {
            // Retorna erros encontrados
            $retorno = ['sucesso' => $sucesso, 'erros' => $erros];
        }
        
        // Envia resposta codificada em JSON
        echo json_encode($retorno);
    }

    /**
     * MÉTODO: alterar
     * Realiza alteração de dados de uma sala existente
     * Recebe código obrigatório e campos opcionais a atualizar
     */
    public function alterar()
    {
        // Inicializa array de erros e flag de sucesso
        $erros = [];
        $sucesso = false;

        try {
            // ====== RECEBER E DECODIFICAR JSON DO FRONTEND ======
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);
            // Define lista de campos esperados
            $lista = [
                "codigo" => '0',
                "descricao" => '0',
                "andar" => '0',
                "capacidade" => '0'
            ];

            // ====== VERIFICAR ESTRUTURA DO JSON ======
            if (verificarParametro($resultado, $lista) != 1) {
                $erros[] = ['codigo' => 99, 'msg' => "Campos Inexistentes ou incorretos no front end!"];
            } else {
                // ====== VALIDAR SE HÁ PELO MENOS UM CAMPO PARA ATUALIZAR ======
                if (trim($resultado->descricao) == '' && trim($resultado->andar) == '' && trim($resultado->capacidade) == '') {
                    $erros[] = ['codigo' => 12, 'msg' => 'Pelo menos um parametro precisa ser passado para a atualização!'];
                } else {
                    // ====== VALIDAR CAMPOS ======
                    $retornoCodigo = validarDados($resultado->codigo, 'int', true);
                    $retornoDescricao = validarDadosConsulta($resultado->descricao, 'string');
                    $retornoAndar = validarDadosConsulta($resultado->andar, 'int');
                    $retornoCapacidade = validarDadosConsulta($resultado->capacidade, 'int');

                    // ====== VERIFICAR ERROS DE VALIDAÇÃO ======
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

                    // ====== ATUALIZAR NO BANCO SE NÃO HÁ ERROS ======
                    if (empty($erros)) {
                        // Define os valores a serem atualizados
                        $this->setCodigo($resultado->codigo);
                        $this->setDescricao($resultado->descricao);
                        $this->setAndar($resultado->andar);
                        $this->setCapacidade($resultado->capacidade);

                        // Carrega e executa método de alteração do modelo
                        $this->load->model('M_sala');
                        $resBanco = $this->M_sala->alterar(
                            $this->getCodigo(),
                            $this->getDescricao(),
                            $this->getAndar(),
                            $this->getCapacidade()
                        );

                        // Verifica resultado da alteração
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
            // Captura exceções inesperadas
            $erros[] = ['codigo' => 0, 'msg' => 'Erro Inesperado: ' . $e->getMessage()];
        }

        // ====== PREPARAR RESPOSTA JSON ======
        if ($sucesso == true) {
            $retorno = ['sucesso' => $sucesso, 'codigo' => $resBanco['codigo'], 'msg' => $resBanco['msg']];
        } else {
            $retorno = ['sucesso' => $sucesso, 'erros' => $erros];
        }

        // Envia resposta codificada em JSON
        echo json_encode($retorno);
    }

    /**
     * MÉTODO: desativar
     * Realiza desativação (exclusão lógica) de uma sala
     * Recebe apenas o código da sala como parâmetro obrigatório
     */
    public function desativar()
    {
        // Inicializa array de erros e flag de sucesso
        $erros = [];
        $sucesso = false;

        try {
            // ====== RECEBER E DECODIFICAR JSON DO FRONTEND ======
            $json = file_get_contents('php://input');
            $resultado = json_decode($json);
            // Define lista de campos esperados (apenas código)
            $lista = [
                "codigo" => '0'
            ];

            // ====== VERIFICAR ESTRUTURA DO JSON ======
            if (verificarParametro($resultado, $lista) != 1) {
                $erros[] = ['codigo' => 99, 'msg' => "Campos Inexistentes ou incorretos no front end!"];
            } else {
                // ====== VALIDAR CÓDIGO ======
                $retornoCodigo = validarDados($resultado->codigo, 'int', true);

                // ====== VERIFICAR ERROS DE VALIDAÇÃO ======
                if ($retornoCodigo['codigoHelper'] != 0) {
                    $erros[] = [
                        'codigo' => $retornoCodigo['codigoHelper'],
                        'campo' => 'Codigo',
                        'msg' => $retornoCodigo['msg']
                    ];
                }

                // ====== DESATIVAR NO BANCO SE NÃO HÁ ERROS ======
                if (empty($erros)) {
                    // Define o código da sala a desativar
                    $this->setCodigo($resultado->codigo);
                    
                    // Carrega e executa método de desativação do modelo
                    $this->load->model('M_sala');
                    $resBanco = $this->M_sala->desativar($this->getCodigo());

                    // Verifica resultado da desativação
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
            // Captura exceções inesperadas
            $erros[] = ['codigo' => 0, 'msg' => 'Erro inesperado: ' . $e->getMessage()];
        }

        // ====== PREPARAR RESPOSTA JSON ======
        if ($sucesso == true) {
            $retorno = ['sucesso' => $sucesso, 'codigo' => $resBanco['codigo'], 'msg' => $resBanco['msg']];
        } else {
            $retorno = ['sucesso' => $sucesso, 'erros' => $erros];
        }

        // Envia resposta codificada em JSON
        echo json_encode($retorno);
    }
};
