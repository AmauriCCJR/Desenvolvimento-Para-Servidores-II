<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Função: verificarParametro
 * Verifica se todos os atributos esperados existem em um objeto
 * Retorna 1 se válido, 0 se inválido
 */
function verificarParametro($atributo, $lista)
{
    // Itera sobre cada item esperado na lista
    foreach ($lista as $key => $value) {
        // Verifica se cada chave existe como propriedade do objeto
        if (array_key_exists($key, get_object_vars($atributo))) {
            $estatus = 1;
        } else {
            // Se alguma chave não existir, marca como inválido
            $estatus = 0;
            break;
        }
    }

    // Verifica se a quantidade de atributos do objeto é igual à esperada
    if (count(get_object_vars($atributo)) != count($lista)) {
        $estatus = 0;
    }

    return $estatus;
}

/**
 * Função: validarDados
 * Valida se um valor é válido conforme seu tipo (int, string, date, hora)
 * Retorna array com código e mensagem de validação
 */
function validarDados($valor, $tipo, $tamanhoZero = true)
{
    // Verifica se o valor é nulo ou vazio
    if (is_null($valor) || $valor === '') {
        return array('codigoHelper' => 3, 'msg' => 'Conteudo Zerado');
    }

    // Verifica se o valor é zero (se tamanhoZero for true)
    if ($tamanhoZero && ($valor === 0 || $valor === '0')) {
        return array('codigoHelper' => 4, 'msg' => 'Conteudo Zerado');
    }

    // Valida conforme o tipo de dado definido
    switch ($tipo) {
        // Validação para tipo inteiro
        case 'int':
            if (filter_var($valor, FILTER_VALIDATE_INT) === false) {
                return array('codigoHelper' => 5, 'msg' => 'Conteudo não inteiro');
            }
            break;

        // Validação para tipo string (texto)
        case 'string':
            if (!is_string($valor) || trim($valor) === '') {
                return array('codigoHelper' => 6, 'msg' => 'Conteudo não é um texto');
            }
            break;
        
        // Validação para tipo data (formato Y-m-d)
        case 'date':
            // Verifica o formato com regex
            if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $valor, $match)) {
                return array('codigoHelper' => 7, 'msg' => 'Data em formato invalido');
            } else {
                // Cria um objeto DateTime e valida se a data existe
                $d = DateTime::createFromFormat('Y-m-d', $valor);
                if (($d->format('Y-m-d') === $valor) == false) {
                    return array('codigoHelper' => 8, 'msg' => 'Data inválida');
                }
            }
            break;
        
        // Validação para tipo hora (formato HH:mm)
        case 'hora':
            // Valida formato de hora com regex (00:00 até 23:59)
            if (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $valor)) {
                return array('codigoHelper' => 7, 'msg' => 'Hora em formato invalido');
            }
            break;
        
        // Se o tipo não for reconhecido
        default:
            return array('codigoHelper' => 0, 'msg' => 'Tipo de dado não definido');
    }

    // Se passou em todas as validações, retorna sucesso
    return array('codigoHelper' => 0, 'msg' => 'Validação Correta!');
}

/**
 * Função: consultarDados
 * Função não implementada - destinada para consultar dados
 * Atualmente apenas inicializa um array vazio
 */
function consultarDados()
{
    // Array para armazenar erros (não utilizado no momento)
    $erros = [];
}


/**
 * Função: validarDadosConsulta
 * Valida dados especificamente para consultas (similar a validarDados)
 * Apenas valida se o valor não está vazio
 */
function validarDadosConsulta($valor, $tipo)
{
    // Verifica se o valor não está vazio antes de validar
    if ($valor != "") {
        // Valida conforme o tipo de dado definido
        switch ($tipo) {

            // Validação para tipo inteiro
            case 'int':
                if (filter_var($valor, FILTER_VALIDATE_INT) === false) {
                    return array('codigoHelper' => 4, 'msg' => 'Conteudo não inteiro!');
                }
                break;
            
            // Validação para tipo string (texto)
            case 'string':
                if (!is_string($valor) or trim($valor) === '') {
                    return array('codigoHelper' => 5, 'msg' => 'Conteúdo não é texto!');
                }
                break;
            
            // Validação para tipo data (formato Y-m-d)
            case 'date':
                // Verifica o formato com regex
                if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $valor, $match)) {
                    return array('codigoHelper' => 6, 'msg' => 'Data em formato invalido');
                } else {
                    // Cria um objeto DateTime e valida se a data existe
                    $d = DateTime::createFromFormat('Y-m-d', $valor);
                    if (($d->format('Y-m-d') === $valor) === false) {
                        return array('codigoHelper' => 6, 'msg' => 'Data inválida');
                    }
                }
                break;
            
            // Validação para tipo hora (formato HH:mm)
            case 'hora':
                // Valida formato de hora com regex (00:00 até 23:59)
                if (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $valor)) {
                    return array('codigoHelper' => 7, 'msg' => 'Hora em formato invalido');
                }
                break;
            
            // Se o tipo não for reconhecido
            default:
                return array('codigoHelper' => 97, 'msg' => 'Tipo de dado não definido');
        }
    }
    // Se passou em todas as validações, retorna sucesso
    return array('codigoHelper' => 0, 'msg' => 'Validação Correta!');
}

/**
 * Função: compararDataHora
 * Compara duas datas ou horas para verificar se a data/hora inicial é menor que a final
 * Retorna erro se a data/hora final for menor que a inicial
 */
function compararDataHora($valorInicial, $valorFinal, $tipo ){
    // Converte as strings para timestamp Unix
    $valorInicial = strtotime($valorInicial);
    $valorFinal = strtotime($valorFinal);

    // Verifica se ambos os valores foram convertidos com sucesso
    if ($valorInicial != '' && $valorFinal != '' ){
        // Se a data/hora inicial for maior que a final, há erro
        if ($valorInicial > $valorFinal){
            // Retorna mensagem de erro específica conforme o tipo
            switch($tipo){
                // Para tipo hora
                case 'hora':
                    return array('codigoHelper' => 13, 'msg' => 'Hora final menor que a hora inicial');
                    break;
                
                // Para tipo data
                case 'data':
                    return array('codigoHelper' => 14, 'msg' => 'Data final menor que a data inicial');
                    break;
                
                // Se o tipo não for reconhecido
                default:
                    return array('codigoHelper' => 97, 'msg' => 'Tipo de verificação não definida');
            }
        }
    }
    // Se passou em todas as verificações, retorna sucesso
    return array('codigoHelper' => 0, 'msg' => 'Validação correta!');
}
