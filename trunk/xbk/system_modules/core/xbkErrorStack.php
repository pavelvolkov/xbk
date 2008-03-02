<?php

/**
 * xbkErrorStack
 *
 * Класс работы над ошибками
 *
 * @author     Pavel Bakanov - http://bakanov.info
 * @version    1.0   2008-01-17
 */

class xbkErrorStack extends xbkContextObject
{
    /**
     * Массив ошибок
     *
     * @access    public
     * @var       array
     */
    public $errors = Array();

    /**
     * Добавляет ошибку
     *
	 * @param     string  - текст ошибки
	 * @param     string  - код или индекс ошибки
     *
     * или
     *
     * @param     array  - массив ошибок, где ключ - код ошибки, значение - текст
     */
    public function addError ($message, $code = null)
    {    	$args = func_get_args();
    	$num = func_num_args();
    	if (is_string($args[0]))
    	{
        	array_push($this->errors, Array('message' => $message, 'code' => $code));
    	} else if ($num == 1 ? (is_array($args[0])) : false)
    	{    		foreach ($args[0] as $key => $value)
    		{    			array_push($this->errors, Array('message' => $value, 'code' => $key));    		}    	}
    }

    /**
     * Добавляет ошибку (только текст)
     *
	 * @param     string  - текст ошибки
     */
    public function push ($message)
    {
    	$this->addError($message);
    }

    /**
     * Определяет наличие ошибки по её коду
     *
	 * @param     string
	 * @return    boolean
     */
    public function hasError ($code)
    {    	foreach ($this->errors as $error)
    	{    		if ($error['code'] == $code) return true;    	}
    	return false;
    }

    /**
     * Определяет, есть ли ошибки
     *
	 * @return    boolean
     */
    public function hasErrors ()
    {
    	if (count($this->errors) > 0) return true;
    	else return false;
    }

    /**
     * Убирает ошибку из набора по её коду
     *
	 * @param     int     код ошибки
     */
    public function removeError ($code)
    {
    	for ($i=0; isset($this->errors[$i]); $i++)
    	{    		if ($this->errors[$i]['code'] == $code)
    		{    			unset($this->errors[$i]);    			break;    		}    	}
    }

    /**
     * Возвращает коды ошибок
     *
	 * @param     boolean   исключить повторы
	 * @return    array
     */
    public function getErrorCodes ($distinct = false)
    {    	$errorCodes = Array();
    	foreach ($this->errors as $error)
    	{
    		if ($distinct ? !$this->hasError($error['code']) : true)
    		{        		array_push($errorCodes, $error['code']);    		}
    	}
    	return $errorCodes;
    }

    /**
     * Возвращает сообщения об ошибках
     *
	 * @param     boolean   исключить повторы
	 * @return    array
     */
    public function getErrorMessages ($distinct = false)
    {
    	$errorMessages = Array();
    	foreach ($this->errors as $error)
    	{
    		if ($distinct ? !$this->hasError($error['code']) : true)
    		{
        		array_push($errorMessages, $error['message']);
    		}
    	}
    	return $errorMessages;
    }

    /**
     * Строит
     *
	 * @return    string
     */
    public function build ()
    {    	if (count($this->errors) > 0)
    	{    		$tmpl = $this->template();
            $tmpl->readTemplatesFromInput('error');
            $tmpl->addRows('list', $this->errors);
            return $tmpl->getParsedTemplate('error');    	} else return '';
    }
    /**
     * Выводит список ошибок
     *
	 * @return	string
     */
    public function __toString ()
    {    	return $this->build();    }
}

?>