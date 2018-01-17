<html>
<?php
$dir = "./";
$dh  = opendir($dir);
while (false !== ($filename = readdir($dh))) {
    $files[] = $filename;
}

sort($files);
foreach ($files as $file) {
	if ($file != "." && $file != ".." && $file != "examples") {
		if (is_dir($dir . $file)) {
			
		} else if (strpos($file, ".php") == false){
			$name = pathinfo($file, PATHINFO_FILENAME);
			$content = file_get_contents($file);
			echo ("<a href=\"$file\" title=\"$name\"><![CDATA[ $content ]]></a><br/>");
		}
	}
}
?>
</html>
