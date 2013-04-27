<?php
/*
 * Copyright (c) 2011, Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * All rights reserved.
 */


	//calcula o endere�o de root
	$root = str_replace('\\', '/', dirname(dirname(dirname(__FILE__))));
	if(substr($root, -1) != '/') $root = $root .'/';
	
	//define as vari�veis de root
	define('root', $root);
	define('root_virtual', str_replace($_SERVER['DOCUMENT_ROOT'], '', root));
	define('wwwroot', root . 'app/wwwroot/');
	
	//importa os arquivos iniciais
	require_once root . 'core/libs/Import.php';
	require_once root . 'core/libs/Route.php';
	require_once root . 'app/config.php';
	require_once root . 'app/routes.php';
	require_once root . 'core/constantes.php';
	require_once root . 'core/functions.php';
	
	Import::core('App');
	
	new App($_GET['url']);
	
