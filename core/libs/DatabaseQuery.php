<?php
/*
 * Copyright (c) 2011, Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * All rights reserved.
 */


/**
 * Classe de Mapemamento de Objeto Relacional (ORM), que � utilizada em conjunto com a classe Database para manipular o banco de dados
 * utilizando orienta��o a objetos.
 * 
 * @author	Valdirene da Cruz Neves J�nior <linkinsystem666@gmail.com>
 * @version	1
 *
 */
class DatabaseQuery 
{	
	/**
	 * Guarda a conex�o com o banco de dados
	 * @var	object
	 */
	protected static $connection = null;
	
	/**
	 * Guarda as instru��es geradas para inser��o, atualiza��o e dele��o de dados das tabelas
	 * @var	array
	 */
	protected $operations = array();
	
	/**
	 * Guarda uma inst�ncia da classe Annotation referente ao model que est� sendo trabalhado
	 * @var	object
	 */
	protected $annotation;
	
	/**
	 * Guarda as propriedades do model que est� sendo trabalhado
	 * @var	array
	 */
	protected $properties = array();
	
	/**
	 * Guarda o nome da classe do model
	 * @var	string
	 */
	protected $clazz;
	
	/**
	 * Guarda o nome da tabela que o model representa
	 * @var	string
	 */
	protected $table;
	
	/**
	 * Guarda o nome dos atributos que ser�o retornas da tabela na hora da execu��o das instru��o SQL
	 * @var	string
	 */
	protected $select;
	
	/**
	 * Guarda os condicionais do instru��o SQL
	 * @var	string
	 */
	protected $where = '';
	
	/**
	 * Guarda os valores dos condicionais
	 * @var	array
	 */
	protected $where_params = array();
	
	/**
	 * Guarda a condi��o de ordena��o
	 * @var	string
	 */
	protected $orderby = '';
	
	/**
	 * Guarda o limite m�ximo de resultados que poder�o ser retornados
	 * @var	int
	 */
	protected $limit = '';
	
	/**
	 * Guarda a posi��o em que come�ar�o os resultados
	 * @var	int
	 */
	protected $offset = '';
	
	/**
	 * Guarda a informa��o se vai utilizar ou n�o distin��o dos resultados
	 * @var	string
	 */
	protected $distinct = '';
	
	/**
	 * Indica se o resultado a instru��o � uma oper�o soma, m�dia, valor m�nimo e etc.
	 * @var	boolean
	 */
	protected $calc = false;
	
	protected $innerJoins = array();
	protected $rightJoins = array();
	protected $leftJoins = array();
	
	/**
	 * Construtor da classe
	 * @param	string	$class		nome do model
	 * @throws	DatabaseException	dispara se o model n�o tiver a anota��o de Entity ou View
	 */
	public function __construct($class)
	{
		$this->clazz = $class;
		$this->annotation = Annotation::get($class);
		$this->properties = $this->annotation->getProperties();
		
		$annotation_class = $this->annotation->getClass();
		if(!property_exists($annotation_class, 'Entity') && !property_exists($annotation_class, 'View'))
			throw new DatabaseException("A classe '". $class ."' n�o � uma entidade ou view");
		
		$this->table = is_string($annotation_class->Entity) ? $annotation_class->Entity : $class;
	}
	
	/**
	 * M�todo est�tico que faz a conex�o com o banco de dados
	 * @throws	DatabaseException	dispara se ocorrer algum exce��o do tipo PDOException
	 * @return	object				retorna uma inst�ncia da classe PDO que representa a conex�o
	 */
	public static function connection()
	{
		if(self::$connection !== null) 
			return self::$connection;
		try
		{
			self::$connection = new PDO('mysql:dbname='. db_name .';host='. db_host, db_user, db_pass);
			self::$connection->setAttribute(PDO::ATTR_PERSISTENT, true);
			return self::$connection;
		}
		catch(PDOException $e)
		{
			throw new DatabaseException($e->getMessage());
		}
	}
	
	/**
	 * Pega as instruc�es SQL geradas e limpa a propriedade $operations
	 * @return	array	retorna um array com as SQLs
	 */
	public function getAndClearOperations()
	{
		$operations = $this->operations;
		$this->operations = array();
		return $operations;
	}
	
	/**
	 * Adiciona as condi��es na instrun��o (clausula WHERE)
	 * @param	string	$condition		condi��es SQL, por exemplo 'Id = ? OR slug = ?'
	 * @param	mixed	$value1			valor da primeira condi��o
	 * @param	mixed	$valueN			valor da x condi��o
	 * @throws	DatabaseException		disparado se a quantidade de argumentos for menor 2 ou se quantidade de condicionais n�o corresponder a quantidade de valores
	 * @return	object					retorna a pr�pria inst�ncia da classe DatabaseQuery 
	 */
	public function where()
	{
		if(func_num_args() < 2)
			throw new DatabaseException('O m�todo where() deve conter no m�nimo 2 par�metros');
		
		$args = func_get_args();
		$where = $args[0];
		array_shift($args);
		$params = $args;
		
		if(substr_count($where, '?') !== count($params))
			throw new DatabaseException('Quantidade de par�metros est� diferente');
			
		$this->where = $where;
		$this->where_params = $params;
		return $this;
	}
	
	/**
	 * Adiciona as condi��es na instru��o (clausula WHERE)
	 * @param	string	$where		condi��es SQL, por exemplo 'Id = ? OR slug = ?'
	 * @param	array	$params		array com os valores das condi��es
	 * @return	object				retorna a pr�pria inst�ncia da classe DatabaseQuery
	 */
	public function whereArray($where, $params)
	{
		if(substr_count($where, '?') !== count($params))
			throw new DatabaseException('Quantidade de par�metros est� diferente');
		
		$this->where = $where;
		$this->where_params = $params;
		return $this;
	}
	
	/**
	 * Adiciona as condi��es na instru��o SQL (clausula WHERE)
	 * @param	string	$where		condi��es SQL com valores direto, por exemplo 'Description IS NOT NULL'
	 * @return	object				retorna a pr�pria inst�ncia da classe DatabaseQuery
	 */
	public function whereSQL($where)
	{
		$this->where = $where;
		return $this;
	}
	
	/**
	 * Define a ordem em que os resultados ser�o retornados
	 * @param	string	$order	nome da coluna a ser ordenada
	 * @return	object			retorna a pr�pria inst�ncia da classe DatabaseQuery
	 */
	public function orderBy($order)
	{
		$this->orderby = $order;
		return $this;
	}
	
	/**
	 * Define como ordem decrescente os resultados que ser�o retornados
	 * @param	string	$order	nome da coluna a ser ordenada
	 * @return	object			retorna a pr�pria inst�ncia da classe DatabaseQuery
	 */
	public function orderByDesc($order)
	{
		$this->orderby = $order .' DESC';
		return $this;
	}
	
	/**
	 * Define um limite m�ximo de itens a serem retornados
	 * @param	int	$n	valor do limite
	 * @param	int	$o	valor do offset
	 * @return	object	retorna a pr�pria inst�ncia da classe DatabaseQuery
	 */
	public function limit($n, $o = null)
	{
		$this->limit = $n;
		if($o) 
			$this->offset = $o;
		return $this;
	}
	
	/**
	 * Define a posi��o em que os resultados iniciam
	 * @param	int	$n	valor da posi��o
	 * @return	object	retorna a pr�pria inst�ncia da classe DatabaseQuery
	 */
	public function offset($n)
	{
		$this->offset = $n;
		return $this;
	}
	
	/**
	 * Define que os resultados ser�o distintos
	 * @return	object	retorna a pr�pria inst�ncia da classe DatabaseQuery
	 */
	public function distinct()
	{
		$this->distinct = 'DISTINCT ';
		return $this;
	}
	
	public function innerJoin($model, $as = null)
	{
		if(!$as)
			$as = $model;
		$this->innerJoins[$as] = $model;
		return $this;
	}
	
	/**
	 * Gerar e retorna o SQL da consulta
	 * @return	string	retorna o SQL gerado
	 */
	public function getSQL()
	{
		$select = $this->select;
		if(!$select)
			$select = $this->table .'.*';
		
		$joins = '';
		//joins
		foreach($this->innerJoins as  $as => $join)
		{
			$relation = $this->getRelationship($join);
			$entity = $class->Entity ? $class->Entity : $this->clazz;
			if($relation)
				$joins .= ' INNER JOIN '. $join .' AS '. $as .' ON '. $as .'.'. $relation->{$join} .' = '. $entity .'.'. $relation->{$this->clazz};
		}
		
		$where = $this->where ? ' WHERE '. $this->where : '';
		$orderby = $this->orderby ? ' ORDER BY '. $this->orderby : '';
		$limit = $this->limit ? ' LIMIT '. $this->limit : '';
		$offset = $this->offset ? ' OFFSET '. $this->offset : '';
		
		return 'SELECT '. $this->distinct . $select .' FROM '. $this->table . $joins . $where . $orderby . $limit . $offset;
	}
	
	/**
	 * Monta a instrun��o SQL a partir da opera��es chamadas e executa a instru��o
	 * @throws	TriladoException	disparada caso ocorra algum erro na execu��o da opera��o
	 * @return	array				retorna um array com inst�ncias do Model
	 */
	public function all()
	{
		if (func_num_args() > 0) 
		{
			$reflectionMethod = new ReflectionMethod('DatabaseQuery', 'where');
			$args = func_get_args();
			$reflectionMethod->invokeArgs($this, $args);
		}
		
		$sql = $this->getSQL();

		Debug::addSql($sql, $this->where_params);
		
		$stmt = self::connection()->prepare($sql);
		$status = $stmt->execute($this->where_params);
		if(!$status)
		{
			$error = $stmt->errorInfo();
			throw new TriladoException($error[2]);
		}
		if($stmt->rowCount() > 0)
		{
			$results = array();
			$annotation = Annotation::get($this->clazz);
			while($result = $stmt->fetch(PDO::FETCH_ASSOC))
			{
				if($this->calc)
					return $result['calc'];
				$model = $this->clazz;
				$object = new $model();	
				$object->_setNew();	
				
				foreach($result as $field => $value)
				{
					$property = $annotation->getProperty($field);
					$type = strtolower($property->Column->Type);
					$type = $type == 'double' ? 'float' : $type;
					$type = $type == 'int' ? 'integer' : $type;
					if($type == 'boolean')
						$value = ord($value) == 1;
					elseif($type != 'datetime')
						settype($value, $type);
					$object->{$field} = $value;
				}
				
				$results[] = $object;
			}
			return $results;
		}
		return array();
	}
	
	/**
	 * Monta a instru��o SQL a partir das opera��es chamadas e executa a instru��o
	 * @return	object	retorna uma inst�ncia do Model com os valores preenchidos de acordo com o banco
	 */
	public function single()
	{
		$this->limit(1);
		
		$reflectionMethod = new ReflectionMethod('DatabaseQuery', 'all');
		$args = func_get_args();
		$result = $reflectionMethod->invokeArgs($this, $args);
		
		if(count($result)) 
			return $result[0];
	}
	
	/**
	 * Monta a instru��o SQL com pagina��o de resultado, executa a instru��o
	 * @param	int	$p		o n�mero da p�gina que quer listar os resultados (come�a com zero)
	 * @param	int	$m		quantidade m�xima de itens por p�gina
	 * @return	object		retorna um objeto com as propriedade Data (contendo um array com os resultados) e Count (contento a quantidade total de resultados)
	 */
	public function paginate($p, $m)
	{
		$p = ($p < 0 ? 0 : $p) * $m;
		$result = new stdClass;
		$this->limit = $m;
		$this->offset = $p;
		$result->Data = $this->all();
		$this->limit = $this->offset = null;
	
		$result->Count = $this->count();
		return $result;
	}
	
	/**
	 * Monta a instru��o SQL a partir das opera��es chamadas e executa a instru��o
	 * @param	string	$operation		opera��o a ser executada, tipo SUM, AVG, MIN e etc
	 * @param	string	$column			colunas da tabela em que a opera��o se aplica
	 * @return 	int						retorna o valor da opera��o 
	 */
	protected function calc($operation, $column)
	{
		$this->calc = true;
		$this->select = $operation .'('. $column .') AS calc';
		return $this->all();
	}
	
	/**
	 * Calcula quantos resultados existem na tabela aplicando as regras dos m�todos chamados anteriormente
	 * @return	int		retorna a quantidade
	 */
	public function count()
	{
		return $this->calc('COUNT', '*');
	}
	
	/**
	 * Calcula a soma de todos os valores da coluna expecificada
	 * @param	string	$column		coluna a ser somada
	 * @return	double				retorna a soma dos valores de cada linha
	 */
	public function sum($column)
	{
		return $this->calc('SUM', $this->table .'.'. $column);
	}
	
	/**
	 * Calcula o maior valor de uma coluna expecifica
	 * @param	string	$column		nome da coluna a ser calculada
	 * @return	double				retorna o maior valor	
	 */
	public function max($column)
	{
		return $this->calc('MAX', $this->table .'.'. $column);
	}
	
	/**
	 * Calcula o menor valor de uma coluna expecifica
	 * @param	string	$column		nome da coluna a ser calculada
	 * @return	double				retorna o menor valor
	 */
	public function min($column)
	{
		return $this->calc('MIN', $this->table .'.'. $column);
	}
	
	/**
	 * Calcula a m�dia de uma coluna expecifica, somando todos os valores dessa coluna e divindo pela quantidade de linhas existentes
	 * @param	string	$column		nome da coluna a ser calculada
	 * @return	double				retorna a m�dia calculada
	 */
	public function avg($column)
	{
		return $this->calc('AVG', $this->table .'.'. $column);
	}
	
	/**
	 * Verifica se o model possui algum relacionamento com outro model
	 * @return	array	retorna null caso n�o possua, mas se possuir retorna os relacionamentos
	 */
	protected function getRelationships()
	{
		$relationships = array();
		foreach($this->properties as $name => $property)
		{
			if($property->HasMany)
				$relationships[$name] = $property->HasMany;
			elseif($property->HasOne)
				$relationships[$name] = $property->HasOne;
		}
		return $relationships;
	}
	
	protected function getRelationship($model)
	{
		foreach($this->properties as $property)
		{
			if($property->HasMany)
				$relationship = $property->HasMany;
			elseif($property->HasOne)
				$relationship = $property->HasOne;
			if($relationship->Model == $model)
			{
				$class = Annotation::get($model);
				$property = $class->getProperty($relationship->Property);
				$name = $property->Column && $property->Column->Name ? $property->Column->Name : $relationship->Property;
				$key = null;
				foreach($class->getProperties() as $n => $p)
				{
					if($p->HasMany)
						$r = $p->HasMany;
					elseif($p->HasOne)
						$r = $p->HasOne;
					if($r->Model == $this->clazz)
						$key = $r->Property;
				}
				$object = new stdClass;
				$object->{$model} = $name;
				$object->{$this->clazz} = $key;
				return $object;
			}
		}
	}
	
	/**
	 * Verifica se uma propriedade expecifica do model representa uma coluna na tabela
	 * @param	object	$property	annotation da propriedade
	 * @return	boolean				retorna true se for uma coluna ou false caso contrario
	 */
	protected function isField($property)
	{
		return count((array)$property) > 0;
	}
	
	/**
	 * Valida uma propriedade expecifica do model
	 * @param	object	$property			anota��o da propriedade
	 * @param	string	$field				nome da propriedade	
	 * @param	mixed	$value				valor da propriedade
	 * @throws	ValidationException			disparada caso o valor seja inv�lido
	 * @return	void
	 */
	protected function validate($property, $field, $value)
	{
		$label = $property->Label ? $property->Label : $field;
		$functions = array('Int' => 'is_int', 'String' => 'is_string', 'Double' => 'is_double', 'Boolean' => 'is_bool');
		$is_type = $functions[$property->Column->Type];
		
		$value = $this->defaultValue($property->Column->Type, $value);
		
		if ($value == null && $property->AutoGenerated)
			return true;
		if(is_object($value))
			throw new ValidationException("O valor de '{$label}' n�o pode ser um objeto", 90400);
		if($property->Required && ($value === '' || $value === null)) 
			throw new ValidationException("O campo '{$label}' � obrigat�rio", 90401);
		if($is_type && !$is_type($value))
			throw new ValidationException("O campo '{$label}' s� aceita valor do tipo '{$property->Column->Type}'", 90402);
		if($property->Regex && !preg_match('#'. $property->Regex->Pattern .'#', $value))
			throw new ValidationException($property->Regex->Message, 90403);
	}
	
	/**
	 * Normaliza um valor de acordo com o padr�o do seu tipo
	 * @param	string	$type		tipo da propriedade
	 * @param	mixed	$value		valor da propriedade
	 * @return	mixed				retorna o valor normalizado caso seja null ou o pr�prio valor se n�o for null
	 */
	protected function defaultValue($type, $value)
	{
		if($type == 'String' && $value == null)
			$value = '';
		elseif($type == 'Boolean' && $value == null)
			$value = false;
		elseif(($type == 'Int' && $value == null) || ($type == 'Double' && $value == null))
			$value = 0;
		return $value;
	}
	
	/**
	 * Verifica se o tipo da propriedade � string
	 * @param	object	$property	anota��o da propriedade
	 * @return	boolean				retorna true se for do tipo string, no contr�rio retorna false
	 */
	protected function isString($property)
	{
		$types = array('String','Date','DateTime');
		return in_array($property->Column->Type, $types);
	}
	
	/**
	 * Verifica se propriedade � chave prim�ria
	 * @param	object	$property	anota��o da propriedade
	 * @return	boolean				retorna true se for chave prim�ria, no contr�rio retorna false
	 */
	protected function isKey($property)
	{
		return $property->Column && $property->Column->Key;
	}
	
	/**
	 * Cria uma instru��o SQL de inser��o no banco
	 * @param	Model	$model		model a ser inserido
	 * @throws	DatabaseException	disparada caso o model n�o seja uma nova inst�ncia, ou n�o tenha a anota��o Entity
	 * @return	void
	 */
	public function insert(Model $model)
	{
		$fields = array();
		$values = array();
		
		if(get_class($model) != $this->clazz)
			throw new DatabaseException("O objeto deve ser do tipo '". $this->clazz ."'");
		
		$class = $this->annotation->getClass();
		if(!$class->Entity)
			throw new DatabaseException('A classe '. get_class($model) .' n�o � uma entidade');
		
		if(!$model->_isNew()) 
			throw new DatabaseException('Para usar o m�todo inserir � preciso criar uma nova inst�ncia de '. $this->clazz);
		
		foreach($model as $field => $value)
		{	
			$property = $this->annotation->getProperty($field);
			if($this->isField($property) && !$property->AutoGenerated)
			{
				$this->validate($property, $field, $value);
				
				if($property->Column && $property->Column->Name)
					$field = $property->Column->Name;
				if (!$value && !is_bool($value) && !is_int($value)) 
					$value = null;
				if (is_bool($value))
					$value = $value ? '1' : '0';
				
				$fields[] = $field;
				$values[] = $value;
			}
		}
		$entity = $class->Entity ? $class->Entity : get_class($model);
			
		$sql = 'INSERT INTO '. $entity .' ('. implode($fields, ', ') .') VALUES ('. implode(',', array_fill(0, count($values), '?')) .');';
		
		Debug::addSql($sql, $values);
		$this->operations[] = array('sql' => $sql, 'values' => $values, 'model' => $model);
	}
	
	/**
	 * Cria uma instru��o SQL de atualiza��o no banco
	 * @param	Model	$model		model a ser atualizado
	 * @throws	DatabaseException	disparada caso o model seja uma nova inst�ncia, ou n�o tenha a anota��o Entity
	 * @return	void
	 */
	public function update(Model $model)
	{
		$fields = array();
		$values = array();
		$conditions = array();
		
		if(get_class($model) != $this->clazz)
			throw new DatabaseException("O objeto deve ser do tipo '". $this->clazz ."'");
		
		$class = $this->annotation->getClass();
		if(!$class->Entity)
			throw new DatabaseException('A classe '. get_class($model) .' n�o � uma entidade');
		
		if($model->_isNew()) 
			throw new DatabaseException('O m�todo update n�o pode ser utilizado com uma nova inst�ncia de '. $this->clazz);
		
		foreach($model as $field => $value)
		{	
			$property = $this->annotation->getProperty($field);
			if($this->isField($property))
			{
				if($this->isKey($property))
				{
					$conditions['fields'][] = $field .' = ?';
					$conditions['values'][] = $value;
				}
				else
				{
					$this->validate($property, $field, $value);
					
					if($property->Column && $property->Column->Name)
						$field = $property->Column->Name;
					if (!$value && !is_bool($value)) 
						$value = 'NULL';
					if (is_bool($value))
						$value = $value ? '1' : '0';
					
					$fields[] = $field .' = ?';
					$values[] = $value;
				}
			}
		}
		$entity = $class->Entity ? $class->Entity : get_class($model);
		$sql = 'UPDATE '. $entity .' SET '. implode(', ', $fields) .' WHERE '. implode(' AND ', $conditions['fields']) .';';
		
		Debug::addSql($sql, array_merge($values, $conditions['values']));
		$this->operations[] = array('sql' => $sql, 'values' => array_merge($values, $conditions['values']));
	}
	
	/**
	 * Cria uma instru��o SQL de dele��o no banco
	 * @param	Model	$model		model a ser deletado
	 * @throws	DatabaseException	disparada caso o model seja uma nova inst�ncia, ou n�o tenha a anota��o Entity
	 * @return	void
	 */
	public function delete(Model $model)
	{
		$conditions = array();
		
		if(get_class($model) != $this->clazz)
			throw new DatabaseException("O objeto deve ser do tipo '". $this->clazz ."'");
		
		$class = $this->annotation->getClass();
		if(!$class->Entity)
			throw new DatabaseException('A classe '. get_class($model) .' n�o � uma entidade');
		
		if($model->_isNew()) 
			throw new DatabaseException('O m�todo delete n�o pode ser utilizado com uma nova inst�ncia de '. $this->clazz);
		
		foreach($model as $field => $value)
		{	
			$property = $this->annotation->getProperty($field);
			if($this->isField($property) && $this->isKey($property))
			{
				$conditions['fields'][] = $field .' = ?';
				$conditions['values'][] = $value;
			}
		}
		$entity = $class->Entity ? $class->Entity : get_class($model);
		$sql = 'DELETE FROM '. $entity .' WHERE '. implode(' AND ', $conditions['fields']) .';';
		
		Debug::addSql($sql, $conditions['values']);
		$this->operations[] = array('sql' => $sql, 'values' => $conditions['values']);
	}
	
	/**
	 * Pega o ID da ultima instrun��o de um model espec�fico
	 * @return	int		retorna o valor do ID
	 */
	public function lastInsertId()
	{
		return self::connection()->lastInsertId($this->table);
	}
}
