<?php
/*
 * This file is part of MiroslavSapic/PHP-Zip
 */
declare(strict_types=1);

namespace MiroslavSapic\PHPZip;

/**
 * @author Miroslav Sapic
 */

interface ZipInterface
{
    /**
     * @param string $filename
     * @param array|string $options
     * @return ZipInterface
     */
    public function open(string $filename, $options = []):ZipInterface;

    /**
     * @param string $filename
     * @param array $options
     * @return ZipInterface
     */
    public function merge(string $filename, array $options = []):ZipInterface;

    /**
     * @param string $filename
     * @param array $options
     * @return ZipInterface
     */
    public function duplicate(string $filename, array $options = []):ZipInterface;

    /**
     *
     */
    public function close():void;

    /**
     * @param string $entry
     * @param array $options
     * @return ZipInterface
     */
    public function set(string $entry, array  $options = []):ZipInterface;

    /**
     * @param string $entry
     * @param array $options
     * @return ZipInterface
     */
    public function get(string $entry, array $options = []):ZipInterface;

    /**
     * @param string $entry
     * @param string $new_name
     * @return ZipInterface
     */
    public function rename(string $entry, string $new_name):ZipInterface;

    /**
     * @param string $entry
     * @return ZipInterface
     */
    public function delete(string $entry):ZipInterface;

    /**
     * @param string $entry
     * @return ZipInterface
     */
    public function find(string $entry):ZipInterface;

    /**
     * @return array
     */
    public function list():array;

    /**
     * @param string $comment
     * @param string|null $entry
     * @return ZipInterface
     */
    public function comment(string $comment, string $entry = null):ZipInterface;

    /**
     * @param string|null $entry
     * @return ZipInterface
     */
    public function undo(string $entry = null):ZipInterface;

    /**
     * @param string|null $path
     * @param null $entries
     */
    public function extract(string $path = null, $entries = null):void;

    /**
     * @return ZipInterface
     */
    public function validate():ZipInterface;
}