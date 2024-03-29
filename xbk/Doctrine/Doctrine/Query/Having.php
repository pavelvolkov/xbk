<?php
/*
 *  $Id: Having.php 3723 2008-02-10 14:49:22Z pookey $
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
Doctrine::autoload('Doctrine_Query_Condition');
/**
 * Doctrine_Query_Having
 *
 * @package     Doctrine
 * @subpackage  Query
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.phpdoctrine.com
 * @since       1.0
 * @version     $Revision: 3723 $
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 */
class Doctrine_Query_Having extends Doctrine_Query_Condition
{
    /**
     * DQL Aggregate Function parser
     *
     * @param string $func
     * @return mixed
     */
    private function parseAggregateFunction($func)
    {
        $pos = strpos($func, '(');

        if ($pos !== false) {
            $funcs  = array();

            $name   = substr($func, 0, $pos);
            $func   = substr($func, ($pos + 1), -1);
            $params = $this->_tokenizer->bracketExplode($func, ',', '(', ')');

            foreach ($params as $k => $param) {
                $params[$k] = $this->parseAggregateFunction($param);
            }

            $funcs = $name . '(' . implode(', ', $params) . ')';

            return $funcs;

        } else {
            if ( ! is_numeric($func)) {
                $a = explode('.', $func);

                if (count($a) > 1) {
                    $field     = array_pop($a);
                    $reference = implode('.', $a);
                    $map       = $this->query->load($reference, false);
                    $field     = $map['table']->getColumnName($field);
                    $func      = $this->query->getTableAlias($reference) . '.' . $field;
                } else {
                    $field = end($a);
                    $func  = $this->query->getAggregateAlias($field);
                }
                return $func;
            } else {
                return $func;
            }
        }
    }


    /**
     * load
     * returns the parsed query part
     *
     * @param string $having
     * @return string
     */
    final public function load($having)
    {
        $tokens = $this->_tokenizer->bracketExplode($having, ' ', '(', ')');
        $part = $this->parseAggregateFunction(array_shift($tokens));
        $operator  = array_shift($tokens);
        $value     = implode(' ', $tokens);
        $part .= ' ' . $operator . ' ' . $value;
        // check the RHS for aggregate functions
        if (strpos($value, '(') !== false) {
          $value = $this->parseAggregateFunction($value);
        }
        return $part;
    }
}
