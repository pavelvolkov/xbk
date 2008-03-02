<?php

/**
 * xbkModuleSection
 *
 * ����� ������ ������
 *
 * @version    1.0   2008-02-04
 * @package    xBk
 * @author     Pavel Bakanov
 * @license    LGPL
 * @link       http://bakanov.info
 */

class xbkModuleSection
{
    /**
     * ��� ������
     *
     * @access    public
     * @var       string
     */
	public $name;

    /**
     * ��� ������
     *
     * content|page|document|image|file|blank
     *
     * @access    public
     * @var       string
     */
	public $type;

    /**
     * ��� ������
     *
     * @access    public
     * @var       string
     */
	public $class;

    /**
     * ������ �������� �������� ������
     *
     * @access    public
     * @var       array
     */
	public $subsections = Array();

    /**
     * �������������� ����� SSL
     *
     * @access    public
     * @var       boolean
     */
	public $enforceSSL = null;

    /**
     * �������������� ����� �� SSL
     *
     * @access    public
     * @var       boolean
     */
	public $enforceNonSSL = null;
    /**
     * ����������� ������
     *
     * @access     public
     * @param      object DOMNode
     */
    public function __construct ($source = null, &$parentSection = null)
    {
    	{
            $this->parse($source, $parentSection);
        }
    }

    /**
     * ��������� �������� ������
     *
     * @access     public
     * @param      object DOMNode
     */
    public function parse (&$document, $source, &$parentSection = null)
    {
    	{
            if ($source->hasAttribute('type') &&
                $source->hasAttribute('name') &&
                $source->hasAttribute('class')
            ) {
                $this->type = $source->getAttribute('type');
                $this->class = $source->getAttribute('class');
                // ������������ ��������
                if ($source->hasAttribute('enforceSSL'))
                {
                    $this->enforceSSL = xbkFunctions::str2bool($source->getAttribute('enforceSSL'));
                } else {
                    $this->enforceSSL = false;
                }
                if ($source->hasAttribute('enforceNonSSL'))
                {
                    $this->enforceNonSSL = xbkFunctions::str2bool($source->getAttribute('enforceNonSSL'));
                } else {
                	$this->enforceNonSSL = false;
                }
                // ���������
                $this->subsections = $this->getSections($document, $source, $this);
            }
        }
    }

    /**
     * ���������� ������ ������
     *
     * @access     public
     * @param      object DOMNodeList
	 * @return	   array
    */
    public function getSections (&$document, &$source, &$parentSection = null)
    {
    	// ����������� ���������
        if (get_class($source) == 'DOMNodeList')
        {
        }
        else if (get_class($source) == 'DOMElement')
        {
        	$src = $xpath->query("section", $source);
        else if (get_class($source) == 'DOMDocument')
        {
        	$src = $source->getElementsByTagName('collection')->item(0)
        	->getElementsByTagName('sections')->item(0)
        	->getElementsByTagName('section');
        } else $src = null;
        // ������ ���������
        if ($src != null)
        {
            if ($src->length > 0)
            {
            	for ($s=0; $s<$src->length; $s++)
            	{
            		// ������������ ���������� �� ����������� ������
            		if (is_object($parentSection) ? (get_class($parentSection) == 'xbkModuleSection') : false)
            		{
            			if ($parentSection->enforceSSL != null)
            			{
            			}
            			if ($parentSection->enforceNonSSL != null)
            			{
                			$sections[$n]->enforceNonSSL = $parentSection->enforceNonSSL;
            			}
            	}
            }
        }
        return $sections;
    }

    /**
     * ���������� ������ ������ �� ������� ������
     *
     * @access     public
     * @param      object xbkModule_Record
	 * @return	   array
    */
    public function getSectionsFromModel (&$Record)
    {
        $sections = Array();

        if ($Record instanceof xbkModule_Record)
        {
            foreach ($Record->Section as $Section)
    		{
    			$ModuleSection->type = $Section->type;
    			$ModuleSection->class = $Section->class;
    			$ModuleSection->enforceSSL = $Section->enforce_ssl;
    			$ModuleSection->enforceNonSSL = $Section->enforce_non_ssl;
    			$ModuleSection->subsections = self::getSectionsFromModel($Section);
    			array_push($sections, $ModuleSection);
    		}
		} else if ($Record instanceof xbkSection_Record)
		{
    		{
    			$ModuleSection = new xbkModuleSection();
    			$ModuleSection->name = $Subsection->name;
    			$ModuleSection->type = $Subsection->type;
    			$ModuleSection->class = $Subsection->class;
    			$ModuleSection->enforceSSL = $Subsection->enforce_ssl;
    			$ModuleSection->enforceNonSSL = $Subsection->enforce_non_ssl;
    			// ������������ ����������
    			if ($Record->enforce_ssl != null)
    			{
    				$ModuleSection->enforceSSL = $Record->enforce_ssl;
        		}
    			if ($Record->enforce_non_ssl != null)
    			{
    				$ModuleSection->enforceNonSSL = $Record->enforce_non_ssl;
    			}
    			$ModuleSection->subsections = self::getSectionsFromModel($Section);
    			array_push($sections, $ModuleSection);
    		}

        return $sections;
    }
    /**
     * ��������� �� ������� ���������
     *
     * @access     public
     * @return      boolean
     */
    public function hasSubsections ()
    {
    	{
    	}
    }

    /**
     * ���������� ������ ��������� �� � �����, ���� false � ������ �������
     *
     * @access     public
     * @param      string
     * @return     object ��� false
     */
    public function getSubsection ($subsectionName)
    {
    	{
    		{
    }

    /**
     * ���������� ������ ���������
     *
     * @access     public
     * @return     array
     */
    public function getSubsections ()
    {
    	return $this->subsections;
    }

    /**
     * ���������� ��� ������
     *
     * @access     public
     * @return     string
     */
    public function getType ()
    {
    	return $this->type;
    }
}

?>