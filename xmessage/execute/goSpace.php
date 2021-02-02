<?php
$dot	=	$_GET['dot'];
$selectedDot	=	$_GET['selectedDot'];
$url	=	"http://" . $_SERVER['SERVER_NAME'].$dot.$selectedDot;
echo "<meta http-equiv=\"refresh\" content=\"0;url=$url\">";
