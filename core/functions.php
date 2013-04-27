<?php 
/*
 * Copyright (c) 2011, Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * All rights reserved.
 */


/**
 * Define algumas fun��es que ser�o utilizadas pelo framework 
 */


if(!function_exists('e'))
{
	/**
	 * Imprime um conte�do
	 * @param string $string	valor a ser impresso
	 */
	function e($string)
	{
		echo $string;
	}
}

/**
 * Carrega automaticamente uma classe caso a mesma seja inst�ncia e n�o seja importada ainda
 * @param string $class		nome da classe
 * @return void
 */
function __autoload($class)
{
	$files[] = root . 'core/libs/'. $class .'.php';
	$files[] = root . 'core/libs/exceptions/'. $class .'.php';
	$files[] = root . 'app/models/'. $class .'.php';
	$files[] = root . 'app/controllers/'. $class .'.php';
	$files[] = root . 'app/helpers/'. $class .'.php';
	
	foreach($files as $file)
	{
		if(file_exists($file))
		{
			require_once($file);
			return;
		}
	}
}

/**
 * Converte 'test-controller' para 'TestController'
 * @param string $string	valor a ser convertido
 * @return string			valor convertido
 */
function camelize($string) 
{
    return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
}

/**
 * Converte 'TestController' para 'test-controller'
 * @param string $string	valor a ser convertido
 * @return string			valor convertido
 */
function uncamelize($string)
{
	return trim(strtolower(preg_replace("/([A-Z])/", "-$1", $string)), '-');
}

/**
 * Converte 'test-controller' para 'Test Controller'
 * @param string $string	valor a ser convertido
 * @return string			valor convertido
 */
function humanize($string)
{
	return ucwords(str_replace('-', ' ', $string));
}

/**
 * Converte 'T�tulo de Exemplo' para 'titulo-de-exemplo'
 * @param string $string	valor a ser convertido
 * @return string			retorna o valor convertido
 */
function slugify($string)
{
	$string = html_entity_decode($string);

	$a = '�����������������������������������������������������';
	$b = 'AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn';
	$string = strtr($string, $a, $b);

	$ponctu = array("?", ".", "!", ",");
	$string = str_replace($ponctu, "", $string);

	$string = trim($string);
	$string = strtolower($string);
	$string = preg_replace('/([^a-z0-9]+)/i', '-', $string);

	if (!empty($string))
		$string = utf8_encode($string);

	return $string;
}

/**
 * Executa a fun��o print_r com a tag <pre>
 * @param mixed $struct		estrutura a ser impressa
 * @return void
 */
function pr($struct)
{
	echo '<pre>';
	print_r($struct);
	echo '</pre>';
}

/**
 * Cria e retorna o caractere de tabula��o
 * @param int $n	quantidade de vezes que desejar dar tabula��o
 * @retun string	retorna a tabula��o
 */
function tab($n = 1)
{
	return str_repeat("\t", $n);
}

/**
 * Cria e retorna espe�acos em branco
 * @param int $n	quantidade de espa�os que deseja criar
 * @return string	retorna os espa�os
 */
function t($n = 1)
{
	return str_repeat('&nbsp;', ($n * 5));
}

/**
 * Cria uma inst�ncia de stdClass com a propriedade 'd', que recebe o valor informado no par�metro
 * @param object $object	objeto que ser� valor da propridade 'd'
 * @return stdClass			retorna uma inst�ncia de stdClass
 */
function d($object)
{
	$d = new stdClass;
	$d->d = $object;
	return $d;
}

/**
 * Converte um objeto ou um array em uma string XML
 * @param mixed $data		dados a serem convertidos em XML
 * @return string			retorna uma string XML
 */
function xml_encode($data)
{
	if (!is_array($data) && !is_object($data)) 
		return $data;
		
	$encoded = "\n";
	foreach($data as $k => $d)
	{
		$e = is_string($k) ? $k : 'n';
		$encoded .= "\t<". $e .">". xml_encode($d) ."</". $e .">\n";
	}
	return $encoded . "";
}

/**
 * Codifica os valores de um array ou um objeto em UTF-8
 * @param mixed $data		dados a serem convertidos
 * @return mixed			retorna o array ou objeto convertido
 */
function utf8encode($data)
{
	if(is_string($data))
		return utf8_encode($data);
	if (is_array($data))
	{
		$encoded = array();
		foreach($data as $k => $d)
			$encoded[$k] = utf8encode($d);
		return $encoded;
	}
	if (is_object($data))
	{
		$encoded = new stdClass;
		foreach($data as $k => $d)
			$encoded->{$k} = utf8encode($d);
		return $encoded;
	}
	return $data;
}

/**
 * Decodifica os valores de um array ou objeto de UTF-8
 * @param mixed $data		dados a serem decodificados
 * @return mixed			retorna um objeto ou array sem a codifica��o UTF-8
 */
function utf8decode($data)
{
	if(is_string($data))
		return utf8_decode($data);
	if (is_array($data))
	{
		$encoded = array();
		foreach($data as $k => $d)
			$encoded[$k] = utf8decode($d);
		return $encoded;
	}
	if(is_object($data))
	{
		$encoded = new stdClass;
		foreach($data as $k => $d)
			$encoded->{$k} = utf8decode($d);
		return $encoded;
	}
	return $data;
}

/**
 * Une dois ou mais array
 * @param array $array1	primeiro array
 * @param array $array2	segundo array
 * @param array $arrayN en�ssimo array
 * @return array		retorna um array com uni�o dos demais
 */
function array_union()
{
	$args = func_get_args();
	$new_array = array();
	foreach($args as $array)
	{
		foreach($array as $element)
			$new_array[] = $element;
	}
	return $new_array;
}

/**
 * Cria um indentificado �nico
 * @return string	retorna o GUID gerado
 */
function guid()
{
	if (function_exists('com_create_guid') === true)
		return trim(com_create_guid(), '{}');
	return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}

/**
 * Gera uma senha
 * @param int $length	tamanho da senha
 * @param int $strength	n�vel se seguran�a da senha, os valores podem ser 1, 2, 4 e 8, quanto maior, mais segura
 * @return string		retorna a senha gerada
 */
function new_passwd($length = 8, $strength = 0)
{
	$vowels = 'aeiou';
	$consonants = 'bcdfghjklmnpqrstvwxyz';
	if ($strength & 1)
		$consonants .= 'BCDFGHJKLMNPQRSTVWXYZ';
	if ($strength & 2)
		$vowels .= 'AEIOU';
	if ($strength & 4)
		$consonants .= '123456789';
	if ($strength & 8)
		$consonants .= '@#$%';
 
	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++)
	{
		if ($alt == 1) 
		{
			$password .= $consonants[(rand() % strlen($consonants))];
			$alt = 0;
		} 
		else 
		{
			$password .= $vowels[(rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	return $password;
}