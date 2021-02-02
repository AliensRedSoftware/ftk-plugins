<?php

/**
 * Возвращение нити
 * -------------------------
 */
class getThreads {

	/**
	 * Выполнение
	 * ----------
	 * 200	-	ОК
	 * 404	-	Ничего не найдено
	 */
	public function execute () {
        $space	=   $_REQUEST['space'];
        $count	=	$_REQUEST['count'];
        $capi   =	new capi();
        $arr	=	getThreads::getToArray($space, $count);
		if ($arr) {
			$capi->setStatus(200);
			$capi->setResponse($arr);
		} else {
			$capi->setStatus(404);
		}
		die($capi->getResponse());
    }

    /**
     * Возвращаем данные в виде массива
     * --------------------------------
     * @return array
     */
    public function getToArray ($space, $count = 0) {
        $capi       =   new capi();
        $xlib       =   new xlib();
        $sql        =   $xlib->getmysql();
        $result     =   mysqli_query($sql , "SELECT * FROM `view` WHERE 1");
        $arr        =   [];
        while ($row = mysqli_fetch_array($result)){
            var_dump($row);
            if($row['title'] != null && $row['selected'] == $space) {
                $id = $row['uuid'];
                $threads = $row['title'];
                $ls     =   mysqli_query($sql, "SELECT * FROM `$id` ORDER BY `id` DESC LIMIT 5");
                $arr[$threads] = ['opt' => ['id' => $id]];
                $txt    =   ['txt' => $row['description']];
                if ($txt['txt']) {
                    $arr[$threads]['opt'] + $txt;
                }
                /*
                if ($count > 0) {
	                while ($lsd = mysqli_fetch_array($ls)) {
	                    $txt = $lsd['text'];
	                    if($lsd['vidos'] != 'N;') {
	                        if (is_array(unserialize($lsd['vidos']))) {
	                            $youtube = ['youtube' => unserialize($lsd['vidos'])];
	                        } else {
	                            $youtube = ['youtube' => trim($lsd['vidos'])];
	                        }
	                    }
	                    if ($lsd['img'] != 'N;') {
	                        if (is_array(unserialize($lsd['img']))) {
	                            $img = ['photo' => unserialize($lsd['img'])];
	                        } else {
	                            $img = ['photo' => trim($lsd['img'])];
	                        }
	                    }
	                    if ($txt['txt']) {
	                        $txt = ['txt' => $txt];
	                    }
	                    if ($youtube['youtube']) {
	                        $file['file'] = $youtube;
	                    }
	                    if ($img['photo']) {
	                        $file['file'] += $img;
	                    }
	                    $arr[$threads] += ['msg' => [$lsd['id'] => ['time' => $lsd['time'], 'name' => $lsd['name']]]];
	                    if ($file['file']['youtube'] || $file['file']['photo']) {
	                        $arr[$threads]['msg'] += $file;
	                    }
	                    if ($txt['txt']) {
	                        $arr[$threads]['msg'] += $txt;
	                    }
	                }
	                $arr[$threads]['msg'] += ['count' => $ls->num_rows];
            	}*/
            }
        }
        return $arr;
    }
}
