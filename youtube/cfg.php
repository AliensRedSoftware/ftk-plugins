<?php
$redirect=$_POST['redirect'];
setcookie('__youtube_type',$_POST['type'],time()+(86400*30),'/');
setcookie('__youtube_quality',$_POST['quality'],time()+(86400*30),'/');
echo "<meta http-equiv=\"refresh\" content=\"0;url=$redirect\">";
