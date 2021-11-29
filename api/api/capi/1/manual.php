<?php

/**
 * Возвращение мана
 * -------------------------
 */
use xlib as x;
use api as capi;
class manual {

	/**
     * Выполнение
     */
	public function execute () {
		capi::setStatus(200);
		$arr=[
	'xmessage' => [
		'name'		=>	'Работа с xmessage',
		'methods'	=>	[
			'getMsg'	=>	[
				'name' => 'Возвращаем сообщение :)', 'opt' => [
					'thread' => 'Ид нити'
				]
			]
		]
	]
];
		capi::setResponse($arr);
    	die(capi::getResponse());
		$getDot = sm::panel([
				'title' => 'getDot <= Возвращаем точки :)',
				'content' => '
					Имя api => capi
					<br>Права для использование => system
					<br>Команда => getDot
					<br>Живой пример => capi/system/getDot
				'
		]);
		$getSpace = sm::panel([
				'title' => 'getSpace <= Возвращение пространство :)',
				'content' => '
					Имя api => capi
					<br>Права для использование => system
					<br>Команда => getSpace
				'
		]);
		$getThreads = sm::panel([
				'title' => 'getThreads <= Возвращение нити :)',
				'content' => '
					Имя api => capi
					<br>Права для использование => system
					<br>Команда => getThreads
				'
		]);
		$getNameThread = sm::panel([
				'title' => 'getThreads <= Возвращение имя нити :)',
				'content' => '
					Имя api => capi
					<br>Права для использование => system
					<br>Команда => getNameThread
					<br>Живой пример => capi/system/getNameThread
				'
		]);
		$SendMsg = sm::panel([
				'title' => 'SendMsg <= Отправка сообщение в нить :)',
				'content' => '
					Имя api => capi
					<br>Права для использование => system
					<br>Команда => SendMsg

				'
		]);
		$cmd =sm::panel([
				'title' => 'Основные команды :)',
				'content' => $getDot . $getMsg . $getSpace . $getThreads . $SendMsg
		]);
		$info = sm::panel([
				'title' => 'Как это юзать :)',
				'content' => '
					Вводите в url адрес команды для выполнение
					<br>1.capi - Имя api
					<br>2.system - права для использование по умл (system)
					<br>3.Команда для использование! (getDot)
					<hr>Живой пример
					<br>s2s5.space/capi/system/getDot - Возвращаем точки
					<br>s2s5.space/1/2/3 | Внимание под номерами название!
					<br>Удачи в использование capi ;)
				'
		]);
		echo $body->layout($info . $cmd);
		die($footer->execute());
    }
}
