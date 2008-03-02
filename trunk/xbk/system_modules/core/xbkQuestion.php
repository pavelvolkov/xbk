<?php

/**
 * xbkQuestion
 *
 * ��������� ����� �������
 *
 * @author     Pavel Bakanov - http://bakanov.info
 * @version    1.0   2008-01-26
 */

class xbkQuestion extends xbkContextObject
{
   /**
    * ������ ���������� �����
    *
    * @access    protected
    * @type      array
    */
    protected $availableTypes = Array('question', 'warning', 'error');

    /**
     * ���: question|warning|error
     *
     * @var     string
     */
    public $type = 'question';
    /**
     * ����� �������
     *
     * @var     string
     */
    public $text = null;

    /**
     * ����� �������� �����
     *
     * @var     string
     */
    public $action = '';

    /**
     * ������
     *
     * @var     array
     */
    public $submits = Array();

    /**
     * ������� ����
     *
     * @var     array
     */
    public $hiddens = Array();
    /**
     * ����������� ������
     *
     * @param     string
     */
    public function __construct2 ($type = null)
    {
    	// ��������� ����
    	if ($type != null)
    	{
        	$this->setType($type);
    	}
    }

    /**
     * ������������� ���
     *
     * @return    string
     */
    public function setType ($type)
    {
    	if (in_array($type, $this->availableTypes))
    	{
    		$this->type = $type;
    	}
    }

    /**
     * ������������� ����� �������
     *
     * @param    string
     */
    public function setText ($text)
    {    	if (is_string($text))
    	{    		$this->text = $text;    	}
    }

    /**
     * ����� ����� �������� �����
     *
     * @param    string
     */
    public function setAction ($action)
    {
    	if (is_string($action))
    	{
    		$this->action = $action;
    	}
    }

    /**
     * ��������� ������
     *
     * @param    string
     */
    public function addSubmit ($name, $value)
    {
    	array_push($this->submits, Array('name' => $name, 'value' => $value));
    }

    /**
     * ��������� ������� ����
     *
     * @param    string
     */
    public function addHidden ($name, $value)
    {
    	array_push($this->hiddens, Array('name' => $name, 'value' => $value));
    }

    /**
     * ������ ����� �������
     *
     * @param    string
     */
    public function build ()
    {
    	if ($this->text != null)
    	{
    		if ($this->type == 'question') {
            	$tmpl = $this->template('question_question');
        	} else if ($this->type == 'warning') {
        		$tmpl = $this->template('question_warning');
        	} else if ($this->type == 'error') {
        		$tmpl = $this->template('question_error');
        	} else {
        		$tmpl = $this->template('question_question');
        	}
        	$tmpl->addVar('question', 'action', $this->action);
        	$tmpl->addRows('hidden', $this->hiddens);
        	$tmpl->addRows('submit', $this->submits);
        	$tmpl->addVar('question', 'text', $this->text);
            return $tmpl->getParsedTemplate('question');
        } else return '';    }

    public function __toString ()
    {
        return $this->build();
    }
}

?>