<?php
require_once$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'bootstrap.php';
//-->Вернуться назад
$xp=skinmanager::a(['href'=>'#xprivate','modal'=>'xprivate','title'=>'Личный кабинет']);
$data=xprivate::getData();
if($data['pass']!=$_COOKIE['__XPRIVATE_PASS']){
    setcookie('__XPRIVATE_PASS',NULL,time()+(86400*30),'/');
    xlib::LoadWebUrl();
    skinmanager::modal(['open'=>1,'title'=>'Личный кабинет','content'=>"В доступе отказано :(</br>Пожалуйста введите верный пароль доступа в $xp"]);
}else{
    setcookie('__XPRIVATE_AUTH',NULL,time()+(86400*30),'/');
    setcookie('__XPRIVATE_PASS',NULL,time()+(86400*30),'/');
    $_COOKIE['__XPRIVATE_PASS']=NULL;
    //account
    unlink("../account/anon/".$data['id'].'/user.json');
    unlink("../account/anon/".$data['id'].'/ico/ava');
    rmdir("../account/anon/".$data['id'].'/ico');
    rmdir("../account/anon/".$data['id']);
    //cache
    $id=substr($data['id'],0,12);
    unlink("../cache/small/$id");
    unlink("../cache/profile/$id");
    xprivate::execute();
    xlib::LoadWebUrl();
}
