<?php

/**
 * xbkQuestion
 *
 * Формирует форму вопроса
 *
 * @author     Pavel Bakanov - http://bakanov.info
 * @version    1.0   2008-01-26
 */

class xbkQuestion extends xbkContextObject
{
   /**
    * Список допустимых типов
    *
    * @access    protected
    * @type      array
    */
    protected $availableTypes = Array('question', 'warning', 'error');

    /**
     * Тип: question|warning|error
     *
     * @var     string
     */
    public $type = 'question';
    /**
     * Текст вопроса
     *
     * @var     string
     */
    public $text = null;

    /**
     * Адрес отправки формы
     *
     * @var     string
     */
    public $action = '';

    /**
     * Кнопки
     *
     * @var     array
     */
    public $submits = Array();

    /**
     * Скрытые поля
     *
     * @var     array
     */
    public $hiddens = Array();
    /**
     * Конструктор класса
     *
     * @param     string
     */
    public function __construct2 ($type = null)
    {
    	// Установка типа
    	if ($type != null)
    	{
        	$this->setType($type);
    	}
    }

    /**
     * Устанавливает тип
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
     * Устанавливает текст вопроса
     *
     * @param    string
     */
    public function setText ($text)
    {    	if (is_string($text))
    	{    		$this->text = $text;    	}
    }

    /**
     * Задаёт адрес отправки формы
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
     * Добавляет кнопку
     *
     * @param    string
     */
    public function addSubmit ($name, $value)
    {
    	array_push($this->submits, Array('name' => $name, 'value' => $value));
    }

    /**
     * Добавляет скрытое поле
     *
     * @param    string
     */
    public function addHidden ($name, $value)
    {
    	array_push($this->hiddens, Array('name' => $name, 'value' => $value));
    }

    /**
     * Строит форму вопроса
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