<?php
$redirect=$_POST['redirect'];
setcookie('__XMESSAGE_SORT_THREAD',$_POST['__XMESSAGE_SORT_THREAD'],0,'/');
echo "<meta http-equiv=\"refresh\" content=\"0;url=$redirect\">";
