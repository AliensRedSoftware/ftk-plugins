<?php
switch($_POST['effect']){
    case 'Перейти':
        $url=explode(':',$_POST['threads'])[1];
        if(!$url){
            $url=$_POST['threads'];
        }
        echo "<meta http-equiv=\"refresh\" content=\"0;url=$url\">";
    break;
    case 'Удалить':
        require_once$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'bootstrap.php';
        xlib::LoadWebUrl();
        $url=explode(':',$_POST['threads'])[1];
        $url=explode('/',$_POST['threads']);
        $url='delThread'.$url[count($url) - 1];
        skinmanager::OpenModal($url);
    break;
}
