<?php
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'bootstrap.php';
error_reporting(0);
class saveSettings extends xlib {

	/**
	 * Выполнить
	 * ------------
	 */
	function execute () {
    	$id				=	$_REQUEST['id'];
    	$name			=	$_REQUEST['name'];
    	$number			=	$_REQUEST['number'];
    	$date			=	$_REQUEST['date'];
    	$IdMessage		=	$_REQUEST['idMessage'];
    	require_once	"../../../page/head.php";
		$head			=	new head();
		require_once	"../../../page/body.php";
		$body			=	new body();
    	$skinmanager	=	new skinmanager();
    	$xmessage		=	new xmessage();
    	//-->Установка имени
    	$isName = $this->setName($id, $name);//Установить имя
    	if ($isName) {
        	$oldName	=	$_COOKIE['__xmessage_name'];
        	if (!$oldName) {
            	$oldName = 'Нейзвестный';
            }
        	$change	.=	"</br>[Имя] - $oldName изменилось на $name";
        }
    	//-->Установка номер поста
    	$isNumber	=	$this->setNumber($number);
    	if ($isNumber) {
        	$newNumber	=	$_COOKIE['__xmessage_number'];
        	if ($newNumber) {
            	$newNumber	=	'Не установлен';
            	$_REQUEST['number'] = false;
            } else {
            	$newNumber = 'Установлен';
            }
        	$change	.=	"</br>[Номер поста] - $newNumber";
        }
        //-->Установка номер поста
    	$isDate	=	$this->setDate($date);
    	if ($isDate) {
        	$newDate	=	$_COOKIE['__xmessage_date'];
        	if ($newDate) {
            	$newDate	=	'Не установлен';
            	$_REQUEST['date'] = false;
            } else {
            	$newDate = 'Установлен';
            }
        	$change	.=	"</br>[Дата отправки] - $newDate";
        }
        //-->Установка номер поста
    	$isIdMessage	=	$this->setIdMessage($IdMessage);
    	if ($isIdMessage) {
        	$newIdMessage	=	$_COOKIE['__xmessage_IdMessage'];
        	if ($newIdMessage) {
            	$newIdMessage	=	'Не установлен';
            	$_REQUEST['idMessage'] = false;
            } else {
            	$newIdMessage = 'Установлен';
            }
        	$change	.=	"</br>[Ид сообщение] - $newIdMessage";
        }
    	$form = $skinmanager->modal([
						'title' 	=> '[Сохранение изменение в панели] -> Успешно :)',
						'content'	=> 'Изменение успешно изменились :)' . $change
					]);
		if ($skinmanager->getSkin() == 'bootstrap337') {
			$this->js("$('#$form').modal('toggle')");
		} elseif ($skinmanager->getSkin() == 'uikit') {
			$this->js("UIkit.modal(\"#$form\").show();");
		} else {
			echo "<meta http-equiv=\"refresh\" content=\"0;url=#$form\">";
		}
		echo $head->execute('[Сохранение изменение в панели] -> Успешно :)');
		echo $body->layout($xmessage->getSendBox($id) . $xmessage->getThread($id));
    }

	/**
	 * Установить имя
	 * ---------------
	 */
	function setName ($id, $name) {
		require_once	"../../../page/head.php";
		$head			=	new head();
		require_once	"../../../page/body.php";
		$body			=	new body();
    	$xmessage		=	new xmessage();
    	$skinmanager	=	new skinmanager();
    	switch ($name) {
        	case 'Нейзвестный':
        		if ($_COOKIE['__xmessage_name']) {
        			setcookie("__xmessage_name", null, time() + (86400 * 30), '/');
                	return true;
                }else {
        			return false;
                }
        	break;
        	case false:
        		setcookie("__xmessage_name", null, time() + (86400 * 30), '/');
        		$_REQUEST['name']	=	'Нейзвестный';
        		return true;
        	break;
        	case $_COOKIE['__xmessage_name']:
        		return false;
        	break;
        	default:
        		if (mb_strlen($name) >= 32) {
                	$form = $skinmanager->modal([
						'title' 	=> '[Сохранение изменение в панели] -> ошибка в имени!',
						'content'	=> 'Нужно ввести имя не более 32 символов. :('
					]);
            		if ($skinmanager->getSkin() == 'bootstrap337') {
                		$this->js("$('#$form').modal('toggle')");
            		} elseif ($skinmanager->getSkin() == 'uikit') {
                		$this->js("UIkit.modal(\"#$form\").show();");
            		} else {
                		echo "<meta http-equiv=\"refresh\" content=\"0;url=#$form\">";
            		}
            		echo $head->execute('[Сохранение изменение в панели] -> ошибка в имени!');
            		echo $body->layout($xmessage->getSendBox($id) . $xmessage->getThread($id));
					die();
                } elseif(mb_strlen($name) < 2) {
					$form = $skinmanager->modal([
						'title' 	=> '[Сохранение изменение в панели] -> ошибка в имени!',
						'content'	=> 'Нужно ввести имя более 2 символов. :('
					]);
            		if ($skinmanager->getSkin() == 'bootstrap337') {
                		$this->js("$('#$form').modal('toggle')");
            		} elseif ($skinmanager->getSkin() == 'uikit') {
                		$this->js("UIkit.modal(\"#$form\").show();");
            		} else {
                		echo "<meta http-equiv=\"refresh\" content=\"0;url=#$form\">";
            		}
            		echo $head->execute('[Сохранение изменение в панели] -> ошибка в имени!');
            		echo $body->layout($xmessage->getSendBox($id) . $xmessage->getThread($id));
                	die();
				} else {
                	setcookie("__xmessage_name", $name, time() + (86400 * 30), '/');
                	return true;
                }
        	break;
        }
    }

	/**
	 * Установить возвращение номер поста
	 * ----------------------------------
	 */
	public function setNumber($val) {
    	require_once	"../../../page/head.php";
		$head			=	new head();
		require_once	"../../../page/body.php";
		$body			=	new body();
    	$xmessage		=	new xmessage();
    	$skinmanager	=	new skinmanager();
    	if ($val != $_COOKIE['__xmessage_number']) {
        	setcookie("__xmessage_number", $val, time() + (86400 * 30), '/');
        	return true;
        } else {
        	return false;
        }
    }

	/**
	 * Установить возвращение Дата отправки
	 * ----------------------------------
	 */
	public function setDate($val) {
    	require_once	"../../../page/head.php";
		$head			=	new head();
		require_once	"../../../page/body.php";
		$body			=	new body();
    	$xmessage		=	new xmessage();
    	$skinmanager	=	new skinmanager();
    	if ($val != $_COOKIE['__xmessage_date']) {
        	setcookie("__xmessage_date", $val, time() + (86400 * 30), '/');
        	return true;
        } else {
        	return false;
        }
    }

	/**
	 * Установить возвращение Id_Message
	 * ----------------------------------
	 */
	public function setIdMessage($val) {
    	require_once	"../../../page/head.php";
		$head			=	new head();
		require_once	"../../../page/body.php";
		$body			=	new body();
    	$xmessage		=	new xmessage();
    	$skinmanager	=	new skinmanager();
    	if ($val != $_COOKIE['__xmessage_IdMessage']) {
        	setcookie("__xmessage_IdMessage", $val, time() + (86400 * 30), '/');
        	return true;
        } else {
        	return false;
        }
    }
}

$xlib   =	new xlib();
if($_SERVER["REQUEST_METHOD"] == 'POST' && constant() == NULL) {
	$event  =   new saveSettings();
	$event->execute();
} else {
	echo $xlib->alert();
}