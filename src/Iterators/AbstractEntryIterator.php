<?php
/*
 * This file is part of MiroslavSapic/PHP-Zip
 */
declare(strict_types=1);

namespace MiroslavSapic\PHPZip\Iterators;

use MiroslavSapic\PHPZip\Entries\EntryFileInterface;

/**
 * @author Miroslav Sapic
 */

abstract class AbstractEntryIterator implements EntryIteratorInterface
{
    protected $index;

    protected $array = [];

    /**
     * AbstractEntryIterator constructor.
     *
     * Inherited constructor from EntryIteratorInterface
     */
    public function __construct()
    {
        $this->index = 0;
    }

    /**
     * Return the current element
     *
     * Inherited method from Iterator interface
     *
     * @return mixed
     */
    public function current(): EntryFileInterface
    {
        return $this->array[$this->index];
    }

    /**
     * Move forward to next element
     *
     * Inherited method from Iterator interface
     *
     * @return void
     */
    public function next(): void
    {
        $this->index++;
    }

    /**
     * Return the key of the current element
     *
     * Inherited method from Iterator interface
     *
     * @return int
     */
    public function key(): int
    {
        return $this->index;
    }

    /**
     * Checks if current position is valid
     *
     * Inherited method from Iterator interface
     *
     * @return boolean
     */
    public function valid(): bool
    {
        return isset($this->array[$this->index]);
    }

    /**
     * Rewind the Iterator to the first element
     *
     * Inherited method from Iterator interface
     *
     * @return void
     */
    public function rewind(): void
    {
        $this->index = 0;
    }

    /**
     * String representation of iterator array variable
     *
     * Inherited method from Serialized interface to generate string representation
     * of iterator array variable
     *
     * @return string
     */
    public function serialize(): string
    {
        return serialize($this->array);
    }

    /**
     * Constructs the iterator array variable
     *
     * Inherited method from Serialized interface to construct iterator array
     * variable from string representation of iterator array variable
     *
     * @param string $serialized
     *
     * @return void
     */
    public function unserialize($serialized): void
    {
        $this->array = unserialize($serialized);

        array_unique($this->array);
    }

    /**
     * Append iterator with new entry
     *
     * Appends iterator with new entry and removes any duplicates
     *
     * @param string $entry
     */
    protected function append(string $entry): void
    {
        $this->array[] = $entry;

        array_unique($this->array);
    }
}