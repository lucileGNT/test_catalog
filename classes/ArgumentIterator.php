<?php

/*
 * This file is part of the Deezer Catalog Package.
 *
 * Copyright (c) Deezer
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deezer\Component\Console;

/**
 * Class ArgumentIterator used in Argument::parse
 *
 * @author Romain Cottard <rco@deezer.com>
 */
class ArgumentIterator implements \Iterator
{
    /**
     * @var integer $index Current index
     */
    protected $index = 0;

    /**
     * @var array $arguments List of arguments
     */
    protected $arguments = array();

    /**
     * Class constructor
     *
     * @param array $args array of arguments.
     */
    public function __construct(array $args)
    {
        $this->index     = 0;
        $this->arguments = $args;
    }

    /**
     * Overridden Iterator method.
     * Return current element.
     *
     * @return mixed
     */
    public function current()
    {
        return $this->arguments[$this->index];
    }

    /**
     * Overridden Iterator method.
     * Return key element.
     *
     * @return integer Current index
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * Overridden Iterator method.
     * Increase internal index
     *
     * @return void
     */
    public function next()
    {
        ++$this->index;
    }

    /**
     * Overridden Iterator method.
     * Decrease internal index
     *
     * @return void
     */
    public function prev()
    {
        --$this->index;
    }

    /**
     * Overridden Iterator method.
     * Reset internal index.
     *
     * @return void
     */
    public function rewind()
    {
        $this->index = 0;
    }

    /**
     * Overridden Iterator method.
     * Check if iterator current element is valid.
     *
     * @return boolean
     */
    public function valid()
    {
        return isset($this->arguments[$this->index]);
    }
}
