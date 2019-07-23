<?php
/*
 * This file is part of MiroslavSapic/PHP-Zip
 */
declare(strict_types=1);

namespace MiroslavSapic\PHPZip;

/**
 * @author Miroslav Sapic
 */

interface ZipFileInterface
{
    /**
     * Compression level default constant
     */
    const CL_DEFAULT = -1;

    /**
     * Compression level for fastest compression constant
     */
    const CL_FASTEST = 1;

    /**
     * Compression level for fast compression constant
     */
    const CL_FAST = 2;

    /**
     * Compression level for best compression constant (Bit slow..)
     */
    const CL_BEST = 9;

    /**
     * Open zip file archive
     *
     * @param string $filename
     * @return ZipFileInterface
     */
    public function open(string $filename):ZipFileInterface;

    /**
     * Open zip archive from string
     *
     * @param string $zip_string
     * @return ZipFileInterface
     */
    public function openFromString(string $zip_string):ZipFileInterface;

    /**
     * Close the currently opened zip archive
     */
    public function close():void;

    /**
     * Output archive as a .zip file
     *
     * @param string $filename
     * @return ZipFileInterface
     */
    public function outputFile(string $filename):ZipFileInterface;

    /**
     * Output archive as a string
     *
     * @return string
     */
    public function outputString():string;

    /**
     * Get a list of all files and directories stored in the archive or sored in a specified entry directory
     *
     * @param string|null $entry_dir
     * @return array
     */
    public function getFiles(string $entry_dir = null):array;

    /**
     * Check if entry is a directory
     *
     * @param string $dirname
     * @return bool
     */
    public function isDir(string $dirname):bool;

    /**
     * Check if entry exists in the archive
     *
     * @param string $filename
     * @return bool
     */
    public function hasEntry(string $filename):bool;

    /**
     * Extract all zip archive files and directories to a local path
     * or
     * Extract a specific entry or entries to a local path
     *
     * @param string $local_path
     * @param array|string|null $entries
     * @return ZipFileInterface
     */
    public function extractTo(string $local_path, $entries = null):ZipFileInterface;

    /**
     * Add new entry from string
     *
     * @param string $entry_name
     * @param string $content
     * @param int|null $compression
     * @return ZipFileInterface
     */
    public function addFromString(string $entry_name, string $content, int $compression = null):ZipFileInterface;

    /**
     * Add local file to zip archive
     *
     * @param string $local_filename
     * @param string $entry_filename
     * @param int|null $compression
     * @return ZipFileInterface
     */
    public function addFile(string $local_filename, string $entry_filename, int $compression = null):ZipFileInterface;

    /**
     * Creates an empty directory in the zip archive
     *
     * @param string $dirname
     * @return ZipFileInterface
     */
    public function addEmptyDir(string $dirname):ZipFileInterface;

    /**
     * Add local directory and all its files (NOT child directories) to zip archive
     *
     * @param string $local_dirname
     * @param string $entry_dirname
     * @param null $compression
     * @return ZipFileInterface
     */
    public function addDir(string $local_dirname, string $entry_dirname = '/', $compression = null):ZipFileInterface;

    /**
     * Add local directory and all its files and directories
     *
     * @param string $local_dirname
     * @param string $entry_dirname
     * @param null $compression
     * @return ZipFileInterface
     */
    public function addDirRecursive(string $local_dirname, string $entry_dirname = '/', $compression = null):ZipFileInterface;

    /**
     * Add all files and directories listed in array
     *
     * @param array $array
     * @return ZipFileInterface
     */
    public function addAll(array $array):ZipFileInterface;

    /**
     * Rename entity in zip archive
     *
     * @param string $entry_name
     * @param string $new_name
     * @return ZipFileInterface
     */
    public function rename(string $entry_name, string $new_name):ZipFileInterface;

    /**
     * Delete entity in zip archive
     *
     * @param string $entry_name
     * @return ZipFileInterface
     */
    public function delete(string $entry_name):ZipFileInterface;

    /**
     * Delete all entities from zip archive
     *
     * @return ZipFileInterface
     */
    public function deleteAll():ZipFileInterface;

    /**
     * Set global compression level
     *
     * @param int $compression_level
     * @return ZipFileInterface
     */
    public function setCompressionLevel(int $compression_level = self::CL_DEFAULT):ZipFileInterface;
}