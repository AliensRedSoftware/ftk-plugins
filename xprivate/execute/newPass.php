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
    $pass=xlib::uuidv4();
    xprivate::setData(['pass'=>$pass]);
    setcookie('__XPRIVATE_PASS',$pass,0,'/');
    $_COOKIE['__XPRIVATE_PASS']=$pass;
    xlib::LoadWebUrl();
    $private=skinmanager::img(['css'=>['width'=>'320px','pointer-events'=>'none'],'src'=>xlib::getPathModules('xprivate'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'private')]);
    skinmanager::modal(['open'=>1,'title'=>'Личный кабинет','content'=>'Успешно сбросился Пароль</br>Это ваш пароль доступа для изменение в личный кабинет '. skinmanager::badge(['txt'=>$pass]) ."</br>Пожалуйста запишите его на листочек и не теряйте :)</br>Также следует ради безопасности никому не говорить про ID и Пароль это возможно мошенники!</br>Познакомьтесь со своим $xp</br>$private"]);
}
