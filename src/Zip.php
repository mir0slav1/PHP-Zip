<?php
/**
 * This file is part of MiroslavSapic/PHP-Zip
 */
declare(strict_types=1);

namespace MiroslavSapic\PHPZip;

use Exception;
use Generator;
use MiroslavSapic\PHPZip\Iterators\AbstractEntryIterator;
use MiroslavSapic\PHPZip\Iterators\EntryIterator;
use ZipArchive;

/**
 * @author Miroslav Sapic
 */
class Zip extends ZipArchive
{
    private $entry_iterator;

    /**
     * Extract multiple zip archives to destination directory
     *
     * Each archive will be extracted to it's individual directory which is created
     * inside the destination directory from the zip archive file name.
     *
     * Example: /path/to/destination/zip-file-name
     *
     * Zip::unzip('/path/to/destination', file-1.zip, file-2.zip, ...);
     *
     * @param string $destination
     * @param string ...$filenames
     */
    public static function unzip(string $destination, string ...$filenames): void
    {
        $destination = realpath($destination ?: '.');

        if (!$destination)
            return;

        foreach ($filenames as $filename) {
            $dirname = pathinfo($filename, PATHINFO_FILENAME);

            $destination .= DIRECTORY_SEPARATOR . $dirname;

            $i = 0;
            while ($i < 5) {
                $destination .= $i ? "-{$i}" : '';

                $i++;

                if (mkdir($destination)) {
                    self::extract($filename, $destination);
                    break;
                }
            }
        }
    }

    /**
     * Extracts a zip archive
     *
     * A static method that extracts a zip file to destination directory if one is supplied otherwise
     * extract in the current working directory.
     *
     * Zip::extract('file.zip', '/path/to/destination', ['file.txt', 'directory', ...]);
     *
     * Zip::extract('file.zip');
     *
     * @param string            $filename
     * @param string            $destination
     * @param array|string|null $entries
     *
     * @return bool
     */
    public static function extract(string $filename, string $destination = '.', $entries = null): bool
    {
        $destination = realpath($destination);

        if (!$destination)
            return false;

        try {
            $zip = new self();

            if ($zip->open($filename) !== true)
                return false;

            if ($zip->extractTo($destination, $entries) !== true)
                return false;

            $zip->close();
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @param string $to_filename
     * @param string $filename
     * @param string ...$filenames
     */
    public static function merge(string $to_filename, string $filename, string ...$filenames): void
    {
        $to = new self();
        if ($to->open($to_filename) !== true)
            return;

        $filenames[] = $filename;

        foreach ($filenames as $file) {
            $temp_dir = $to->makeTempDir();

            if (!$temp_dir)
                continue;

            if (self::extract($file, $temp_dir)) {
                $to->addDir($temp_dir);
            }

            unlink($temp_dir);
        }
    }

    /**
     * Returns all entries iterator (modified)
     *
     * @return AbstractEntryIterator
     */
    public function getEntryIterator(): AbstractEntryIterator
    {
        if ($this->numFiles && !$this->entry_iterator instanceof AbstractEntryIterator) {
            $this->entry_iterator = new EntryIterator();
        }

        $this->entry_iterator->empty();

        $this->entry_iterator->setArray(iterator_to_array($this->generateEntryList()));

        return $this->entry_iterator;
    }

    /**
     * Returns array of all entries
     *
     * @return array
     */
    public function getEntryList(): array
    {
        if (!$this->numFiles)
            return [];

        return iterator_to_array($this->generateEntryList());
    }

    /**
     * Generate entry list (modified)
     *
     * @return Generator
     */
    private function generateEntryList(): Generator
    {
        for($i = 0; $i < $this->numFiles; $i++)
            yield $i => $this->getNameIndex($i);
    }

    /**
     * @return bool|string
     */
    private function makeTempDir()
    {
        $dir = sys_get_temp_dir();

        if (!is_dir($dir) || !is_writable($dir))
            return false;

        $i = 0;
        do
            $path = sprintf('%s%s%s', $dir, DIRECTORY_SEPARATOR, mt_rand(100000, mt_getrandmax()));
        while (!mkdir($path) && $i++ < 1000);

        return $path;
    }

    public function addDir(string $dir)
    {

    }
}