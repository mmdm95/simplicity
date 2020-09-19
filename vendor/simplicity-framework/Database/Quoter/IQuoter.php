<?php

namespace Sim\Database\Quoter;


interface IQuoter
{
    /**
     * @param string $string
     * @return string
     */
    public function quote(string $string): string;

    /**
     * @param string $string
     * @return string
     */
    public function quoteName(string $string): string;

    /**
     * @param string $string
     * @return string
     */
    public function quoteAs(string $string): string;

    /**
     * @param string $string
     * @return string
     */
    public function quoteDot(string $string): string;

    /**
     * @param string $string
     * @return string
     */
    public function quoteFunction(string $string): string;
}