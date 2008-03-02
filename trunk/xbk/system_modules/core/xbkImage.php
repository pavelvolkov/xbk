<?php

/**
 * xbkImage
 *
 * ������ � �������������
 *
 * @version    1.0   2008-01-30
 * @package    xBk
 * @author     Pavel Bakanov
 * @license    LGPL
 * @link       http://bakanov.info
 */

class xbkImage extends xbkContextObject
{
    /**
     * ������ �����������
     *
     * @access    protected
     * @var       array
     */
    protected $img = null;

    /**
     * �������� ���� �����������
     *
     * @access    protected
     * @var       array
     */
    protected $buffer = null;

    /**
     * ������ �����������
     *
     * @access    protected
     * @var       array
     */
    protected $type = null;

    /**
     * ������ ����������� � ��������
     *
     * @access    protected
     * @var       array
     */
    protected $width = null;

    /**
     * ������ ����������� � ��������
     *
     * @access    protected
     * @var       array
     */
    protected $height = null;

    /**
     * ���� �����������
     *
     * @access    public
     * @var       array
     */
    public $typeList = Array('gif', 'png', 'jpeg');
    /**
     * ��������� �� �����, ���������� true � ������ �����, ����� - false
     *
     * @access    public
     * @return    boolean
     */
    public function loadFromFile ($file)
    {
    	$type = false;
        $img = @imagecreatefromjpeg($file);
        if ($img != false) $type = 'jpeg';
        else {
            $img = @imagecreatefromgif($file);
            if ($img != false) $type = 'gif';
            else {
                $img = @imagecreatefrompng($file);
                if ($img != false) $type = 'png';
            }
        }
        if ($img == false) return false;
        else {        	$this->type = $type;        	$this->img = $img;
        	$this->buffer = file_get_contents($file);        	return true;        }
    }

    /**
     * ���������� ������ �����������, ���� ��� ���� ������� ���������,
     * ����� - null
     *
     * @access    public
     * @return    boolean
     */
    public function getType ()
    {    	return $this->type;
    }

    /**
     * ���������� ������ �����������, ���� ��� ���� ������� ���������,
     * ����� - null
     *
     * @access    public
     * @return    boolean
     */
    public function getWidth ()
    {    	if ($this->img != null)
    	{    		$this->width = imageSX($this->img);    	}
    	return $this->width;
    }

    /**
     * ���������� ������ �����������, ���� ��� ���� ������� ���������,
     * ����� - null
     *
     * @access    public
     * @return    boolean
     */
    public function getHeight ()
    {    	if ($this->img != null)
    	{
    		$this->height = imageSY($this->img);
    	}
    	return $this->height;
    }

    /**
     * ���������� ����� �����������, ���� ��� ���� ������� ���������,
     * ����� - null
     *
     * @access    public
     * @return    boolean
     */
    public function getBuffer ()
    {
    	return $this->buffer;
    }
}

?>