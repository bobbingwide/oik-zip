echo Running oik-zip to package a plugin version
rem 2015/07/12 Now run using oikwp since it uses translation functions that require cache logic
rem 2016/01/13 Now run from the oik-zip directory
rem php \apache\htdocs\wordpress\wp-content\plugins\oik-zip.php %*

php c:\apache\htdocs\wordpress\wp-content\plugins\oik-batch\oik-wp.php c:\apache\htdocs\wordpress\wp-content\plugins\oik-zip\oik-zip.php %*
