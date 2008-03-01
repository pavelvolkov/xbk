<?php

/**
 * xbkModuleInfo
 *
 * ����������� ����� �������������� ���������� ������,
 * ��������� ������������� ������������
 *
 * @version    1.0   2008-01-27
 * @package    xBk
 * @author     Pavel Bakanov
 * @license	   LGPL
 * @link	   http://bakanov.info
 */

abstract class xbkModuleInfo extends xbkContextObject
{
    /**
     * ���������
     *
     * @access	  public
     * @return	  string ��� null
     */
    public function getTitle ()
    {    	return null;    }

    /**
     * ��������
     *
     * @access	  public
     * @return	  string ��� null
     */
    public function getDescription ()
    {
    	return null;
    }

    /**
     * �����
     *
     * @access	  public
     * @return	  string ��� null
     */
    public function getAuthor ()
    {
    	return null;
    }

    /**
     * ��������
     *
     * @access	  public
     * @return	  string ��� null
     */
    public function getLicense ()
    {
    	return null;
    }

    /**
     * �������������� ���� � ���� �������
     * �������� ������� - ������� �� ���������� �������:
     * - text string ����� ������� ����
     * - link string ������
     * - sub  array ������ ����������� (�����������)
     *
     * @access	  public
     * @return	  string ��� null
     */
    public function getMenu ()
    {
    	return null;
    }

}

?>