<?php
$_REQUEST['auth']=true;
require_once$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'bootstrap.php';
//-->Вернуться назад
$xp=skinmanager::a(['href'=>'#xprivate','modal'=>'xprivate','title'=>'Личный кабинет']);
$id=$_POST['__XPRIVATE_AUTH'];
$pass=$_POST['__XPRIVATE_PASS'];
$data=xprivate::getDataId($id);
if($data['id']=='undefined'||$id==substr($data['id'],0,12)){
    $data=xprivate::getData();
    if($data['id']){
        setcookie('__XPRIVATE_AUTH',$data['id'],time()+(86400*30),'/');
        xlib::LoadWebUrl();
        skinmanager::modal(['open'=>1,'title'=>'Личный кабинет','content'=>"Не удается войти в аккаунт</br>ID неверный. Пожалуйста введите верный ID в $xp"]);
    }
}else{
    if($data['pass']==$pass){
        setcookie('__XPRIVATE_AUTH',$data['id'],time()+(86400*30),'/');
        setcookie('__XPRIVATE_PASS',$pass,time()+(86400*30),'/');
        $_COOKIE['__XPRIVATE_AUTH']=$data['id'];
        $_COOKIE['__XPRIVATE_PASS']=$pass;
        xprivate::setData(['auth'=>true]);
        xlib::LoadWebUrl();
        skinmanager::modal(['open'=>1,'title'=>'Личный кабинет','content'=>"Успешно вошли в аккаунт</br>Пожалуйста можете вернуться в $xp"]);
    }else{
        //VERIVED USER
        if($data['pass']!=$_COOKIE['__XPRIVATE_PASS']){
            setcookie('__XPRIVATE_PASS',NULL,time()+(86400*30),'/');
        }
        xlib::LoadWebUrl();
        skinmanager::modal(['open'=>1,'title'=>'Личный кабинет','content'=>"В доступе отказано :(</br>Пожалуйста введите верный пароль доступа в $xp"]);
    }
}
