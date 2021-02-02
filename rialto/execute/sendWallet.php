<?php
require_once$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'bootstrap.php';
$data=xprivate::getData();
$xp=skinmanager::a(['href'=>'#xprivate','modal'=>'xprivate','title'=>'Личном кабинете']);
$HTRSND=skinmanager::a(['href'=>'#'.$_POST['id'].'-HTRSND','modal'=>$_POST['id'].'-HTRSND','title'=>'Попробовать снова']);
if($data['pass']!=$_COOKIE['__XPRIVATE_PASS']){
    xlib::LoadWebUrl();
    skinmanager::modal(['open'=>1,'title'=>'Личный кабинет','content'=>"В доступе отказано :(</br>Пожалуйста введите верный пароль доступа в $xp"]);
    die();
}
$myHTR=$data['HTR'];
$dataFriend=xprivate::getDataId($_POST['id']);
if($data['id']==$dataFriend['id']){
    $name=$data['name'];
    xlib::LoadWebUrl();
    skinmanager::modal(['open'=>1,'title'=>"Перевод баланса пользователя '$name'",'content'=>"Что-то пошло не так пожалуйста попробуйте снова</br>$HTRSND"]);
    die();
}
$HTR=$dataFriend['HTR'];
$name=$dataFriend['name'];
$balance=$_POST['balance'];
if($myHTR<$balance){
    xlib::LoadWebUrl();
    skinmanager::modal(['open'=>1,'title'=>"Перевод баланса пользователя '$name'",'content'=>"Недостаточно ЧТР пожалуйста измените в $xp или $HTRSND"]);
    die();
}
if($balance<0.0001){
    xlib::LoadWebUrl();
    skinmanager::modal(['open'=>1,'title'=>"Перевод баланса пользователя '$name'",'content'=>"Что-то пошло не так пожалуйста попробуйте снова</br>$HTRSND"]);
    die();
}
//FRIEND
$HTR="$HTR+".$balance;
xprivate::setDataId(['HTR'=>eval('return '.$HTR.';')],$dataFriend['id']);
//MY
$myHTR="$myHTR-".$balance;
xprivate::setData(['HTR'=>eval('return '.$myHTR.';')]);
//OUTPUT
xlib::LoadWebUrl();
//-->logo
$logo=skinmanager::p(['content'=>skinmanager::img(['src'=>xlib::getPathModules('xprivate'.DIRECTORY_SEPARATOR."ico/success"),'css'=>['width'=>'128px','pointer-events'=>'none']])]);
$MYHTR=skinmanager::text(['text'=>'ЧТР '.skinmanager::badge('HTR').' - Баланс:'.skinmanager::badge('Ваш')]);
$MYHTR=$MYHTR.skinmanager::p(['content'=>skinmanager::input(['value'=>xprivate::getData()['HTR'],'type'=>'number','step'=>'0.0001','readonly'=>1])]);
skinmanager::modal(['open'=>1,'title'=>"Перевод баланса пользователя '$name'",'content'=>"Успешно был использован перевод -$balance ЧТР :)$MYHTR$logo"]);
