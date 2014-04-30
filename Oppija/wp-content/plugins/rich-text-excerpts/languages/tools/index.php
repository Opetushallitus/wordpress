<?php
/**
 * This file can be run to generate the po/mo files for the plugin
 * the directory contans
 * - tpl.txt - this is a template made from the POT file with all lines replaced by '%[line number%]'
 * - en.txt - the english text from the plugin - copied and pasted into google translate
 * - txt directory - contains all translations in plain text format generated by google translate
 * - lang - output directory for all mo and po files
 * the output of the script is an array containing all commands piped to msgfmt, with the output of 
 * those commands and the return value
 *
 * @uses /usr/bin/msgfmt to generate po files (adjust this path to suit your system)
 */
$msgfmt = '/usr/bin/msgfmt';
$txtdir = dirname(__FILE__) . '/txt';
$langdir = dirname(__FILE__) . '/lang';
$tpl = file_get_contents(dirname(__FILE__) . '/tpl.txt');

if ($dh = opendir($txtdir)) {
	$output = array();
    while (($file = readdir($dh)) !== false) {
        if (!is_dir($txtdir . '/' . $file)) {
        	$po_filename = $langdir . '/rich-text-excerpts-' . substr($file, 0, -3) . 'po';
        	$mo_filename = $langdir . '/rich-text-excerpts-' . substr($file, 0, -3) . 'mo';
        	@unlink($po_filename);
        	@unlink($mo_filename);
        	$po_file = $tpl;
        	$lines = file($txtdir . '/' . $file);
        	$line_no = 1;
        	foreach ($lines as $line) {
        		$po_file = str_replace('%'.$line_no.'%', rtrim(str_replace('"', "'", $line), "\n\r"), $po_file);
        		$line_no++;
        	}
        	file_put_contents($po_filename, $po_file);
        	$cmd = $msgfmt . ' -cv -o ' . $mo_filename . ' ' . $po_filename;
        	$output[] = $cmd;
        	exec($cmd, $output, $ret);
            $output[] = $ret;
        }
    }
    closedir($dh);
    print('<pre>' . print_r($output, true) . '</pre>');
}