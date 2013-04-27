<?php
/*
 * Copyright (c) 2011, Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * All rights reserved.
 */


/**
 * Exce��o para diret�rio n�o encontrado, tratado pelo framework, resulta num erro 500
 * 
 * @author	Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * @version	1
 *
 */
class DirectoryNotFoundException extends TriladoException
{
	/**
	 * Contrutor da classe
	 * @param string $directory	endere�o do diret�rio
	 */
	public function __construct($directory)
	{
		parent::__construct('O diret�rio '. $directory .' n�o foi encontrado');
	}
}