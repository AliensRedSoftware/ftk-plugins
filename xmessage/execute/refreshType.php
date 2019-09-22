<?php
error_reporting(0);
class refreshType {

    /**
     * Возвращает кол-во тредов в доске
	 * $id - название доски
     */
    public function getCountDoska ($id) {
		require_once '../../../../../options.php';
		$options = new options();
		$path = './theme/borda' . '/' . "uri/о/$id/";
		$iteam = scandir($path);
		$i = 0;
		foreach ($iteam as $value) {
			if ($value != '.' && $value != '..') {
				$i++;
			}
		}
		return $i;
    }

	function execute () {
        $theme = $_POST['theme'];
        require_once '../../ini/ini.php';
		$ini = new ini('options');
        require_once '../../bootstrap/bootstrap.php';
        $bootstrap = new bootstrap();
        require_once '../../xlib/xlib.php';
        $xlib = new xlib();
        require_once '../../xmessage/xmessage.php';
        $xmessage = new xmessage();
        foreach($xmessage->getType() as $type) {
            $btndoski = null;
            $countDoski = 0;
            foreach ($xmessage->getDoski($type) as $doska) {
                $countDoski++;
                $count = $xmessage->getCountDoska($doska);
                $description = $ini->get($type, $doska);
                if ($countDoski > 1) {
                    $btndoski .= $bootstrap->btn([
                        'title' => "[$doska] ($description) " . $bootstrap->span($count),
                        'theme' => $theme,
                        'href' => '/о/' . $doska,
                        'style' => 'width:100%;margin-top:5px;'
                    ]);
                } else {
                    $btndoski .= $bootstrap->btn([
                        'title' => "[$doska] ($description) " . $bootstrap->span($count),
                        'theme' => $theme,
                        'href' => '/о/' . $doska,
                        'style' => 'width:100%;'
                    ]);
                }
            }
            $doski = $bootstrap->border([
                'theme' => $theme,
                'content' => $btndoski
            ]);
            $id = uniqid();
			$btn .= $bootstrap->btn([
	 			'id' => $id,
		 		'title' => $bootstrap->ico('folder-open') . ' ' . $type . ' ' . $bootstrap->span($countDoski),
                'style' => 'width: 100%;',
                'theme' => $theme,
				'collaps' => $xlib->padding([
                    'top' => 15,
                    'content' => $bootstrap->collaps($doski, $id)
                ])
	 		]) ;
        }
        echo $btn;
	}
}
if($_SERVER["REQUEST_METHOD"] == 'POST') {
    $event = new refreshType();
    $event->execute();
} else {
    http_response_code(403);
    die();
}
