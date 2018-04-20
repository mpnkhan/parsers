<?php
	
	// $f= 'image.html';
	$f= 'Customers/AuditReport_2018-04-19_18-30-53.html';
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
			$lines[$line_num] =  $key .':' . json_encode($file) . "," . "\n";		//replace content
			// echo "Line #<b>{$line_num}</b> : ". $lines[$line_num]. "<br />\n";
	    }
	}

	$content = implode($lines);
	file_put_contents('Customers.html', $content);
	echo 'done';
	


?>