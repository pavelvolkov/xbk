<?php
/*
 *  $Id: Offset.php 3309 2007-12-06 11:09:00Z meus $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.phpdoctrine.com>.
 */
/**
 * Doctrine_Collection_Offset
 * Collection of Doctrine_Record objects.
 *
 * @package     Doctrine
 * @subpackage  Collection
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.phpdoctrine.com
 * @since       1.0
 * @version     $Revision: 3309 $
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 */
class Doctrine_Collection_Offset extends Doctrine_Collection
{
    /**
     * @var integer $limit
     */
    private $limit;

    /**
     * @param Doctrine_Table $table
     */
    public function __construct(Doctrine_Table $table)
    {
        parent::__construct($table);
        $this->limit = $table->getAttribute(Doctrine::ATTR_COLL_LIMIT);
    }

    /**
     * @return integer
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return Doctrine_Collection_Iterator_Expandable
     */
    public function getIterator()
    {
        return new Doctrine_Collection_Iterator_Expandable($this);
    }
}
