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
    $id=str_replace('-',NULL,xlib::uuidv4());
    xprivate::setData(['id'=>$id]);
    setcookie('__XPRIVATE_AUTH',$id,time()+(86400*30),'/');
    $_COOKIE['__XPRIVATE_AUTH']=$id;
    //account name change
    rename("../account/anon/".$data['id'],"../account/anon/$id");
    rename("../buy/card/".$data['id'],"../buy/card/$id");
    //cache small
    rename("../cache/small/".substr($data['id'],0,12),"../cache/small/".substr($id,0,12));
    rename("../cache/profile/".substr($data['id'],0,12),"../cache/profile/".substr($id,0,12));
    xlib::LoadWebUrl();
    skinmanager::modal(['open'=>1,'title'=>'Личный кабинет','content'=>"Успешно сбросился ID</br>Пожалуйста можете вернуться в $xp"]);
}
