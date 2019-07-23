<?php
/*
 * This file is part of MiroslavSapic/PHP-Zip
 */
declare(strict_types=1);

namespace MiroslavSapic\PHPZip\Handler;

/**
 * @author Miroslav Sapic
 */
class ZipFile
{
    private $in_entries = [];

    private $out_entries = [];



    public static function instance(array $entries) {
        $instance = new self();

        $instance->in_entries   = $entries;
        $instance->out_entries  = $entries;

        return $instance;
    }
}