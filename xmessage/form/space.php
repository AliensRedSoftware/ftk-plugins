<?php
error_reporting(0);
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'bootstrap.php';
require_once "../../../page/head.php";
$head = new head();
require_once "../../../page/body.php";
$body = new body();
require_once "../../../page/footer.php";
$footer = new footer();
$dot = $_GET['dot'];
//-->Модальная форма (#newSpace)
$xlib			=	new xlib();
$skinmanager	=	new skinmanager();
$xmessage		=	new xmessage();
$footer->execute();
$skinmanager->ApplyJs();
$form			=	$skinmanager->modal([
	'title'		=>	'Создание нового пространство' . $xmessage->getVersion(),
	'content'	=>	$xmessage->getCreateSpace($dot)
]);
if ($skinmanager->getSkin() == 'bootstrap337') {
	$xlib->js("$('#$form').modal('toggle')");
} elseif ($skinmanager->getSkin() == 'uikit') {
	$xlib->js("UIkit.modal(\"#$form\").show();");
} else {
	echo "<meta http-equiv=\"refresh\" content=\"0;url=#$form\">";
}
echo $head->execute('[Создание пространство] -> процесс ;)');
die($body->layout('Процесс создание пространство</br>Внимание если вы передумали создавать пространство или такое пространство уже есть то пожалуйста выберете уже созданное пространство в точки или создайте другое с другим именем ;)'));