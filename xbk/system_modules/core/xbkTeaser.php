<?php

/**
 * xbkTeaser
 *
 * ��������� ������� ����� ������, �������������� � ��.
 *
 * @author     Pavel Bakanov - http://bakanov.info
 * @version    1.0   2008-01-30
 */

class xbkTeaser extends xbkContextObject
{
   /**
    * ������ ���������� �����
    *
    * @access    protected
    * @type      array
    */
    protected $availableTypes = Array('ok', 'notice', 'warning', 'error');

    /**
     * ���: ok|notice|warning|error
     *
     * @var     string
     */
    public $type = 'notice';
    /**
     * ���������� ������
     *
     * @var     string
     */
    public $content = null;

    /**
     * ����������� ������
     *
     * ������� ���������� ����� ���� ������ ������ xbkErrorStack,
     * ������ ��� ������
     *
     * @param     string, array ��� object xbkErrorStack
     * @param     string
     */
    public function __construct2 ($input = null, $type = null)
    {    	// ��������� ����������    	if ($input != null)
    	{
        	$this->setContent($input);
    	}

    	// ��������� ����
    	if ($type != null)
    	{
        	$this->setType($type);
    	}
    }

    /**
     * ������������� ����������
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
     * ������������� ���
     *
     * @return    string
     */
    public function setType ($type)
    {
    	if (in_array($type, $this->availableTypes))
    	{    		$this->type = $type;    	}
    }

    /**
     * ��������� �����
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