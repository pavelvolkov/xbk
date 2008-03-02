<?php

/**
 * xbkShifter
 *
 * ������ ���������� ������ ������� � ��.
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
     * ��� ������
     *
     * @var     string
     */
    protected $modelName = null;

    /**
     * ��� ���� ������� � ����������� �������� �������
     *
     * @var     integer
     */
    protected $orderField = 'order_id';

    /**
     * ID ������� ������
     *
     * @var     integer
     */
    protected $id = null;

    /**
     * �������� ��������� where
     *
     * @var     string
     */
    protected $whereCondition = null;
    /**
     * ���������� ������ ��� ���������� ������
     *
	 * @access	public
	 * @param	string
    */
	public function setModel ($name)
	{		$this->modelName = $name;
	}

    /**
     * ���������� ��� ���� � ����������� �������� �������
     *
	 * @access	public
	 * @param	string
    */
	public function setOrderField ($name)
	{
		$this->orderField = $name;
	}

    /**
     * ���������� ID ������, � ������� ��������
     *
	 * @access	public
	 * @param	string
    */
	public function setId ($id)
	{
		$this->id = $id;
	}

    /**
     * ������ �������� ��������� where
     *
	 * @access	public
	 * @param	string
    */
	public function where ($condition)
	{
		$this->whereCondition = $condition;
	}

    /**
     * ����� �� ������� �����
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
            			{            				// ���� ����, ���� ��������                			$previous_order_id = $records[$i]->get($this->orderField);
                			$next_order_id = $records[($i-1)]->get($this->orderField);
                			$next_id = $records[($i-1)]->id;
                            // ��� ������� �� ������
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
     * ����� �� ������� ����
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
            				// ���� ����, ���� ��������
                			$previous_order_id = $records[$i]->get($this->orderField);
                			$next_order_id = $records[($i-1)]->get($this->orderField);
                			$next_id = $records[($i-1)]->id;
                            // ��� ������� �� ������
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
     * �������� � ����� ����
     *
	 * @access	public
    */
	public function top ()
	{
	}

    /**
     * �������� � ����� ���
     *
	 * @access	public
    */
	public function bottom ()
	{

	}
}

?>