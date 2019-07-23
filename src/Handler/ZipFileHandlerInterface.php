<?php
/*
 * This file is part of MiroslavSapic/PHP-Zip
 */
declare(strict_types=1);

namespace MiroslavSapic\PHPZip\Handler;


/**
 * @author Miroslav Sapic
 */

interface ZipFileHandlerInterface
{
    const LOCAL_FILE_SIGNATURE = 0x04034B50;

    const ZIP_SIGNATURE = 0x06054B50;
    const ZIP64_LOCATOR_SIGNATURE = 0x07064B50;
    const ZIP64_RECORD_SIGNATURE = 0x06064B50;
    /**
     * Read handle and parse as zip archive
     *
     * @return ZipFile
     */
    public function read():ZipFile;

    /**
     * Close zip file handler
     */
    public function close():void ;

    /**
     * @return resource
     */
    public function getHandle();
}