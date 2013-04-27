<?php
/*
 * Copyright (c) 2011, Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * All rights reserved.
 */


/**
 * Exce��o para arquivo n�o encontrado, tratado pelo framework, que resulta num erro 500
 * 
 * @author	Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * @version	1
 *
 */
class FileNotFoundException extends TriladoException
{
	/**
	 * Construtor da classe
	 * @param string $file	endere�o do arquivo
	 */
	public function __construct($file)
	{
		parent::__construct('O arquivo '. $file .' n�o foi encontrado');
	}
}