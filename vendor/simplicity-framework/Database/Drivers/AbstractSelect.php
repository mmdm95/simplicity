<?php

namespace Sim\Database\Drivers;

use Sim\Database\Builder\ISelect;
use Sim\Database\Quoter\AbstractQuoter;

abstract class AbstractSelect implements ISelect
{
    /**
     * @var array $quotes
     */
    protected $quotes = array(
        'Mysql' => array('`', '`'),
        'Posgre' => array('"', '"'),
        'Sqlite' => array('"', '"'),
        'Sqlsrv' => array('[', ']'),
    );

    /**
     * @var string $db
     */
    protected $db;

    /**
     * @var AbstractQuoter $quoter
     */
    protected $quoter;

    /**
     * @var string $quote_prefix
     */
    protected $quote_prefix;

    /**
     * @var string $quote_suffix
     */
    protected $quote_suffix;

    /**
     * @var string $table
     */
    protected $table;

    /**
     * @var array $columns
     */
    protected $columns = [];

    /**
     * @var string $where
     */
    protected $where;

    /**
     * @var array $join
     */
    protected $join = [];

    /**
     * @var array $group_by
     */
    protected $group_by = [];

    /**
     * @var array $order_by
     */
    protected $order_by = [];

    /**
     * @var string $limit
     */
    protected $limit;

    /**
     * @var string $having
     */
    protected $having;

    /**
     * AbstractSelect constructor.
     * @param string $db
     */
    public function __construct(string $db)
    {
        $this->db = ucfirst(strtolower($db));
        $this->quote_prefix = $this->quotes[$this->db][0];
        $this->quote_suffix = $this->quotes[$this->db][1];
        //-----
        $quoterClass = 'Sim\\Database\\Quoter\\' . $this->db . 'Quoter';
        $this->quoter = new $quoterClass($this->quote_prefix, $this->quote_suffix);
    }

    /**
     * Give a column's name of type
     *   string: exp. ColumnName
     *   array: exp. [col => ColumnName]
     *
     * Note:
     *   - This function will quote $column.
     *   - If you don't need quote, send <i>false</i>
     *     as second parameter
     *
     * @param string|array $column
     * @return ISelect
     */
    public function avg($column): ISelect
    {
        return $this;
    }

    /**
     * Give a column's name of type
     *   string: exp. ColumnName
     *   array: exp. [col => ColumnName]
     *
     * Note:
     *   - This function will quote $column.
     *   - If you don't need quote, send <i>false</i>
     *     as second parameter
     *
     * @param string|array $column
     * @return ISelect
     */
    public function count($column): ISelect
    {
        return $this;
    }

    /**
     * Give a column's name of type
     *   string: exp. ColumnName
     *   array: exp. [col => ColumnName]
     *
     * Note:
     *   - This function will quote $column.
     *   - If you don't need quote, send <i>false</i>
     *     as second parameter
     *
     * @param string|array $column
     * @return ISelect
     */
    public function max($column): ISelect
    {
        return $this;
    }

    /**
     * Give a column's name of type
     *   string: exp. ColumnName
     *   array: exp. [col => ColumnName]
     *
     * Note:
     *   - This function will quote $column.
     *   - If you don't need quote, send <i>false</i>
     *     as second parameter
     *
     * @param string|array $column
     * @return ISelect
     */
    public function min($column): ISelect
    {
        return $this;
    }

    /**
     * Give a column's name of type
     *   string: exp. ColumnName
     *   array: exp. [col => ColumnName]
     *
     * Note:
     *   - This function will quote $column.
     *   - If you don't need quote, send <i>false</i>
     *     as second parameter
     *
     * @param string|array $column
     * @return ISelect
     */
    public function sum($column): ISelect
    {
        return $this;
    }

    /**
     * Give a column's name of type
     *   string: exp. ColumnName
     *   array: exp. [col => ColumnName]
     *
     * Note:
     *   - This function will quote $column.
     *   - If you don't need quote, send <i>false</i>
     *     as second parameter
     *
     * @param string|array $columns
     * @return ISelect
     */
    public function columns($columns): ISelect
    {
        return $this;
    }

    /**
     * Give a column's name of type
     *   string: exp. ColumnName
     *   array: exp. [col => ColumnName]
     *
     * Note:
     *   - This function will quote $column.
     *   - If you don't need quote, send <i>false</i>
     *     as second parameter
     *
     * @param string|array $column
     * @return ISelect
     */
    public function distinctColumn($column): ISelect
    {
        return $this;
    }

    /**
     * Give a table's name of type
     *   string: table name
     *     exp.
     *       TableName
     *   array: [alias => TableName]
     *     exp.
     *       [col => TableName]
     *       [col => ISelect]
     *   ISelect: you can build another select and pass it as table
     *
     * Note:
     *   - This function will quote $table.
     *   - If you don't need quote, send <i>false</i>
     *     as second parameter
     *
     * @param array|string|ISelect $table
     * @return ISelect
     */
    public function from($table): ISelect
    {
        // need quote?
        $quote = func_get_args()[1] ?? null;
        $quote = is_null($quote) ? true : !(false === $quote);
        //-----
        $alias = null;
        $column = null;
        if ($table instanceof ISelect) {
            $quote = false;
            $column = '(' . $table . ')';
        } elseif (is_array($table) && 2 == count($table)) {
            $alias = $table[0];
            $column = $table[1];
            if ($column instanceof ISelect) {
                $quote = false;
            }
        } elseif (is_string($table)) {
            $column = $table;
        }
        // now check for quote
        if (!is_null($column)) {
            if (!$quote) {
                $column = $this->quoter->quoteAS($this->quoter->quoteDot($column));
            }
            if (is_null($alias)) {
                $this->table = [$alias => $column];
            } else {
                $this->table = $column;
            }
        }

        return $this;
    }

    /**
     * Give a where clause of type:
     *   string: column name [and an operator [and value/ColumnName]]
     *     exp.
     *       where('ColumnName=5')
     *       where(ColumnName, <, 5)
     *       where(ColumnName, >, AnotherColumnName)
     *   ICondition: you can build conditions and pass
     *               them to this function
     *
     * Note:
     *   - This function will quote $where.
     *   - If you don't need quote, send <i>false</i>
     *     as second parameter if $operation is null
     *     or as 4th parameter
     *
     * @param $where
     * @param string|null $operation
     * @param string|null $other
     * @return ISelect
     */
    public function where($where, $operation = null, string $other = null): ISelect
    {
        return $this;
    }

    /**
     * Give a where clause of type:
     *   string: column name [and an operator [and value/ColumnName]]
     *     exp.
     *       where('ColumnName=5')
     *       where(ColumnName, <, 5)
     *       where(ColumnName, >, AnotherColumnName)
     *   ICondition: you can build conditions and pass
     *               them to this function
     *
     * Note:
     *   - This function will quote $where.
     *   - If you don't need quote, send <i>false</i>
     *     as second parameter if $operation is null
     *     or as 4th parameter
     *
     * @param $where
     * @param string|null $operation
     * @param string|null $other
     * @return ISelect
     */
    public function orWhere($where, $operation = null, string $other = null): ISelect
    {
        return $this;
    }

    public function all($column): ISelect
    {
        return $this;
    }

    public function any($column): ISelect
    {
        return $this;
    }

    public function groupBy($columns): ISelect
    {
        return $this;
    }

    public function having($having): ISelect
    {
        return $this;
    }

    public function orderBy($columns): ISelect
    {
        return $this;
    }

    public function limit($limit, $offset = 0): ISelect
    {
        return $this;
    }

    public function join($on, $condition): ISelect
    {
        return $this;
    }

    public function leftJoin($on, $condition): ISelect
    {
        return $this;
    }

    public function rightJoin($on, $condition): ISelect
    {
        return $this;
    }

    public function query($query): void
    {

    }

    public function getQuery(): string
    {
        $query = 'SELECT ';

        return $query;
    }
}