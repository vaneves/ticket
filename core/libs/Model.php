<?php
/*
 * Copyright (c) 2011, Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * All rights reserved.
 */


/**
 * Classe Model representa uma entidade do banco de dados, deve ser herdada, nela deve ficar a l�gica de neg�cio da aplica��o. J� vem com  m�todos para as opera��es CRUD prontas
 * 
 * @author	Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * @version	2
 *
 */
class Model
{
	/**
	 * Guarda true se a classe for uma nova inst�ncia de Model e false a inst�ncia vinher do banco
	 * @var	boolean
	 */
	private $_isNew = true;
	
	/**
	 * Verifica se a classe � uma nova inst�ncia de Model ou se os valores vem do banco
	 * @return		boolean	retorna true se classe foi inst�nciada pelo usu�rio, ou false se foi inst�nciada pela classe DatabaseQuery
	 */
	public function _isNew()
	{
		return $this->_isNew;
	}
	
	/**
	 * Define se a classe � ou n�o uma nova inst�ncia. Esse m�todo n�o deve ser chamado
	 * @return	void
	 */
	public function _setNew()
	{
		$this->_isNew = false;
	}
	
	/**
	 * Guarda o nome da propriedade que � a chave prim�ria
	 * @var	string
	 */
	protected $_key = null;
	
	/**
	 * Identifica e retorna o nome da propriedade que � uma chave prim�ria
	 * @return	string		nome da propriedade
	 */
	protected function _getKey()
	{
		if($this->_key)
			return $this->_key;
	
		$class = get_called_class();
		$annotation = Annotation::get($class);
		foreach ($this as $p => $v)
		{
			$property = $annotation->getProperty($p);
			if($property->Column && $property->Column->Key)
				return $this->_key = $p;
		}
	}
	
	/**
	 * Define o valor da propriedade em caso de auto incremento
	 * @param	int	$id		valor do auto incremento
	 * @return	void
	 */
	public function _setLastId($id = null)
	{
		$key = $this->_getKey();
		if($id)
			$this->{$key} = $id;
	}
	
	/**
	 * M�todo do Active Record, retorna uma inst�ncia do Model buscando do banco pela chave prim�ria
	 * @param	int	$id		valor da chave prim�ria
	 * @return	object		retorna uma int�ncia de Model
	 */
	public static function get($id)
	{
		$class = get_called_class();
		$instance = new $class();
		$db = Database::getInstance();
		return $db->{$class}->single($instance->_getKey() .' = ?', $id);
	}
	
	/**
	 * M�todo do Active Record, retorna um array de inst�ncias do Model buscando do banco pelos par�metros
	 * @param	int		$p		n�mero da p�gina (ex.: 1 listar� de 0 � 10)	
	 * @param	int		$m		quantidade m�xima de itens por p�gina
	 * @param	string	$o		coluna a ser ordenada
	 * @param	string	$t		tipo de ordena��o (asc ou desc)
	 * @return	array			retorna umma lista de inst�ncias de Model
	 */
	public static function all($p = 1, $m = 10, $o = 'Id', $t = 'asc')
	{
		$p = $m * (($p < 1 ? 1 : $p) - 1);
		$class = get_called_class();
		$db = Database::getInstance();
		return $db->{$class}->limit($m, $p)->ordeBy($o .' '. $t)->all();
	}
	
	public static function search()
	{
		
	}
	
	/**
	 * M�todo do Active Record para salvar o objeto no banco, se for uma nova int�ncia d� um 'insert', sen�o d� 'update'
	 * @return	void
	 */
	public function save()
	{
		$class = get_called_class();
		$key = $this->_getKey();
		
		$db = Database::getInstance();
		if($this->{$key})
			$db->{$class}->update($this);
		else
			$db->{$class}->insert($this);
		$db->save();
	}
	
	/**
	 * M�todo do Active Record que deleta um objeto do banco de dados, por�m o objeto n�o pode ser uma nova inst�ncia
	 * @return	void
	 */	
	public function delete()
	{
		$class = get_called_class();
		
		$db = Database::getInstance();
		$db->{$class}->delete($this);
		$db->save();
	}
}
