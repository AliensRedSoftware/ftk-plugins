<?php
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'bootstrap.php';

/**
 * Возвращение пространство
 * -------------------------
 */
class manual {

	/**
     * Выполнение
     */
	public function execute () {
		$xlib = new xlib();
		$view = new view();
		$skinmanager = new skinmanager();
		require_once '.' . $xlib->getTheme() . $xlib->getPlatform() . '/' . $xlib->getLibPath() . '/head.php';
		$head = new head();
		$head->execute('Дока по capi v1.1');
		require_once '.' . $xlib->getTheme() . $xlib->getPlatform() . '/' . $xlib->getLibPath() . '/body.php';
		$body = new body();
		require_once '.' . $xlib->getTheme() . $xlib->getPlatform() . '/' . $xlib->getLibPath() . '/footer.php';
		$footer = new footer();
		$skinmanager->applyJs();
		$getDot = $skinmanager->panel([
				'title' => 'getDot <= Возвращаем точки :)',
				'content' => '
					Имя api => capi
					<br>Права для использование => system
					<br>Команда => getDot
					<br>Живой пример => capi/system/getDot
				'
		]);
		$getMsg = $skinmanager->panel([
				'title' => 'getMsg <= Возвращаем сообщение :)',
				'content' => '
					Имя api => capi
					<br>Права для использование => system
					<br>Команда => getMsg
					<br>Живой пример => capi/system/getMsg?dot=test
				'
		]);
		$getSpace = $skinmanager->panel([
				'title' => 'getSpace <= Возвращение пространство :)',
				'content' => '
					Имя api => capi
					<br>Права для использование => system
					<br>Команда => getSpace
				'
		]);
		$getThreads = $skinmanager->panel([
				'title' => 'getThreads <= Возвращение нити :)',
				'content' => '
					Имя api => capi
					<br>Права для использование => system
					<br>Команда => getThreads
				'
		]);
		$getNameThread = $skinmanager->panel([
				'title' => 'getThreads <= Возвращение имя нити :)',
				'content' => '
					Имя api => capi
					<br>Права для использование => system
					<br>Команда => getNameThread
					<br>Живой пример => capi/system/getNameThread
				'
		]);
		$SendMsg = $skinmanager->panel([
				'title' => 'SendMsg <= Отправка сообщение в нить :)',
				'content' => '
					Имя api => capi
					<br>Права для использование => system
					<br>Команда => SendMsg

				'
		]);
		$cmd = $skinmanager->panel([
				'title' => 'Основные команды :)',
				'content' => $getDot . $getMsg . $getSpace . $getThreads . $SendMsg
		]);
		$info = $skinmanager->panel([
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
