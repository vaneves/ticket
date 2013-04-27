<?php 
/*
 * Copyright (c) 2011, Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * All rights reserved.
 */


/**
 * Classe de persist�ncia com o banco de dados. Implementa o padr�o Singleton
 * 
 * @author		Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * @version		1
 *
 */
class Database 
{
	/**
	 * Guarda a inst�ncia da classe Database, pois utiliza o padr�o Singleton
	 * @var	object
	 */
	protected static $instance;
	
	/**
	 * Guarda inst�ncias da classe DatabaseQuery
	 * @var	array
	 */
	protected $tables = array();
	
	/**
	 * Guarda as SQL das opera��es de inert, update e delete
	 * @var	array
	 */
	protected $operations = array();
	
	/**
	 * Construtor da classe, protegido para n�o criar um inst�ncia sem utilizar o Singleton
	 */
	protected function __construct()
	{
		
	}
	
	/**
	 * M�todo para instancia��o do classe
	 * @return	object 	retorna a inst�ncia da classe Database
	 */
	public static function getInstance()
	{
		if(!self::$instance)
			self::$instance = new self();
		return self::$instance;
	}
	
	/**
	 * Chamado autom�ticamente quando uma propriedade de Database for chamada e ela n�o existir. Cria uma nova inst�ncia de DatabaseQuery 
	 * @param	string	$name	nome de uma tabela ou view do banco de dados
	 * @return	object			retorna uma inst�ncia de DatabaseQuery
	 */
	public function __get($name)
	{
		if($this->tables[$name])
			$this->operations = array_union($this->operations, $this->tables[$name]->getAndClearOperations());
		return $this->tables[$name] = new DatabaseQuery($name);
	}
	
	/**
	 * Submete para o banco de dados as opera��es realizadas nos models
	 * @throws	TriladoException	disparada quando ocorrer alguma exce��o do tipo SQLException
	 * @throws	SQLException		disparada quando ocorrer alguma exce��o no banco de dados
	 * @return	void
	 */
	public function save()
	{
		foreach($this->tables as $entity)
		{
			$this->operations = array_union($this->operations, $entity->getAndClearOperations());
			foreach($this->operations as $operation)
			{
				try
				{
					$stmt = DatabaseQuery::connection()->prepare($operation['sql']);
					$status = $stmt->execute($operation['values']);
					if(!$status)
					{
						$error = $stmt->errorInfo();
						throw new TriladoException($error[2]);
					}
					if($operation['model'])
						$key = $operation['model']->_setLastId($entity->lastInsertId());
				}
				catch(PDOException $ex)
				{
					throw new DatabaseException($ex->getMessage(), $ex->getCode());
				}
			}
		}
	}
	
	/**
	 * Inicioa uma transa��o
	 * @return	void
	 */
	public function transaction()
	{
		DatabaseQuery::connection()->beginTransaction();
	}
	
	/**
	 * Envia a transa��o
	 * @return	void
	 */
	public function commit()
	{
		DatabaseQuery::connection()->commit();
	}
	
	/**
	 * Cancela uma transa��o
	 * @return	void
	 */
	public function rollback()
	{
		DatabaseQuery::connection()->rollBack();
	}
}