<?php

/**
 * xbkShifter
 *
 * Меняет порядковые номера записей в БД.
 *
 * @version    1.0   2008-01-31
 * @package    xBk
 * @author     Pavel Bakanov
 * @license    LGPL
 * @link       http://bakanov.info
 */


class xbkShifter extends xbkContextObject
{
    /**
     * Имя модели
     *
     * @var     string
     */
    protected $modelName = null;

    /**
     * Имя поля таблицы с порядковыми номерами записей
     *
     * @var     integer
     */
    protected $orderField = 'order_id';

    /**
     * ID текущей записи
     *
     * @var     integer
     */
    protected $id = null;

    /**
     * Условное выражение where
     *
     * @var     string
     */
    protected $whereCondition = null;
    /**
     * Установить модель для дальнейшей работы
     *
	 * @access	public
	 * @param	string
    */
	public function setModel ($name)
	{		$this->modelName = $name;
	}

    /**
     * Установить имя поля с порядковыми номерами записей
     *
	 * @access	public
	 * @param	string
    */
	public function setOrderField ($name)
	{
		$this->orderField = $name;
	}

    /**
     * Установить ID записи, с которой работаем
     *
	 * @access	public
	 * @param	string
    */
	public function setId ($id)
	{
		$this->id = $id;
	}

    /**
     * Задать условное выражение where
     *
	 * @access	public
	 * @param	string
    */
	public function where ($condition)
	{
		$this->whereCondition = $condition;
	}

    /**
     * Сдвиг на позицию вверх
     *
	 * @access	public
	 * @param	integer
	 * @return	boolean
    */
	public function up ($count = 1)
	{		$query = new Doctrine_Query();		if ($this->modelName != null && $this->orderField != null && $this->id != null)
		{			if ($this->whereCondition != null)
			{        		$records = $query->select($this->modelName.'.id, '.$this->modelName.'.'.$this->orderField)
                                  ->from($this->modelName)
                                  ->orderby($this->orderField.' ASC')
                                  ->execute();
            } else {            	$records = $query->select($this->modelName.'.id, '.$this->modelName.'.'.$this->orderField)
                                  ->from($this->modelName)
                                  ->where($this->whereCondition)
                                  ->orderby($this->orderField.' ASC')
                                  ->execute();            }
            if (count($records) > 1)
            {            	for ($i=0; isset($records[$i]); $i++)
            	{            		if ($records[$i]->id == $this->id)
            		{            			if (isset($records[($i-1)]))
            			{            				// Если есть, куда сдвигать                			$previous_order_id = $records[$i]->get($this->orderField);
                			$next_order_id = $records[($i-1)]->get($this->orderField);
                			$next_id = $records[($i-1)]->id;
                            // Два запроса по сдвигу
                			$q = new Doctrine_Query();
                            $rows = $q->update($this->modelName)
                                      ->set($this->orderField, $next_order_id)
                                      ->where('id = '.(int)$this->id)
                                      ->execute();
                            $q = new Doctrine_Query();
                            $rows = $q->update($this->modelName)
                                      ->set($this->orderField, $previous_order_id)
                                      ->where('id = '.(int)$next_id)
                                      ->execute();
                			return true;
            			} else {            				return false;            			}            		}            	}            }
        }
        return false;
	}

    /**
     * Сдвиг на позицию вниз
     *
	 * @access	public
	 * @param	integer
	 * @return	boolean
    */
	public function down ($count = 1)
	{
		$query = new Doctrine_Query();
		if ($this->modelName != null && $this->orderField != null && $this->id != null)
		{
			if ($this->whereCondition != null)
			{
        		$records = $query->select($this->modelName.'.id, '.$this->modelName.'.'.$this->orderField)
                                  ->from($this->modelName)
                                  ->orderby($this->orderField.' DESC')
                                  ->execute();
            } else {
            	$records = $query->select($this->modelName.'.id, '.$this->modelName.'.'.$this->orderField)
                                  ->from($this->modelName)
                                  ->where($this->whereCondition)
                                  ->orderby($this->orderField.' DESC')
                                  ->execute();
            }
            if (count($records) > 1)
            {
            	for ($i=0; isset($records[$i]); $i++)
            	{
            		if ($records[$i]->id == $this->id)
            		{
            			if (isset($records[($i-1)]))
            			{
            				// Если есть, куда сдвигать
                			$previous_order_id = $records[$i]->get($this->orderField);
                			$next_order_id = $records[($i-1)]->get($this->orderField);
                			$next_id = $records[($i-1)]->id;
                            // Два запроса по сдвигу
                			$q = new Doctrine_Query();
                            $rows = $q->update($this->modelName)
                                      ->set($this->orderField, $next_order_id)
                                      ->where('id = '.(int)$this->id)
                                      ->execute();
                            $q = new Doctrine_Query();
                            $rows = $q->update($this->modelName)
                                      ->set($this->orderField, $previous_order_id)
                                      ->where('id = '.(int)$next_id)
                                      ->execute();
                			return true;
            			} else {
            				return false;
            			}
            		}
            	}
            }
        }
        return false;
	}

    /**
     * Сдвигает в самый верх
     *
	 * @access	public
    */
	public function top ()
	{
	}

    /**
     * Сдвигает в самый низ
     *
	 * @access	public
    */
	public function bottom ()
	{

	}
}

?>