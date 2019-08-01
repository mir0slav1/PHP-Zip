<?php
/*
 * This file is part of MiroslavSapic/PHP-Zip
 */
declare(strict_types=1);

namespace MiroslavSapic\PHPZip\Helpers;

use Generator;

/**
 * @author Miroslav Sapic
 */

class DirectoryScanner
{
    private $log_errors = false;

    private $errors = [
        'paths' => [],
        'files' => []
    ];

    private $directories;

    private $files = [];

    private $ext_filter;

    private $extensions_exclude;

    private $recursive;

    /**
     * DirectoryScanner constructor.
     * @param array $directories
     * @param array $extensions
     * @param bool $extensions_exclude
     * @param bool $recursive
     */
    public function __construct(array $directories = [], $extensions = [], bool $extensions_exclude = true, bool $recursive = false)
    {
        $this->directories = (array)$directories;
        $this->ext_filter = (array)$extensions;
        $this->extensions_exclude = $extensions_exclude;
        $this->recursive = $recursive;
    }

    /**
     * Enables logging
     *
     * @return DirectoryScanner
     */
    public function log(): DirectoryScanner
    {
        $this->log_errors = true;

        return $this;
    }

    /**
     * If no parameters passed it returns array containing both
     * files and path logs.
     *
     * Allowed parameter values are 'paths' and 'files'
     *
     * @param string|null $log
     *
     * @return array
     */
    public function getLog(string $log = null): array
    {
        if ($log and isset($this->errors[$log]))
            return $this->errors[$log];

        return $this->errors;
    }

    /**
     * Add new directory to the list
     * Takes single directory or array of directories
     *
     * @param string|array $directory
     * @return DirectoryScanner
     */
    public function addDir($directory): DirectoryScanner
    {
        $directory = (array)$directory;

        $this->directories = array_merge($this->directories, $directory);

        return $this;
    }

    /**
     * Returns array of all the loaded directories
     *
     * @return array
     */
    public function getDirs(): array
    {
        return $this->directories;
    }

    /**
     * Checks if a specific directory/path was loaded
     *
     * @param string $dir
     * @return bool
     */
    public function isDirLoaded(string $dir): bool
    {
        return in_array($dir, $this->directories);
    }

    /**
     * Removes a specific directory/path from the list
     *
     * @param string $directory
     * @return DirectoryScanner
     */
    public function removeDir(string $directory): DirectoryScanner
    {
        if (($key = array_search($directory, $this->directories)) !== false)
            unset($this->directories[$key]);

        return $this;
    }


    /**
     * Add extension to the extension include/exclude filter.
     * Can be added as a string '.jpg' or array('.jpg', '.png', ...)
     *
     * @param $extension
     * @return DirectoryScanner
     */
    public function addExt($extension): DirectoryScanner
    {
        $extension = (array)$extension;

        $this->ext_filter = array_merge($this->ext_filter, $extension);

        return $this;
    }

    /**
     * Returns array of loaded extensions
     *
     * @return array
     */
    public function getExt(): array
    {
        return $this->ext_filter;
    }

    /**
     * Checks if specific extension exists in the extension filter
     *
     * @param string $extension
     *
     * @return bool
     */
    public function isExtInFilter(string $extension): bool
    {
        return in_array($extension, $this->ext_filter);
    }

    /**
     * Remove specific extension from the extension filter
     *
     * @param string $extension
     *
     * @return DirectoryScanner
     */
    public function removeExt(string $extension): DirectoryScanner
    {
        if (($key = array_search($extension, $this->ext_filter)) !== false)
            unset($this->ext_filter[$key]);

        return $this;
    }

    /**
     * Set if the directory should be scanned recursively
     *
     * @param bool $recursive
     *
     * @return DirectoryScanner
     */
    public function setRecursive(bool $recursive = true): DirectoryScanner
    {
        $this->recursive = $recursive;

        return $this;
    }

    /**
     * Returns if recursive has been enabled or not
     *
     * @return bool
     */
    public function isRecursive(): bool
    {
        return $this->recursive;
    }

    public function setExcludeInclude(bool $true_to_exclude_false_to_include): DirectoryScanner
    {
        $this->extensions_exclude = $true_to_exclude_false_to_include;

        return $this;
    }

    /**
     * Yields all the found files
     *
     * @return Generator
     */
    public function getFilesIterator()
    {
        foreach ($this->files as $file)
            yield $file;
    }

    /**
     * Starts the scanning process
     *
     * @return DirectoryScanner
     */
    public function scan(): DirectoryScanner
    {
        if ($this->ext_filter) {
            $this->ext_filter = array_filter($this->ext_filter);

            $this->ext_filter = array_map(function($ext) {
                $ext = strtolower($ext);
                $ext = trim($ext);
                $ext = rtrim($ext, '/');
                $ext = $ext . '/';

                return $ext;
            }, $this->ext_filter);
        }

        foreach ($this->directories as $k => $dir) {
            if (is_dir($dir)) {
                $this->scanDir($dir);
                continue;
            }

            $this->logPath($dir);
            unset($this->directories[$k]);
        }

        return $this;
    }

    /**
     * Cleans DirectoryScanner data including the loaded directories list.
     *
     * So you can load up a fresh list of directories but keep extension
     * filters and recursive settings
     *
     * @return DirectoryScanner
     */
    public function cleanScanner(): DirectoryScanner
    {
        $this->directories = [];
        $this->files = [];
        $this->errors['paths'] = [];
        $this->errors['files'] = [];

        return $this;
    }

    /**
     * Reinitialize DirectoryScanner so you can start fresh.
     *
     * After this call you will need to call log() again if you
     * wish to log data from this point on
     *
     * @return DirectoryScanner
     */
    public function resetScanner(): DirectoryScanner
    {
        $this->cleanScanner();
        $this->ext_filter = [];
        $this->extensions_exclude = true;
        $this->recursive = false;
        $this->log_errors = false;

        return $this;
    }

    /**
     * Logs information related to directories/paths
     *
     * @access private
     *
     * @param string $directory
     * @param mixed  ...$args
     */
    private function logPath(string $directory, ...$args): void
    {
        if (!$this->log_errors)
            return;

        if ($args)
            $this->errors['paths'][] = vsprintf($directory, $args);
        else
            $this->errors['paths'][] = sprintf('%s is not a directory and it was removed from the scanner!', $directory);
    }

    /**
     * Logs information related to files
     *
     * @access private
     *
     * @param string $filename
     * @param mixed  ...$args
     */
    private function logFile(string $filename, ...$args): void
    {
        if (!$this->log_errors)
            return;

        if ($args)
            $this->errors['files'][] = vsprintf($filename, $args);
        else
            $this->errors['files'][] = sprintf('%s is not a file and ignored from the list!', $filename);
    }

    /**
     * Scans a single directories and appends files array with found files.
     *
     * If recursive is enabled it will look inside every child directory
     *
     * @access private
     *
     * @param string $dir
     */
    private function scanDir(string $dir): void
    {
        $scan = scandir($dir);

        if (!$scan)
            return;

        $scan = array_diff($scan, ['.', '..']);

        foreach ($scan as $file) {
            $file = $dir . $file;

            if (is_file($file)) {
                if (!$this->ext_filter) {
                    $this->files[] = $file;
                    continue;
                }

                list('basename' => $filename, 'extension' => $extension) = pathinfo($file);
                $extension = strtolower($extension);

                if (!$extension && $this->extensions_exclude) {
                    $this->files[] = $file;
                    continue;
                }

                $in_filter = in_array($extension, $this->ext_filter);

                if ($this->extensions_exclude && $in_filter) {
                    $this->logFile('File %s excluded, extension (%s) in exclude list.', $filename, $extension);
                    continue;
                } elseif (!$this->extensions_exclude && $in_filter)
                    $this->files[] = $file;

                continue;
            }

            if ($this->recursive and is_dir($file))
                $this->scanDir($file);
        }
    }
}