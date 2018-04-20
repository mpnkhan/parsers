<?php
	/*
	// $f= 'image.html';
	$f= 'createInvoice/AuditReport_2018-04-02_15-20-09.html';
	list($dir, $fname) = explode("/", $f);
	$lines = file($f);

	foreach ($lines as $line_num => $line) {
		if(eregi("data:image", $line)){
			$img = md5(uniqid()) . '.jpg';
	    	$file = $dir.'/' . $img;    	
			$uri =  substr($line, strpos($line, ","));		
			$uri = substr($uri, 0, -3);
			file_put_contents($file, base64_decode($uri));				//create image file
			// $r1 = substr($line, strpos($line, "data:image"),-2);
			list($key, $val) = explode(":", $line, 2);
			$lines[$line_num] =  $key .':' . json_encode($img) . "," . "\n";		//replace content
			// echo "Line #<b>{$line_num}</b> : ". $lines[$line_num]. "<br />\n";
	    }
	}

	$content = implode($lines);
	file_put_contents($f, $content);
	echo 'done';
	*/

	/***
	*
	*	
	* This Script parses the Accessibility audit done by XCode, which saves the images in data:image\/jpeg;base64 format
	* The source audit file is very slow to load
	* This script converts data:images to jpegs and replaces them in the source file so that its faster to load
	* But the source audit file and this replaces file opens only in Safari browser. Other browsers images contains errors
	*
	**/

	//Non-recursive way (red: FASTER & less memory consumption) to list all directory content :
	// http://php.net/manual/en/function.scandir.php

	function list_directory($dir) {
	   $file_list = array();
	   $stack[] = $dir;
	   $ext='html';

	   while ($stack) {
	       $current_dir = array_pop($stack);
	       if ($dh = opendir($current_dir)) {
	           while (($file = readdir($dh)) !== false) {
	               if ($file !== '.' AND $file !== '..') {
	                   $current_file = "{$current_dir}/{$file}";
	                   $report = array();
	                   if (is_file($current_file) &&  eregi($ext, $file) ) {
	                       $file_list[] = "{$current_dir}/{$file}";
	                   } elseif (is_dir($current_file)) {
	                       $stack[] = $current_file;
	                       // $file_list[] = "{$current_dir}/{$file}/";
	                   }
	               }
	           }
	       }
	   }

	   return $file_list;
	}

	$filenames= list_directory('.');
	foreach($filenames as $key => $fname){
		// echo $fname.'<br>';
		list($dir1, $dir, $f) = explode("/", $fname);
		$lines = file($fname);

		foreach ($lines as $line_num => $line) {
			if(eregi("data:image", $line)){
				$img = md5(uniqid()) . '.jpg';
		    	$file = $dir.'/' . $img;    	
				$uri =  substr($line, strpos($line, ","));		
				$uri = substr($uri, 0, -3);
				$uri = str_replace(' ','+',$uri);
				file_put_contents($file, base64_decode($uri));				//create image file
				// $r1 = substr($line, strpos($line, "data:image"),-2);
				list($key, $val) = explode(":", $line, 2);
				$lines[$line_num] =  $key .':' . json_encode($file) . "," . "\n";		//replace content
				// echo "Line #<b>{$line_num}</b> : ". $lines[$line_num]. "<br />\n";
		    }
		}

		$content = implode($lines);
		file_put_contents($dir.'.html', $content);
		echo $fname. ' -> Done <br>';
	}

?>