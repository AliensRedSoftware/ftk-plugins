<?php
$redirect=$_POST['redirect'];
setcookie('__LIGHTBOX_VIEW',$_POST['__LIGHTBOX_VIEW'],0,'/');
echo "<meta http-equiv=\"refresh\" content=\"0;url=$redirect\">";
