<?php

/**
 * xbkForm_Test
 *
 * ������ ������������ ������ xbkForm
 *
 * @version    1.0   2008-02-04
 * @package    xBk
 * @author
 * @license    LGPL
 * @link
 */

class xbkForm_Test extends xbkSection
{
    /**
     * ����������� ������
     *
     * @access      public
     */
    public function __construct2()
    {
        $Form = $this->factory('xbkForm');

        $this->setContent('');
    }
}

?>