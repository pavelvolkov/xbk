<?php

/**
 * xbkTeaser
 *
 * Оформляет внешний вывод ошибок, предупреждений и др.
 *
 * @author     Pavel Bakanov - http://bakanov.info
 * @version    1.0   2008-01-30
 */

class xbkTeaser extends xbkContextObject
{
   /**
    * Список допустимых типов
    *
    * @access    protected
    * @type      array
    */
    protected $availableTypes = Array('ok', 'notice', 'warning', 'error');

    /**
     * Тип: ok|notice|warning|error
     *
     * @var     string
     */
    public $type = 'notice';
    /**
     * Содержание вывода
     *
     * @var     string
     */
    public $content = null;

    /**
     * Конструктор класса
     *
     * Входным параметром может быть объект класса xbkErrorStack,
     * массив или строка
     *
     * @param     string, array или object xbkErrorStack
     * @param     string
     */
    public function __construct2 ($input = null, $type = null)
    {    	// Установка содержания    	if ($input != null)
    	{
        	$this->setContent($input);
    	}

    	// Установка типа
    	if ($type != null)
    	{
        	$this->setType($type);
    	}
    }

    /**
     * Устанавливает содержимое
     *
     * @return    string
     */
    public function setContent ($input)
    {
    	if (is_object($input) ? ($input instanceof xbkErrorStack) : false)
    	{
    		if ($input->hasErrors())
    		{
        		$this->content = $input->getErrorMessages();
    		}
    	} else if (is_array($input)) {
    		$this->content = $input;
    	} else if (is_string($input)) {
    		$this->content = $input;
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
    	{    		$this->type = $type;    	}
    }

    /**
     * Формирует вывод
     *
     * @return    string
     */
    public function build ()
    {    	if ($this->content != null)
    	{    		if ($this->type == 'ok') {            	$tmpl = $this->template('teaser_ok');
        	} else if ($this->type == 'notice') {        		$tmpl = $this->template('teaser_notice');        	} else if ($this->type == 'warning') {
        		$tmpl = $this->template('teaser_warning');
        	} else if ($this->type == 'error') {
        		$tmpl = $this->template('teaser_error');
        	} else {        		$tmpl = $this->template('teaser_notice');        	}
            $tmpl->addVar('list', 'content', $this->content);
            return $tmpl->getParsedTemplate('teaser');
        } else return '';
    }

    public function __toString ()
    {
        return $this->build();
    }
}

?>