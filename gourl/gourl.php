<?php
$redirect = $_GET['url'];
if ($redirect) {
	$gourl = new gourl();
	$gourl->redirect($redirect);
}
class gourl {

	/**
	 * Редирект
	 */
	public function redirect ($redirect) {
		$url	=	"http://" . $_SERVER['SERVER_NAME'] . $redirect;
		echo "<meta http-equiv=\"refresh\" content=\"0;url=$url\">";
	}
}