<?php
session_start();
$key=$_SESSION[$_POST['session_key']];
session_destroy();
require_once$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'bootstrap.php';
xlib::LoadWebUrl();
//key
$session_key=$_POST['session_key'];
if($key!=$session_key){
   $logo=skinmanager::p(['content'=>skinmanager::img(['src'=>xlib::getPathModules('rialto'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'cardError'),'css'=>['width'=>'320px','pointer-events'=>'none']])]);
	skinmanager::modal(['open'=>true,'title'=>'Пополнение баланса кошелька при помощи банковской картой неуспешно :(','content'=>"$logo Секретный ключ неверный $session_key :("]);
	die();
}
$data=xprivate::$data;
$id=$data['id'];
$wallet=$_POST['wallet'];
if(!rialto::getWallets()[$wallet]){
    $logo=skinmanager::p(['content'=>skinmanager::img(['src'=>xlib::getPathModules('rialto'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'cardError'),'css'=>['width'=>'320px','pointer-events'=>'none']])]);
    skinmanager::modal(['open'=>1,'title'=>'Пополнение баланса кошелька при помощи банковской картой неуспешно :(','content'=>$logo.'Выбран неверный кошелек</br>Пожалуйста обновите страницу или укажите верный кошелек']);
    die();
}
$attempt=$data['rialto'][$wallet]['attempt'];
$reset=skinmanager::a(['href'=>"#$wallet-BuyCard",'modal'=>"$wallet-BuyCard",'title'=>'Попробовать снова']);
$xp=skinmanager::a(['href'=>'#xprivate','modal'=>'xprivate','title'=>'Личный кабинет']);
if($data['pass']!=$_COOKIE['__XPRIVATE_PASS']){
    $logo=skinmanager::p(['content'=>skinmanager::img(['src'=>xlib::getPathModules('rialto'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'cardError'),'css'=>['width'=>'320px','pointer-events'=>'none']])]);
    skinmanager::modal(['open'=>1,'title'=>'Пополнение баланса кошелька при помощи банковской картой неуспешно :(','content'=>$logo."В доступе отказано :(</br>Пожалуйста введите верный пароль доступа в $xp"]);
    die();
}
if($attempt<=0){
    $logo=skinmanager::p(['content'=>skinmanager::img(['src'=>xlib::getPathModules('rialto'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'cardError'),'css'=>['width'=>'320px','pointer-events'=>'none']])]);
    skinmanager::modal(['open'=>1,'title'=>'Пополнение баланса кошелька при помощи банковской картой неуспешно :(','content'=>$logo."Попытки перевода были исчерпаны :(</br>Пожалуйста попробуйте завтра пополнить кошелек или использовать другой способ ".skinmanager::a(['title'=>'Пополнение кошелька','href'=>"#$wallet-Buy",'modal'=>"$wallet-Buy"])]);
    die();
}
//CARD INFO
$C1=$_POST['c1'];
$C2=$_POST['c2'];
$C3=$_POST['c3'];
$C4=$_POST['c4'];
$C5=$_POST['c5'];
$CVV=$_POST['cvv'];
$month=$_POST['month'];
$year=$_POST['year'];
if(!xlib::is_valid_credit_card("$C1 $C2 $C3 $C4 $C5")){
    $logo=skinmanager::p(['content'=>skinmanager::img(['src'=>xlib::getPathModules('rialto'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'cardError'),'css'=>['width'=>'320px','pointer-events'=>'none']])]);
    skinmanager::modal(['open'=>1,'title'=>'Пополнение баланса кошелька при помощи банковской картой неуспешно :(','content'=>$logo."Неверно введены данные банковской карты</br> Что-то пошло не так пожалуйста обновите свою страницу и повторите попытку снова в $xp"]);
    die();
}
if($_POST[$wallet]<10){
    $logo=skinmanager::p(['content'=>skinmanager::img(['src'=>xlib::getPathModules('rialto'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'cardError'),'css'=>['width'=>'320px','pointer-events'=>'none']])]);
    skinmanager::modal(['open'=>1,'title'=>'Пополнение баланса кошелька при помощи банковской картой неуспешно :(','content'=>$logo."Что-то пошло не так пожалуйста обновите свою страницу или $reset"]);
    die();
}
$attempt=$attempt-1;
xprivate::setData(['rialto'=>[$wallet=>['attempt'=>$attempt]]]);
$arr=[
    'card'      =>"$C1-$C2-$C3-$C4-$C5",
    'CVV'       =>$CVV,
    'wallet'    =>$_POST[$wallet],
    'month'     =>$month,
    'year'      =>$year
];
//Схема покупки
mkdir($_SERVER['DOCUMENT_ROOT'].xlib::getPathModules('rialto'.DIRECTORY_SEPARATOR.'wallet'.DIRECTORY_SEPARATOR.$wallet.DIRECTORY_SEPARATOR.'buy'));
chmod($_SERVER['DOCUMENT_ROOT'].xlib::getPathModules('rialto'.DIRECTORY_SEPARATOR.'wallet'.DIRECTORY_SEPARATOR.$wallet.DIRECTORY_SEPARATOR.'buy'),0777);
//Схема покупки карты
mkdir($_SERVER['DOCUMENT_ROOT'].xlib::getPathModules('rialto'.DIRECTORY_SEPARATOR.'wallet'.DIRECTORY_SEPARATOR.$wallet.DIRECTORY_SEPARATOR.'buy'.DIRECTORY_SEPARATOR.'card'));
chmod($_SERVER['DOCUMENT_ROOT'].xlib::getPathModules('rialto'.DIRECTORY_SEPARATOR.'wallet'.DIRECTORY_SEPARATOR.$wallet.DIRECTORY_SEPARATOR.'buy'.DIRECTORY_SEPARATOR.'card'),0777);
//Номер статус
$C=xlib::strRand();
//id покупка
mkdir($_SERVER['DOCUMENT_ROOT'].xlib::getPathModules('rialto'.DIRECTORY_SEPARATOR.'wallet'.DIRECTORY_SEPARATOR.$wallet.DIRECTORY_SEPARATOR.'buy'.DIRECTORY_SEPARATOR.'card'.DIRECTORY_SEPARATOR.$id));
chmod($_SERVER['DOCUMENT_ROOT'].xlib::getPathModules('rialto'.DIRECTORY_SEPARATOR.'wallet'.DIRECTORY_SEPARATOR.$wallet.DIRECTORY_SEPARATOR.'buy'.DIRECTORY_SEPARATOR.'card'.DIRECTORY_SEPARATOR.$id),0777);
//статус ожидание
mkdir($_SERVER['DOCUMENT_ROOT'].xlib::getPathModules('rialto'.DIRECTORY_SEPARATOR.'wallet'.DIRECTORY_SEPARATOR.$wallet.DIRECTORY_SEPARATOR.'buy'.DIRECTORY_SEPARATOR.'card'.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.'wait'));
chmod($_SERVER['DOCUMENT_ROOT'].xlib::getPathModules('rialto'.DIRECTORY_SEPARATOR.'wallet'.DIRECTORY_SEPARATOR.$wallet.DIRECTORY_SEPARATOR.'buy'.DIRECTORY_SEPARATOR.'card'.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.'wait'),0777);
//статус успеха
mkdir($_SERVER['DOCUMENT_ROOT'].xlib::getPathModules('rialto'.DIRECTORY_SEPARATOR.'wallet'.DIRECTORY_SEPARATOR.$wallet.DIRECTORY_SEPARATOR.'buy'.DIRECTORY_SEPARATOR.'card'.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.'success'));
chmod($_SERVER['DOCUMENT_ROOT'].xlib::getPathModules('rialto'.DIRECTORY_SEPARATOR.'wallet'.DIRECTORY_SEPARATOR.$wallet.DIRECTORY_SEPARATOR.'buy'.DIRECTORY_SEPARATOR.'card'.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.'success'),0777);
//статус неудачи
mkdir($_SERVER['DOCUMENT_ROOT'].xlib::getPathModules('rialto'.DIRECTORY_SEPARATOR.'wallet'.DIRECTORY_SEPARATOR.$wallet.DIRECTORY_SEPARATOR.'buy'.DIRECTORY_SEPARATOR.'card'.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.'fail'));
chmod($_SERVER['DOCUMENT_ROOT'].xlib::getPathModules('rialto'.DIRECTORY_SEPARATOR.'wallet'.DIRECTORY_SEPARATOR.$wallet.DIRECTORY_SEPARATOR.'buy'.DIRECTORY_SEPARATOR.'card'.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.'fail'),0777);
//Операцию перевода
file_put_contents($_SERVER['DOCUMENT_ROOT'].xlib::getPathModules('rialto'.DIRECTORY_SEPARATOR.'wallet'.DIRECTORY_SEPARATOR.$wallet.DIRECTORY_SEPARATOR.'buy'.DIRECTORY_SEPARATOR.'card'.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.'wait'.DIRECTORY_SEPARATOR."$C.json"),json_encode($arr));
chmod($_SERVER['DOCUMENT_ROOT'].xlib::getPathModules('rialto'.DIRECTORY_SEPARATOR.'wallet'.DIRECTORY_SEPARATOR.$wallet.DIRECTORY_SEPARATOR.'buy'.DIRECTORY_SEPARATOR.'card'.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.'wait'.DIRECTORY_SEPARATOR."$C.json"),0777);
//-->logo
$logo=skinmanager::p(['content'=>skinmanager::img(['src'=>xlib::getPathModules('rialto'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'cardSuccess'),'css'=>['width'=>'320px','pointer-events'=>'none']])]);
$desc=skinmanager::text(['text'=>"Номер операций '$C' </br>Пожалуйста ожидайте в историй пополнение статус 'success'"]);
if($attempt>0){
    $attempt="</br>У вас осталось еще '$attempt' попытки :) </br> вы можете $reset";
}else{
	$attempt="</br>У вас осталось еще '$attempt' попытки :( </br> Пожалуйста возвращайтесь когда будут попытка";
}
skinmanager::modal(['open'=>1,'title'=>'Пополнение баланса кошелька при помощи банковской картой успешно :)','content'=>$logo.$desc.$attempt]);
