<?php
/*
 * This file is part of MiroslavSapic/PHP-Zip
 */
declare(strict_types=1);

namespace MiroslavSapic\PHPZip\Handler;

use function is_resource;
use function get_resource_type;
use MiroslavSapic\PHPZip\Exceptions\ZipFileHandlerException;
use function stream_get_meta_data;
use function rewind;

/**
 * @author Miroslav Sapic
 */

class ZipFileHandler implements ZipFileHandlerInterface
{
    private $allowed_mime_types = [
        'zip'   => 'application/zip'
    ];

    private $handle;

    private $handle_meta;

    public function __construct($handle)
    {
        $this->handle = $handle;

        $this->fetchHandleMeta();

        if ($this->isHandleValid()) {
            $this->cleanHandler();
            throw new ZipFileHandlerException('Handler must be a valid stream resource type!');
        }

        if ($this->isStreamValid()) {
            $this->cleanHandler();
            throw new ZipFileHandlerException('Handler must be a zip readable zip file!');
        }
    }

    public function read():ZipFile
    {
        $this->validateZipFile();


    }

    public function isHandleValid():bool
    {
        return is_resource($this->handle) and get_resource_type($this->handle) === 'stream';
    }

    public function isStreamValid():bool
    {
        return $this->handle_meta['stream_type'] !== 'dir' and $this->handle_meta['seekable'];
    }

    private function validateZipFile():void
    {
        rewind($this->handle);

        $header_bytes = fread($this->handle, 4);

        if (strlen($header_bytes) < 4) {
            $this->cleanHandler();

            throw new ZipFileHandlerException('Invalid zip file provided to the handler!');
        }

        $sig = unpack('V', $header_bytes)[0];

        if (self::LOCAL_FILE_SIGNATURE !== $sig and self::ZIP64_RECORD_SIGNATURE !== $sig and self::ZIP_SIGNATURE !== $sig) {
            $this->cleanHandler();

            throw new ZipFileHandlerException('Invalid zip file provided to the handler!');
        }
    }

    private function fetchHandleMeta():void
    {
        if ($this->handle_meta === null) {
            $this->handle_meta = stream_get_meta_data($this->handle);
        }
    }

    private function cleanHandler():void
    {
        fclose($this->handle);
        $this->handle = null;
        $this->handle_meta = null;
    }
}