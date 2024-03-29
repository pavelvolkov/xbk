<?php
/*
 *  $Id: Expression.php 3212 2007-11-24 18:58:33Z romanb $
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
Doctrine::autoload('Doctrine_Connection_Module');
/**
 * Doctrine_Expression
 *
 * @package     Doctrine
 * @subpackage  Expression
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.phpdoctrine.com
 * @since       1.0
 * @version     $Revision: 3212 $
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 */
class Doctrine_Expression
{
    protected $_expression;
    protected $_conn;
    protected $_tokenizer;

    /**
     * Create an expression 
     * 
     * @param string $expr The expression
     * @param Doctrine_Connection $conn The connection (optional)
     * @return void
     */
    public function __construct($expr, $conn = null)
    {
        $this->_tokenizer = new Doctrine_Query_Tokenizer();
        $this->setExpression($expr);
        if ($conn !== null) {
            $this->_conn = $conn;
        }
    }

    /**
     * getConnection 
     * 
     * @return Doctrine_Connection The connection
     */
    public function getConnection()
    {
        if ( ! isset($this->_conn)) {
            return Doctrine_Manager::connection();
        }

        return $this->_conn;
    }

    /**
     * setExpression 
     * 
     * @param string $clause The expression to set
     * @return void
     */
    public function setExpression($clause)
    {
        $this->_expression = $this->parseClause($clause);
    }

    /**
     * parseExpression 
     *
     * @todo: What does this function do?
     * 
     * @param string $expr The expression to parse
     * @return void
     */
    public function parseExpression($expr)
    {
        $pos  = strpos($expr, '(');
        if ($pos === false) {
            return $expr;
        }

        // get the name of the function
        $name   = substr($expr, 0, $pos);
        $argStr = substr($expr, ($pos + 1), -1);

        // parse args
        foreach ($this->_tokenizer->bracketExplode($argStr, ',') as $arg) {
           $args[] = $this->parseClause($arg);
        }

        return call_user_func_array(array($this->getConnection()->expression, $name), $args);
    }

    /**
     * parseClause 
     * 
     * @param string $clause The clause
     * @return string The parse clause
     */
    public function parseClause($clause)
    {
        $e = $this->_tokenizer->bracketExplode($clause, ' ');

        foreach ($e as $k => $expr) {
            $e[$k] = $this->parseExpression($expr);
        }
        
        return implode(' ', $e);
    }

    /**
     * getSql 
     * 
     * @return string The expression
     */
    public function getSql()
    {

        return $this->_expression;
    }

    /**
     * __toString 
     * 
     * @return void
     */
    public function __toString()
    {
        return $this->getSql();
    }
}
