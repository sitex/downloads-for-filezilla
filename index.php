<?php

// FTP
$ftpHost	= '';
$ftpPort	= '21';
$ftpUser	= '';
$ftpPass	= '';
// paths
$localPath  = '';
$ServerPath = '';
// file with download list
$filename	= '';

$handle = fopen($filename, "r");
if ($handle) {
	$i = 1;
    while (($line = fgets($handle)) !== false) {
    	$i++;
    	$LocalFile = trim($localPath.str_replace('/', '\\', $line));
        $path = explode('/',$line);
    	$RemoteFile = trim(array_pop($path));
        $RemotePath = '';
        foreach ($path as $item) {
        	if (strlen($item) > 0) {
        		$RemotePath .= strlen($item).' '.$item.' ';
        	}
        	
        }
        $RemotePath = trim($RemotePath);

		$return  = "<File>\n";
		$return .= "<LocalFile>$LocalFile</LocalFile>\n";
		$return .= "<RemoteFile>$RemoteFile</RemoteFile>\n";
		$return .= "<RemotePath>$ServerPath$RemotePath</RemotePath>\n";
		$return .= "<Download>1</Download>\n";
		$return .= "<Size>1</Size>\n";
		$return .= "<DataType>1</DataType>\n";
		$return .= "</File>\n";
		// to file
		$file[floor($i/2000)][] = $return;
    }
} else {
    // error opening the file.
} 
fclose($handle);

foreach ($file as $n => $items) {
	$File = 'FileZilla'.$n.'.xml';
	$Handle = fopen($File, 'w');
	$data = '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>';
	$data .= '<FileZilla3>';
	$data .= '<Queue>';
	$data .= '<Server>';
	$data .= '<Host>'.$ftpHost.'</Host>';
	$data .= '<Port>'.$ftpPort.'</Port>';
	$data .= '<Protocol>0</Protocol>';
	$data .= '<Type>0</Type>';
	$data .= '<User>'.$ftpUser.'</User>';
	$data .= '<Pass>'.$ftpPass.'</Pass>';
	$data .= '<Logontype>1</Logontype>';
	$data .= '<TimezoneOffset>0</TimezoneOffset>';
	$data .= '<PasvMode>MODE_DEFAULT</PasvMode>';
	$data .= '<MaximumMultipleConnections>0</MaximumMultipleConnections>';
	$data .= '<EncodingType>UTF-8</EncodingType>';
	$data .= '<BypassProxy>0</BypassProxy>';
	$data .= '<Name>New Site</Name>';
	$data .= implode($items); 
	$data .= '</Server>';
	$data .= '</Queue>';
	$data .= '</FileZilla3>';
	// write
	fwrite($Handle, $data); 
	print "$File Written<br>"; 
	// close
	fclose($Handle); 
}
