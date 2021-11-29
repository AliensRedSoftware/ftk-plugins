<?php
switch($_POST['effect']){
    case 'Перейти':
        $url=explode(':',$_POST['dots'])[1];
        if(!$url){
            $url=$_POST['dots'];
        }
        echo "<meta http-equiv=\"refresh\" content=\"0;url=$url\">";
    break;
    case 'Конфигурация':
    	require_once$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'bootstrap.php';
    	xlib::LoadWebUrl();
        skinmanager::OpenModal(xmessage::getChangeDot(explode(':',$_POST['dots'])[1]));
    break;
    case 'Удалить':
        require_once$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'bootstrap.php';
        xlib::LoadWebUrl();
        $path=explode(':',$_POST['dots'])[1];
        skinmanager::OpenModal(xmessage::getDeleteDot($path));
    break;
}
