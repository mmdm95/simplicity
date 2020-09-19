<?php

namespace Sim\Database\Quoter;


abstract class AbstractQuoter implements IQuoter
{
    /**
     * @var string $as_regex
     */
    protected $as_regex = '#(?<before>[^\s]*)\s+AS\s+(?<after>[^\s]*)#umi';

    /**
     * @var string $dot_regex - after always have value
     */
    protected $dot_regex = '#(?<before>[^\s\.]*)\.(?<after>[^\s\=\.]*)#umi';

    /**
     * @var string $function_regex
     */
    protected $function_regex = '#(?<name>[^\s]+)\((?<column>.*)\)(?:[^\s]*)#umi';

    /**
     * @var string $inside_parenthesis_regex
     */
    protected $inside_parenthesis_regex = '#(?:[\s]+)\((?<inside>.*)\)(?:[\s]+)#umi';

    /**
     * @var string $name_regex
     */
    protected $name_regex = '#(?<name>[a-z_-]+[a-z0-9_-])*#umi';

    /**
     * @var string $select_regex
     */
    protected $select_regex = '#select\s+(?<command>.*)\s+#umi';

    /**
     * @var string $insert_regex
     */
    protected $insert_regex = '#insert\s+(?<command>.*)\s+#umi';

    /**
     * @var string $update_regex
     */
    protected $update_regex = '#update\s+(?<command>.*)\s+#umi';

    /**
     * @var string $delete_regex
     */
    protected $delete_regex = '#delete\s+(?<command>.*)\s+#umi';

    /**
     * @var string $quote_prefix
     */
    protected $quote_prefix;

    /**
     * @var string $quote_suffix
     */
    protected $quote_suffix;

    /**
     * @var array $ignore_regex
     */
    protected $ignore_regex = [];

    /**
     * @var string $quotes_string
     */
    protected $quoted_string = '';

    public function __construct(string $prefix, string $suffix)
    {
        $this->quote_prefix = $prefix;
        $this->quote_suffix = $suffix;
    }

    /**
     * @param string $string
     * @return string
     */
    public function quote(string $string): string
    {

    }

    /**
     * @param string $string
     * @return string
     */
    public function quoteName(string $string): string
    {

    }

    /**
     * @param string $string
     * @return string
     */
    public function quoteAs(string $string): string
    {

    }

    /**
     * @param string $string
     * @return string
     */
    public function quoteDot(string $string): string
    {

    }

    /**
     * @param string $string
     * @return string
     */
    public function quoteFunction(string $string): string
    {

    }

    /**
     * @param string $string
     * @return bool
     */
    protected function isFunction(string $string): bool
    {

    }

    /**
     * @param string $string
     * @return bool
     */
    protected function isSelect(string $string): bool
    {

    }

    /**
     * @param string $string
     * @return bool
     */
    protected function isInsert(string $string): bool
    {

    }

    /**
     * @param string $string
     * @return bool
     */
    protected function isUpdate(string $string): bool
    {

    }

    /**
     * @param string $string
     * @return bool
     */
    protected function isDelete(string $string): bool
    {

    }

    /**
     * @param string $string
     * @param string $regex
     * @return array
     */
    protected function getMatchedRegex(string $string, string $regex): array
    {

    }
}