<?php
require_once$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'bootstrap.php';
//-->Вернуться назад
$xp=skinmanager::a(['href'=>'#xprivate','modal'=>'xprivate','title'=>'Личный кабинет']);
$data=xprivate::$data;
if($data['pass']!=$_COOKIE['__XPRIVATE_PASS']){
    setcookie('__XPRIVATE_PASS',NULL,0,'/');
    xlib::LoadWebUrl();
    skinmanager::modal(['open'=>1,'title'=>'Личный кабинет','content'=>"В доступе отказано :(</br>Пожалуйста введите верный пароль доступа в $xp"]);
}else{
    setcookie('__XPRIVATE_AUTH',NULL,0,'/');
    setcookie('__XPRIVATE_PASS',NULL,0,'/');
    $_COOKIE['__XPRIVATE_PASS']=NULL;
    //account
    unlink("../account/anon/".$data['id'].'/user.json');
    unlink("../account/anon/".$data['id'].'/ico/ava');
    rmdir("../account/anon/".$data['id'].'/ico');
    rmdir("../account/anon/".$data['id']);
    array_map('unlink',array_filter((array)array_merge(glob('..'.DIRECTORY_SEPARATOR.'account'.DIRECTORY_SEPARATOR.'anon'.DIRECTORY_SEPARATOR.$data['id'].DIRECTORY_SEPARATOR.'*'))));
    array_map('rmdir',array_filter((array)array_merge(glob('..'.DIRECTORY_SEPARATOR.'account'.DIRECTORY_SEPARATOR.'anon'.DIRECTORY_SEPARATOR.$data['id'].DIRECTORY_SEPARATOR.'*'))));
    //cache other
    xprivate::autoclear();
    //redirect
    $url=$_POST['redirect'];
    echo "<meta http-equiv=\"refresh\" content=\"0;url=$url\">";
}
