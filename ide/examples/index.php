<html>
<?php
if ($handle = opendir("./")) {
	while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
            if (is_dir($directory. "/" . $file)) {
            } else if (strcmp($file, "index.php") != 0){
            	$name = pathinfo($file, PATHINFO_FILENAME);
            	echo ("<a href=\"$file\" title=\"$name\">$file</a><br/>");
            }
        }
    }
    closedir($handle);
}
?>
</html>