<?php
/*
 * Copyright (c) 2011, Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * All rights reserved.
 */


/**
 * Classe de internacionaliza��o
 * @author	Valdirene da Cruz Neves J�nior <linkinsystem666@gmailc.om>
 * @version	1.1
 *
 */
class I18n 
{
	/**
	 * Nome do arquivo de tradu��o
	 * @var	string
	 */
	private $file = '';
	
	/**
	 * Guarda as mensagens, sendo a chave um MD5 da mensagem original e o valor a mensagem traduzida
	 * @var	array
	 */
	private $messages = array();
	
	/**
	 * Linguagem da tradu��o
	 * @var	string
	 */
	private $lang;
	
	/**
	 * Linguagem original
	 * @var	string
	 */
	private $default_lang;
	
	private static $instance = null;
	
	/**
	 * Construtor da classe
	 * @param	string	$default	linguagem original
	 */
	private function __construct($default)
	{
		$this->default_lang = $default;
	}
	
	/**
	 * Retorna a inst�ncia da classes (padr�o singleton)
	 * @return	object				retorna a inst�ncia de I18n
	 */
	public static function getInstance()
	{
		if(!self::$instance)
			self::$instance = new self(default_lang);
		return self::$instance;
	}
	
	/**
	 * Define a linguagem da tradu��o
	 * @param	string	$lang		nome da linguagem de tradu��o
	 * @return	void
	 */
	public function setLang($lang = null)
	{
		if(!$lang)
			$lang = $this->default_lang;
		$this->lang = $lang;
		if($lang != $this->default_lang)
			$this->messages = $this->load($lang);
	}
	
	/**
	 * Traduz uma mensagem e retorna
	 * @param	string	$string			mensagem a ser traduzida
	 * @param	array	$format			array com as vari�veis de formata��o da mensagem
	 * @throws	TriladoException		disparada caso a mensagem esteja vazia
	 * @return	string					retorna a mensagem traduzida
	 */
	public function get($string, $format = null)
	{
		if(count($string) == 0)
			throw new TriladoException('Params is empty!');
		
		if($this->lang != $this->default_lang)
			$string = $this->messages[md5($string)];
		
		if(is_array($format))
		{
			foreach ($format as $k => $v)
				$string = str_replace('%'.$k, $v, $string);
		}
		return $string;
	}
	
	/**
	 * Carrega um arquivo de tradu��o pegando as mensagens e tradu��es e joga em array retornando-o
	 * @param	string	$file		nome do arquivo
	 * @throws	TriladoException	disparada caso o arquivo n�o exista ou o conte�do esteja vazio
	 * @return	array				retorna um array com as mensagens de tradu��o, sendo as chaves o MD5 da mensagem original
	 */
	private function load($lang)
	{
		$file_path = root .'app/i18n/'. $lang .'.lang';

		if(!file_exists($file_path))
			throw new FileNotFoundException($file_path);
		$lines = file($file_path);
		if(!count($lines))
			throw new TriladoException('Arquivo "'. $file_path .'" est� vazio');
		
		$key = false;
		$result = array();
		foreach($lines as $line)
		{
			$line = trim($line);
			if(preg_match('@^(msgid|msgstr)@', $line))
			{
				if(preg_match('/^msgid "(.+)"/', $line, $match))
					$key = md5($match[1]);
				elseif(preg_match('/^msgstr "(.+)"/', $line, $match))
				{
					if(!$key)
						throw new TriladoException('Erro de sintax no arquivo "'. $file_path .'" na linha "'. $match[1] .'"');
					$result[$key] = $match[1];
					$key = false;
				}
			}
		}
		return $result;
	}
}