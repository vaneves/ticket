<?php
/**
 * Classe Helper, auxilia na gera��o de tags HTML
 *
 * @author		Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * @version		2
 *
 */
class Html 
{
	/**
	 * Gera uma tag HTML com base nos par�metros
	 * @param string $tag		nome da tag
	 * @param array $attrs		atributos da tag
	 * @param boolean $close	indica se a tag � auto fech�vel ou n�o (ex.: <tag></tag> ou <tag />)
	 * @param mixed $value		valor da tag
	 * @return string			retorna o HTML gerado
	 */
	public static function createTag($tag, $attrs = array(), $close = true, $value = null)
	{
		$html = '<'. $tag;
		foreach($attrs as $n => $v)
			$html .= ' '. $n .'="'. $v .'"';
		return $html .= $close ? ' />' : '>'.$value.'</'.$tag.'>';
	}
}
