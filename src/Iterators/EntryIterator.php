<?php
/*
 * This file is part of MiroslavSapic/PHP-Zip
 */
declare(strict_types=1);

namespace MiroslavSapic\PHPZip\Iterators;

/**
 * @author Miroslav Sapic
 */

class EntryIterator extends AbstractEntryIterator
{
    public function empty(): void
    {
        $this->array = [];
    }

    public function setArray(array $array): void
    {
        $this->array = $array;
    }
}