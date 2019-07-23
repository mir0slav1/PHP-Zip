<?php
/*
 * This file is part of MiroslavSapic/PHP-Zip
 */
declare(strict_types=1);

namespace MiroslavSapic\PHPZip;

use MiroslavSapic\PHPZip\Exceptions\ZipFileHandlerException;
use MiroslavSapic\PHPZip\Handler\ZipFileHandler;

use function file_exists;
use function fopen;
use function fwrite;
use function rewind;

/**
 * @author Miroslav Sapic
 */

class ZipArchive implements ZipFileInterface
{
    private $allowed_compression_levels = [
        self::CL_DEFAULT,
        self::CL_FASTEST,
        self::CL_FAST,
        self::CL_BEST
    ];

    private $file_input = null;

    public function __construct()
    {

    }

    public function open(string $filename): ZipFileInterface
    {
        if (!file_exists($filename)) {
            throw new ZipFileHandlerException(sprintf('Filename %s does not exists!', $filename));
        }

        if (($handle = fopen($filename, 'rb')) === false) {
            throw new ZipFileHandlerException(sprintf('Unable to open %s', $filename));
        }

        $this->openFromHandler($handle);

        return $this;
    }

    public function openFromString(string $zip_string):ZipFileInterface
    {
        if (empty($zip_string)) {
            throw new ZipFileHandlerException('String cannot be empty or null!');
        }

        if (($handle = fopen('php://temp', 'r+b')) === false) {
            throw new ZipFileHandlerException('Unable to load string data to temp file!');
        }

        fwrite($handle, $zip_string);
        rewind($handle);

        $this->openFromHandler($handle);

        return $this;
    }

    public function openFromHandler($handle):ZipFileInterface {
        $this->file_input = new ZipFileHandler($handle);

        return $this;
    }

    public function getFiles(string $entry_dir = null): array
    {

    }
}