<?php
/*
Made By: Miroslav Sapic
*/

set_time_limit(0);
ignore_user_abort(true);

$download_file = './compressed.zip';

function zip($source, $destination, $ignore = array())
{
    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }

    $source = str_replace('\\', '/', realpath($source));
	
	$ignore = preg_filter('/^/', $source . '/', $ignore);

    if (is_dir($source) === true) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
		
		$this_file = str_replace('\\', '/', __FILE__);
		
        foreach ($files as $file) {
            $file = str_replace('\\', '/', $file);
			
            if (in_array(substr($file, strrpos($file, '/')+1), array('.', '..', substr($this_file, strrpos($this_file, '/')+1)))) {
                continue;
            }
			
			if (in_array($file, $ignore))
				continue;
			
            if (is_dir($file) === true) {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            } elseif (is_file($file) === true) {
                $zip->addFile(str_replace($source . '/', '', $file));
            }
        }
    } elseif (is_file($source) === true) {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
}

zip(__DIR__, $download_file);

if (file_exists($download_file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($download_file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($download_file));
	$fp = fopen($download_file, 'rb');
	fpassthru($fp);
	unlink($download_file);
    exit;
}
?>