<?php
require_once$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'bootstrap.php';
$data=xprivate::$data;
$xp=skinmanager::a(['href'=>'#xprivate','modal'=>'xprivate','title'=>'Личном кабинете']);
$reset=skinmanager::a(['href'=>'#'.$_POST['id'].'-sendBuyHTR','modal'=>$_POST['id'].'-sendBuyHTR','title'=>'Попробовать снова']);
if($data['pass']!=$_COOKIE['__XPRIVATE_PASS']){
    xlib::LoadWebUrl();
    skinmanager::modal(['open'=>1,'title'=>'Личный кабинет','content'=>"В доступе отказано :(</br>Пожалуйста введите верный пароль доступа в $xp"]);
    die();
}
$dataFriend=xprivate::getDataId($_POST['id']);
$name=$dataFriend['private']['name'];
$wallet=$_POST['wallet'];
$walletArr=rialto::getWallets()[$wallet];
if(!$walletArr){
    xlib::LoadWebUrl();
    skinmanager::modal(['open'=>1,'title'=>"Перевод баланса пользователя '$name'",'content'=>'Выбран неверный кошелек</br>Пожалуйста обновите страницу или укажите верный кошелек']);
    die();
}

if($data['id']==$dataFriend['id']){
    xlib::LoadWebUrl();
    skinmanager::modal(['open'=>1,'title'=>"Перевод баланса пользователя '$name'",'content'=>"Что-то пошло не так пожалуйста попробуйте снова</br>$HTRSND"]);
    die();
}
$name=$dataFriend['private']['name'];
if($data['rialto'][$wallet]['value']<$_POST['balance']){
    xlib::LoadWebUrl();
    skinmanager::modal(['open'=>1,'title'=>"Перевод баланса пользователя '$name'",'content'=>"Недостаточно ЧТР пожалуйста измените в $xp или $reset"]);
    die();
}
if($_POST['balance']<$walletArr['step']){
    xlib::LoadWebUrl();
    skinmanager::modal(['open'=>1,'title'=>"Перевод баланса пользователя '$name'",'content'=>"Что-то пошло не так пожалуйста попробуйте снова</br>$reset"]);
    die();
}
//FRIEND
if($dataFriend['id']=='undefined'){
    xlib::LoadWebUrl();
    skinmanager::modal(['open'=>1,'title'=>"Перевод баланса пользователя '$name'",'content'=>"Что-то пошло не так пожалуйста попробуйте снова</br>$reset"]);
    die();
}
$walletFriend=$dataFriend['rialto'][$wallet]['value'].'+'.$_POST['balance'];
xprivate::setDataId(['rialto'=>[$wallet=>['value'=>eval('return '.$walletFriend.';')]]],$dataFriend['id']);
//MY
$walletMy=$data['rialto'][$wallet]['value'].'-'.$_POST['balance'];
xprivate::setData(['rialto'=>[$wallet=>['value'=>eval('return '.$walletMy.';')]]]);
//OUTPUT
xlib::LoadWebUrl();
//-->logo
$logo=skinmanager::p(['content'=>skinmanager::img(['src'=>xlib::getPathModules('rialto'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'repeat'),'css'=>['width'=>'128px','pointer-events'=>'none']])]);
$MYHTR=skinmanager::text(['text'=>'Ваш баланс кошелька '.skinmanager::badge(['txt'=>$wallet])]);
$MYHTR=$MYHTR.skinmanager::p(['content'=>skinmanager::input(['value'=>xprivate::getData()['rialto'][$wallet]['value'],'type'=>'number','step'=>'0.0001','readonly'=>1])]);
skinmanager::modal(['open'=>1,'title'=>"Перевод баланса пользователя '$name'",'content'=>'Успешно был использован перевод -'.$_POST['balance'].' '. skinmanager::badge(['txt'=>$wallet])." :).$MYHTR$logo"]);
