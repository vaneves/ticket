<?php
/*
 * Copyright (c) 2011, Valdirene da Cruz Neves Júnior <linkinsystem666@gmail.com>
 * All rights reserved.
 */


/**
 * Arquivo de configuração
 * 
 */

/**
 * Define o tipo do debug, pode assumir os seguintes valores: off, local, network e all
 * @var string
 */
define('debug', 'local');

/**
 * Tipo do drive do banco de dados, pode assumir os seguintes valores: mysql
 * @var string
 */
define('db_type', 'mysql');

/**
 * Local do banco de dados 
 * @var string
 */
define('db_host', 'localhost');

/**
 * Nome do banco de dados
 * @var string
 */
define('db_name', 'ticket');

/**
 * Usuário do banco de dados
 * @var string
 */
define('db_user', 'root');

/**
 * Senha do banco de dados
 * @var string
 */
define('db_pass', '');

/**
 * Master Page padrão
 * @var string
 */
define('default_master', 'template');

/**
 * Controller padrão
 * @var string
 */
define('default_controller', 'Home');

/**
 * Action padrão
 * @var string
 */
define('default_action', 'index');

/**
 * Página de login
 * @var string
 */
define('default_login', '~/login');

/**
 * Charset padrão
 * @var string
 */
define('charset', 'ISO-8859-1');

/**
 * Linguagem padrão
 * @var string
 */
define('default_lang', 'pt-br');

/**
 * Chave de segurança (deve ser alterada)
 * @var string
 */
define('salt', 'ad%¨44][tgh*)(ww2#$%643d#$5gf54Ql.m,kH2?Ed');

define('auto_ajax', false);
define('auto_dotxml', true);
define('auto_dotjson', true);