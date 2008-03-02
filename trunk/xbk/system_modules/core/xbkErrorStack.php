<?php

/**
 * xbkErrorStack
 *
 * ����� ������ ��� ��������
 *
 * @author     Pavel Bakanov - http://bakanov.info
 * @version    1.0   2008-01-17
 */

class xbkErrorStack extends xbkContextObject
{
    /**
     * ������ ������
     *
     * @access    public
     * @var       array
     */
    public $errors = Array();

    /**
     * ��������� ������
     *
	 * @param     string  - ����� ������
	 * @param     string  - ��� ��� ������ ������
     *
     * ���
     *
     * @param     array  - ������ ������, ��� ���� - ��� ������, �������� - �����
     */
    public function addError ($message, $code = null)
    {
    	$num = func_num_args();
    	if (is_string($args[0]))
    	{
        	array_push($this->errors, Array('message' => $message, 'code' => $code));
    	} else if ($num == 1 ? (is_array($args[0])) : false)
    	{
    		{
    }

    /**
     * ��������� ������ (������ �����)
     *
	 * @param     string  - ����� ������
     */
    public function push ($message)
    {
    	$this->addError($message);
    }

    /**
     * ���������� ������� ������ �� � ����
     *
	 * @param     string
	 * @return    boolean
     */
    public function hasError ($code)
    {
    	{
    	return false;
    }

    /**
     * ����������, ���� �� ������
     *
	 * @return    boolean
     */
    public function hasErrors ()
    {
    	if (count($this->errors) > 0) return true;
    	else return false;
    }

    /**
     * ������� ������ �� ������ �� � ����
     *
	 * @param     int     ��� ������
     */
    public function removeError ($code)
    {
    	for ($i=0; isset($this->errors[$i]); $i++)
    	{
    		{
    }

    /**
     * ���������� ���� ������
     *
	 * @param     boolean   ��������� �������
	 * @return    array
     */
    public function getErrorCodes ($distinct = false)
    {
    	foreach ($this->errors as $error)
    	{
    		if ($distinct ? !$this->hasError($error['code']) : true)
    		{
    	}
    	return $errorCodes;
    }

    /**
     * ���������� ��������� �� �������
     *
	 * @param     boolean   ��������� �������
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
     * ������
     *
	 * @return    string
     */
    public function build ()
    {
    	{
            $tmpl->readTemplatesFromInput('error');
            $tmpl->addRows('list', $this->errors);
            return $tmpl->getParsedTemplate('error');
    }
    /**
     * ������� ������ ������
     *
	 * @return	string
     */
    public function __toString ()
    {
}

?>