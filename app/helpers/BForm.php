<?php

/**
 * Classe Helper, auxilia na geração de tags HTML para formulários
 * 
 * @author	Valdirene da Cruz Neves Júnior <linkinsystem666@gmail.com>
 * @version	2
 *
 */
class BForm extends Html
{
	private static function create($type, $label, $name, $value, $close = false, $attrs = array())
	{
		$input = self::createTag($type, array_merge(array('name' => $name, 'id' => $name, 'placeholder' => $label), $attrs), $close, $value);
		$input = self::createTag('div', array('class' => 'controls'), false, $input);
		$label = self::createTag('label', array('class' => 'control-label', 'for' => $name), false, $label);
		return self::createTag('div', array('class' => 'control-group'), false, $label . $input);
	}
	
	/**
	 * Cria um campo input do tipo "text"
	 * @param	string	$name		o nome do campo (os atributos "name" e "id")
	 * @param	mixed	$value		o valor do campo (atributo "value")
	 * @param	array	$attrs		os demais atributos do campo, como por exemplo "onclick", "title" e etc.
	 * @return	string				retorna o HTML do input gerado
	 */
	public static function input($label, $name, $value = null, $size = 'input-xlarge', $attrs = array())
	{
		return self::create('input', $label, $name, $value, true, array_merge(array('value' => $value, 'type' => 'text', 'class' => $size), $attrs));
	}
	
	/**
	 * Cria um campo do tipo "textarea"
	 * @param	string	$name		o nome do campo (os atributos "name" e "id")
	 * @param	mixed	$value		o valor do campo
	 * @param	array	$attrs		os demais atributos do campo, como por exemplo "onclick", "title" e etc.
	 * @return	string				retorna o HTML do input gerado
	 */
	public static function textarea($label, $name, $value = null, $size = 'input-xlarge', $attrs = array())
	{
		return self::create('textarea', $label, $name, $value, false, array_merge(array('class' => $size), $attrs));
	}
	
	/**
	 * Cria um campo input do tipo "hidden"
	 * @param	string	$name		o nome do campo (os atributos "name" e "id")
	 * @param	mixed	$value		o valor do campo (atributo "value")
	 * @param	array	$attrs		os demais atributos do campo, como por exemplo "onclick", "title" e etc.
	 * @return	string				retorna o HTML do input gerado
	 */
	public static function hidden($label, $name, $value = null, $attrs = array())
	{
		return self::create('input', $label, $name, $value, true, array_merge(array('value' => $value, 'type' => 'hidden'), $attrs));
	}
	
	/**
	 * Cria um campo input do tipo "password"
	 * @param	string	$name		o nome do campo (os atributos "name" e "id")
	 * @param	string	$value		o valor do campo (atributo "value")
	 * @param	array	$attrs		os demais atributos do campo, como por exemplo "onclick", "title" e etc.
	 * @return	string				retorna o HTML do input gerado
	 */
	public static function password($label, $name, $value = null, $size = 'input-xlarge', $attrs = array())
	{
		return self::create('input', $label, $name, $value, true, array_merge(array('value' => $value, 'type' => 'password', 'class' => $size), $attrs));
	}
	
	/**
	 * Cria um campo input do tipo "submit"
	 * @param	string	$name		o nome do campo (os atributos "name" e "id")
	 * @param	string	$value		o valor do campo (atributo "value")
	 * @param	array	$attrs		os demais atributos do campo, como por exemplo "onclick", "title" e etc.
	 * @return	string				retorna o HTML do input gerado
	 */
	public static function submit($text, $cancel = null, $url = null)
	{
		$submit = self::createTag('button', array('type' => 'submit', 'class' => 'btn btn-primary'), false, $text);
		if($cancel && $url)
			$cancel = self::createTag('a', array('href' => $url, 'class' => 'btn pull-right'), false, $cancel);
		else if($cancel)
			$cancel = self::createTag('input', array('type' => 'reset', 'class' => 'btn pull-right', 'value' => $cancel), true);
		return self::createTag('div', array('class' => 'form-actions'), false, $submit . $cancel);
	}
	
	/**
	 * Cria um dropdown list (tag "select")
	 * @param	string	$name		o atributo "name", isso também será usado no "id"
	 * @param	array	$options	um array contendo as opções do dropdown, onde a chave é o valor e o valor é o texto
	 * @param	mixed	$selected	o valor da opção que já virá selecionada
	 * @param	array	$attrs		os demais atributos do campo, como por exemplo "onclick", "title" e etc.
	 * @return	string				retorna o HTML do select gerado
	 */
	public static function select($label, $name, $options = array(), $selected = null, $size = 'input-xlarge', $attrs = array())
	{
		$op = '';
		if(is_array($options))
		{
			foreach($options as $v => $t)
			{
				$optionAttrs = array('value' => $v);
				if($selected == $v)
					$optionAttrs['selected'] = 'selected';
				$op .= self::createTag('option', $optionAttrs, false, $t) . "\n";
			}
		}
		return self::create('select', $label, $name, $op, false, array_merge(array('class' => $size), $attrs));
	}
	
	/**
	 * Cria uma lista de input radios (tag "input" do tipo "radio")
	 * @param	string	$name		o atributo "name", isso também será usado no "id"
	 * @param	array	$options	um array contendo as opções do radio, onde a chave é o valor e o valor é o texto
	 * @param	mixed	$selected	o valor da opção que já virá selecionada
	 * @param	array	$attrs		os demais atributos do campo, como por exemplo "onclick", "title" e etc.
	 * @return	string				retorna o HTML do select gerado
	 */
	public static function radio($name, $options, $selected = null, $attrs = array())
	{
		$radios = '';
		if(is_array($options))
		{
			foreach($options as $v => $t)
			{
				$radioAttrs = array('name' => $name, 'value' => $v, 'type' => 'radio');
				if($v === $selected)
					$radioAttrs['checked'] = 'checked';
				$radios .= self::createTag('input', array_merge($radioAttrs, $attrs)) .' '. $t;
			}
		}
		return $radios;
	}
}
