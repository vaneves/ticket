<?php
/*
 * Copyright (c) 2011-2012, Valdirene da Cruz Neves Júnior <linkinsystem666@gmail.com>
 * All rights reserved.
 */


/**
 * Classe responsável por renderizar a página
 * 
 * @author	Valdirene da Cruz Neves Júnior <linkinsystem666@gmail.com>
 * @version	2.5
 *
 */
class Template
{
	/**
	 * Guarda o conteúdo da resposta da requisição, pode ser HTML, JSON ou XML
	 * @var	string
	 */
	private $response;
	
	/**
	 * Guarda os hooks do usuário
	 * @var	array	lista com instâncias das classes que contêm os hooks
	 */
	private $hook = array();
	
	/**
	 * Renderiza a página solicitada pelo usuário
	 * @param	array	$args				argumentos requisitados pelo usuário, como controller, action e parâmetros
	 * @throws	InvalidReturnException		Disparada caso a action solicitada retorne null
	 * @return	void
	 */
	public function render($args)
	{
		$this->master();
		
		$registry = Registry::getInstance();
		
		$name = CONTROLLER;
		$controller = new $name();
		$registry->set('Controller', $controller);
		$this->response = $controller->beforeRender();
		
		$content = call_user_func_array(array($controller, ACTION), $args['params']);
		
		if(!$content)
			throw new InvalidReturnException(CONTROLLER .'->'. ACTION .'()');
		
		$this->renderFlash();
		
		$method = new ReflectionMethod(CONTROLLER, ACTION);
		$params = $method->getParameters();
		
		for($i = 0; $i < count($params); $i++)
		{
			if(!array_key_exists($params[$i]->getName(), $content->Vars))
				$content->Vars[$params[$i]->getName()] = isset($args['params'][$i]) && $args['params'][$i] !== null ? $args['params'][$i] : $params[$i]->getDefaultValue();
		}
		
		if(isset($args['dot']))
		{
			$content->Type = $args['dot'];
			$content->Data = $content->Vars['model'];
		}
		
		switch($content->Type)
		{
			case 'view':
			case 'snippet':
				$this->renderView($content);
				break;
			case 'content':
				$this->renderContent($content);
				break;
			case 'partial':
				$this->renderPartial($content);
				break;
			case 'xml':
				$this->renderXml($content);
				break;
			case 'json':
				$this->renderJson($content);
				break;
			default:
				throw new InvalidReturnException(CONTROLLER .'->'. ACTION .'()');
				break;
		}
		$this->response = $controller->afterRender($this->response);
		
		foreach ($this->hook as $hook)
			$this->responde = $hook->response($this->response);
		
		echo $this->response;
	}
	
	/**
	 * Verifica e retorna o master page deve ser renderizada
	 * @throws	MethodNotFoundException		disparado caso método referente ao nome da master não seja encontrado dentro da MasterController
	 * @throws	MethodVisibilityException	disparado caso método referente ao nome da master não esteja público
	 * @return	string						retorna o nome da master page
	 */
	private function master()
	{
		$annotation = Annotation::get(CONTROLLER);
		
		$reflection = new ReflectionClass(CONTROLLER);
		$tpl = null;
		if($reflection->hasMethod('__construct'))
		{
			if(property_exists($annotation->getMethod('__construct'), 'Master'))
				$tpl = $annotation->getMethod('__construct')->Master;
		}
		
		$action = $annotation->getMethod(ACTION);
		$tpl_action = isset($action->Master) ? $action->Master : null;
		
		if($tpl_action)
			$tpl = $tpl_action;
		
		if(!$tpl)
			$tpl = Config::get('default_master');
		
		define('master', $tpl);
		define('MASTER', $tpl);
		return $tpl;
	}
	
	/**
	 * Adicionar uma instância de uma classe para disparar o hook
	 * @param	object	$hook	instância da classe que contém o método para ser utilizado no hook
	 * @return	void
	 */
	public function addHook($hook)
	{
		$this->hook[] = $hook;
	}
	
	/**
	 * Renderiza a flash message
	 * @return	void
	 */
	private function renderFlash()
	{
		$html = '';
		$flash = Session::get('Flash.Message');
		if($flash)
			$html = '<div class="'. $flash->type .'">'. $flash->message .'</div>';
		
		foreach($this->hook as $hook)
			$html = $hook->renderFlash($html);
		
		define('flash', $html);
		define('FLASH', $html);
		Session::del('Flash.Message');
	}
	
	/**
	 * Renderiza a view
	 * @param	object	$ob		objeto com informações da view
	 * @return	void
	 */
	private function renderView($ob)
	{
		$html = Import::view($ob->Vars, '_master', MASTER);
		$html = $this->resolveUrl($html);

		$content = Import::view($ob->Vars, $ob->Data['controller'], $ob->Data['view']);
		$content = $this->resolveUrl($content);
		
		$html = str_replace(CONTENT, $content, $html);
		$this->response .= $html;
	}
	
	/**
	 * Renderiza o conteúdo no lugar da view
	 * @param	object	$ob		objeto com informações do conteúdo
	 * @return	void
	 */
	private function renderContent($ob)
	{
		$html = Import::view($ob->Vars, '_master', MASTER);
		$html = $this->resolveUrl($html);
		
		$content = $ob->Data;
		$content = $this->resolveUrl($content);
		
		$html = str_replace(CONTENT, $content, $html);
		$this->response .= $html;
	}
	
	/**
	 * Renderiza uma página no lugar da view
	 * @param	object	$ob		objeto com informações da página e da master page
	 * @return	void
	 */
	private function renderPartial($ob)
	{
		$html = Import::view($ob->Vars, $ob->Data['controller'], $ob->Data['view']);	
		$html = $this->resolveUrl($html);
		
		$this->response .= $html;
	}
	
	/**
	 * Renderiza um conteúdo XML e mata a execução
	 * @param	object	$ob		objeto com informações do XML
	 * @return	void
	 */
	private function renderXml($ob)
	{
		header('Content-type: application/xml; charset='. Config::get('charset'));
		$this->response .= '<?xml version="1.0" encoding="'. Config::get('charset') .'"?>';
		$this->response .= xml_encode(d($ob->Data));
	}
	
	/**
	 * Renderiza um conteúdo JSON e mata a execução
	 * @param	object	$ob		objeto com informações do JSON
	 * @return	void
	 */
	private function renderJson($ob)
	{
		header('Content-type: application/json; charset='. Config::get('charset'));
		$this->response .= json_encode(utf8encode(d($ob->Data)));
	}
	
	/**
	 * Substitui os '~/' dentro da master e page e da view pelo root virtual
	 * @param	string	$html		HTML da view ou da master page
	 * @return	string				retorna o HTML
	 */
	private function resolveUrl($html)
	{
		return str_replace(array('"~/', "'~/"), array('"'. ROOT_VIRTUAL, "'". ROOT_VIRTUAL), $html);
	}
}