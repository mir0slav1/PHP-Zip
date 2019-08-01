<?php
/*
 * This file is part of MiroslavSapic/PHP-Zip
 */
declare(strict_types=1);

namespace MiroslavSapic\PHPZip\Iterators;

use Iterator;
use Serializable;

/**
 * @author Miroslav Sapic
 */

interface EntryIteratorInterface extends Iterator, Serializable
{
    public function __construct();
}