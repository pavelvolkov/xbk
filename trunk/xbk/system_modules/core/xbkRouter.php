<?php

/**
 * xbkRouter
 *
 * ������������� �� �������
 *
 * @version       1.1   2008-02-29
 * @since         1.0
 * @package       xBk
 * @subpackage    core
 * @author        Pavel Bakanov
 * @license       LGPL
 * @link          http://bakanov.info
 */

class xbkRouter extends xbkContextObject
{
    /**
     * ������, ���������� ����� ���� ���������� ������, ���������� �������
     *
     * @access    private
     * @var       array
     */
    private $sectionHierarchyList;
    /**
     * ����������� ������
     *
     * @access	  public
     * @param	  object xbkUri ��� null
     */
    public function __construct2 ($Uri = null)
    {    	$this->prepare($Uri);
    }

    /**
     * ���������� ������� ������
     *
     * @access	  public
     * @param	  object xbkUri ��� null
     */
    public function prepare ($Uri = null)
    {
        if ($Uri == null) $Uri =& $this->factory('xbkUri');
    	$this->sectionHierarchyList = $Uri->getPath(false);
    	// ���� ���� �����������, ���� ������ ������ � ���� ������
    	if ($this->_Registry->isCoreInstalled())
    	{
            $this->loadModuleSection();
    	}
    }

    /**
     * ���������� ������ ������ ������
     *
     * @access	  public
     * @param	  object xbkModuleSection
     * @param 	  int
     * @return	  object xbkModuleSection ��� null
     */
    public function getModuleSection (&$foundSection = null, $pos = null)
    {    	if (count($this->sectionHierarchyList) > 0 && $foundSection == null)
    	{    		// ���� ������� ������ � ������ ������ ������� ������    		foreach ($this->_Registry->modules as $module)
        	{        		$sections =& $module->getSections();
        		for ($s=0; isset($sections[$s]); $s++)
        		{        			if ($this->sectionHierarchyList[0] == $sections[$s]->name)
        			{        				// ������ ����������
        				$found = true;        				return $this->getModuleSection($sections[$s], 0);
        				break;        			}        		}
        	}
            // ������ �� ���������� - ���������� 404 ������
            return $this->_Registry->getModule('core')->getSection('404');
        } else if (count($this->sectionHierarchyList) > 0) {
    		// ���� ��������� ���������
    		if (isset($this->sectionHierarchyList[$pos+1]))
    		{
        		if ($foundSection->hasSubsections())
        		{    				$subsection =& $foundSection->getSubsection($this->sectionHierarchyList[$pos+1]);                    if ($subsection != false) return $this->getModuleSection($subsection, ($pos+1));
                    else return $foundSection;        		} else return $foundSection;
    		} else return $foundSection;
    	} else {    		// �������� ��������� (��������) ������
    		return $this->getStartSection();    	}
    }

    /**
     * ���� ������ � ������� �� � ������������ ������ ������
     *
     * @access	  public
     * @param	  object xbkModuleSection
     * @param 	  int
     */
    public function loadModuleSection ($foundSection_Record = null, $pos = 0)
    {
        if (count($this->sectionHierarchyList) > 0) {
            $parent_id = null;
            if ($foundSection_Record != null)
            {            	$parent_id = $foundSection_Record->id;            }        	// ���� ��������� ���������
            if ($parent_id == null)
            {
                $result = $this->DB->query(
                    "FROM xbkSection_Record
                    WHERE xbkSection_Record.name = :name
                    AND xbkSection_Record.parent_id IS :parent_id",
                    array(':name' => $this->sectionHierarchyList[$pos], ':parent_id' => $parent_id)
                );
            } else {            	$result = $this->DB->query(
                    "FROM xbkSection_Record
                    WHERE xbkSection_Record.name = :name
                    AND xbkSection_Record.parent_id = :parent_id",
                    array(':name' => $this->sectionHierarchyList[$pos], ':parent_id' => $parent_id)
                );            }

            if (count($result) == 0)
            {            	// ���������� ��������� ��������� ������
            	if ($foundSection_Record != null)
            	{
                    $module_name = $foundSection_Record->Module->name;
                    $this->_Registry->loadModule($module_name, true);
                }            } else {            	if (isset($this->sectionHierarchyList[($pos+1)]))
            	{                	// ���� ������
                	$this->loadModuleSection($result[0], ($pos+1));
            	} else {            		// ���������� ������� ��������� ������
            		$module_name = $result[0]->Module->name;
                    $this->_Registry->loadModule($module_name, true);            	}            }

    	} else {
    		// �������� ������ ��������� ������
    	}
    }

    /**
     * ���������� ������ ����������� ������
     *
     * @access	  public
     * @return	  object xbkModuleSection
     */
    public function getStartSection ()
    {    	global $CONFIG;
    	$Uri = $this->factory('xbkUri');
    	$Uri->goto($CONFIG['index']['section']);
    	$this->prepare($Uri);
    	$this->_SERVER['REQUEST_URI'] = $Uri->build();
    	return $this->getModuleSection();    }
}

?>