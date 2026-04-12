<?php
defined('BASEPATH') or exit('No direct script access allowed');

function verificarParametro($atributo, $lista)
{
    foreach ($lista as $key => $value) {
        if (array_key_exists($key, get_object_vars($atributo))) {
            $estatus = 1;
        } else {
            $estatus = 0;
            break;
        }
    }

    if (count(get_object_vars($atributo)) != count($lista)) {
        $estatus = 0;
    }

    return $estatus;
}

function validarDados($valor, $tipo, $tamanhoZero = true)
{
    if (is_null($valor) || $valor === '') {
        return array('codigoHelper' => 3, 'msg' => 'Conteudo Zerado');
    }

    if ($tamanhoZero && ($valor === 0 || $valor === '0')) {
        return array('codigoHelper' => 4, 'msg' => 'Conteudo Zerado');
    }

    switch ($tipo) {
        case 'int':
            if (filter_var($valor, FILTER_VALIDATE_INT) === false) {
                return array('codigoHelper' => 5, 'msg' => 'Conteudo não inteiro');
            }
            break;

        case 'string':
            if (!is_string($valor) || trim($valor) === '') {
                return array('codigoHelper' => 6, 'msg' => 'Conteudo não é um texto');
            }
            break;
        case 'date':
            if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $valor, $match)) {
                return array('codigoHelper' => 7, 'msg' => 'Data em formato invalido');
            } else {
                $d = DateTime::createFromFormat('Y-m-d', $valor);
                if (($d->format('Y-m-d') === $valor) == false) {
                    return array('codigoHelper' => 8, 'msg' => 'Data inválida');
                }
            }
            break;
        case 'hora':
            if (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $valor)) {
                return array('codigoHelper' => 7, 'msg' => 'Hora em formato invalido');
            }
            break;
        default:
            return array('codigoHelper' => 0, 'msg' => 'Tipo de dado não definido');
    }

    return array('codigoHelper' => 0, 'msg' => 'Validação Correta!');
}

function consultarDados()
{
    $erros = [];
}


function validarDadosConsulta($valor, $tipo)
{

    if ($valor != "") {
        switch ($tipo) {

            case 'int':
                if (filter_var($valor, FILTER_VALIDATE_INT) === false) {
                    return array('codigoHelper' => 4, 'msg' => 'Conteudo não inteiro!');
                }
                break;
            case 'string':
                if (!is_string($valor) or trim($valor) === '') {
                    return array('codigoHelper' => 5, 'msg' => 'Conteúdo não é texto!');
                }
                break;
            case 'date':
                if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $valor, $match)) {
                    return array('codigoHelper' => 6, 'msg' => 'Data em formato invalido');
                } else {
                    $d = DateTime::createFromFormat('Y-m-d', $valor);
                    if (($d->format('Y-m-d') === $valor) === false) {
                        return array('codigoHelper' => 6, 'msg' => 'Data inválida');
                    }
                }
                break;
            case 'hora':
                if (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $valor)) {
                    return array('codigoHelper' => 7, 'msg' => 'Hora em formato invalido');
                }
                break;
            default:
                return array('codigoHelper' => 97, 'msg' => 'Tipo de dado não definido');
        }
    }
    return array('codigoHelper' => 0, 'msg' => 'Validação Correta!');
}

function compararDataHora($valorInicial, $valorFinal, $tipo ){
    $valorInicial = strtotime($valorInicial);
    $valorFinal = strtotime($valorFinal);

    if ($valorInicial != '' && $valorFinal != '' ){
        if ($valorInicial > $valorFinal){
            switch($tipo){
                case 'hora':
                    return array('codigoHelper' => 13, 'msg' => 'Hora final menor que a hora inicial');
                    break;
                case 'data':
                    return array('codigoHelper' => 14, 'msg' => 'Data final menor que a data inicial');
                    break;
                    default:
                    return array('codigoHelper' => 97, 'msg' => 'Tipo de verificação não definida');
            }
        }
    }
    return array('codigoHelper' => 0, 'msg' => 'Validação correta!');
}
