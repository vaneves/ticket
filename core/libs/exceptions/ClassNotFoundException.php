<?php
/*
 * Copyright (c) 2011, Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * All rights reserved.
 */


/**
 * Exce��o para classe n�o encontrada, � tratada pela framework, que resulta numa p�gina de erro interno
 *
 * @author	Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * @version	1
 *
 */
class ClassNotFoundException extends TriladoException
{
	/**
	 * Nome da classe que n�o foi encontrada
	 * @var string
	 */
	protected $clazz;
	
	/**
	 * Contrutor da classe
	 * @param string $class	nome da classe n�o encontrada
	 */
	public function __construct($class)
	{
		$this->clazz = $class;
		parent::__construct('A classe '. $class .' n�o foi encontrada');
	}
	
	/**
	 * (non-PHPdoc)
	 * @see TriladoException::getDetails()
	 */
	public function getDetails()
	{
		return '&lt;?php'. br .'/**'. br .' * @Entity()'. br .' */'. br .'class <b>'. $this->clazz .'</b> extends Model {'. br . br .'}';;
	}
}