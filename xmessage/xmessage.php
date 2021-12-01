<?php

/**
 * @name Создание новых сообщение (Создание своего форума)
 * @version 1.80
 */
//Конфигурация
define('__XMESSAGE_DATABASE','XMESSAGE_DEFAULT');//Название базы данных
define('__XMESSAGE_DOT_NAME_ROOT','о');//Название корневого рута
//Создание нитей
define('__XMESSAGE_THREAD_TITLE_MAX',64);//Максимальное кол-во символов название
define('__XMESSAGE_THREAD_TITLE_MIN',2);//Минимальное кол-во символов название
define('__XMESSAGE_DESC_MAX',8096);//Максимальное кол-во символов сообщение
define('__XMESSAGE_DESC_MIN',0);//Минимальное кол-во символов сообщение
//Создание точки
define('__XMESSAGE_DOT_TITLE_MAX',64);//Максимальное кол-во символов название
//---------------------------------------------
use xlib as x;
use skinmanager as sm;
use jquery as jq;
use xprivate as xp;
use xmotion as moja;
class xmessage {

	public static $id;		//ИД Нити выбранной
	public static $threads;	//Нити
	public static $path;	//Выбранный путь

	/**
	 * Выполнение
	 */
	function execute(){
		self::$id=self::getSelectedThread(); //ИД Нити выбранной
		self::$path=self::getPathSelected(); //Выбранный путь
	}

	/**
     * Возвращаем версию
	 * @return string
	 */
	public function getVersion () {
		return' ('.__CLASS__.' '.sm::badge(['txt'=>'1.80']).')';
	}

	/**
	 * Инициализация database
	 * @return sql
	 */
	function getmysql(){
		$sql=x::getmysql();
		$database=trim(__XMESSAGE_DATABASE);
        if(!mysqli_select_db($sql, $database)){
            mysqli_query($sql, "CREATE DATABASE $database CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");
        }
        mysqli_select_db($sql, $database);
        if(!x::isTable($database, 'view')){
        	$desc=__XMESSAGE_DESC_MAX;
        	mysqli_query($sql, "CREATE TABLE `view` (
  `id` INT NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`),
  `desc` varchar($desc) NOT NULL,
  `name` varchar(32) NOT NULL,
  `title` varchar(64) NOT NULL,
  `uuid` varchar(16) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci");
        }
        return $sql;
	}

	/**
	 * Возвращаем путь загрузки файлов
	 * id - нить
	 * @return string
	 */
	public function getUploadFile($id){
		if(x::is_uuidv4($id)){
			$id=self::$path.$id;
		}
		mkdir($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR,0777,true);
		return DIRECTORY_SEPARATOR.'tmp'.$id.DIRECTORY_SEPARATOR;
	}

    /**
     * Удаление сломанных файлов не до конца которые загрузились
     */
	public function ClearFileThreadBAD($id){
     	$path=$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'tmp'.self::$path.$id;
     	$DIRS=x::scandir($path);
     	$DRX=[];
     	$BAD=false;
     	foreach($DIRS as $DIR){
     		$DRX[$path.DIRECTORY_SEPARATOR.$DIR]=false;
     	}
        $result=mysqli_query(x::getmysql(),"SELECT * FROM `$id` ORDER BY `id`");
     	while($R=mysqli_fetch_array($result)){
     		$files=unserialize($R['img']);
     		foreach($files as $file){
     			$file=$file;
     			$DRX[$_SERVER['DOCUMENT_ROOT'].$file]=true;
     		}
     	}
     	foreach($DRX as $FILE=>$DELETED){
     		if($FILE&&!$DELETED){
     			$BAD.='|'.$FILE;
     			unlink($FILE);
            }
     	}
     	return $BAD;
	}

	/**
     * Возвращает кол-во Сообщение в нити
	 * id - нить
	 * @return int
     */
	public function getCountMsg($id){
		$result=mysqli_query(self::getmysql(),"SELECT COUNT(*) FROM `$id`");
		return mysqli_fetch_array($result)[0];
	}

	/**
	 * Возвращаем все нити в виде массива
	 * ----------------------------------
	 * return array
	 */
	public function getThreadsToArray($path=false,$R=true,$hidden=false){
		$out=[];
		$H=[];
		//path...
		if(!$path){
			$dot=self::$path;
		}else{
			$dot=$path;
		}
		//redirect
		if($R){
			foreach(new RecursiveTreeIterator(new RecursiveDirectoryIterator($_SERVER['DOCUMENT_ROOT'].x::getTheme().'uri'.$dot, RecursiveDirectoryIterator::SKIP_DOTS)) as $path){
				$name=basename($path);
				$id=explode('.',$name)[0];//id thread
				//-->path thread
				$path=substr(trim(str_replace('|',NULL,$path)),2);
				if(!x::startWith(DIRECTORY_SEPARATOR,$path)){
					$path=DIRECTORY_SEPARATOR.$path;
				}
				if(x::getExtension($name)=='php'){
					if(x::is_uuidv4($id)){
						$path=str_replace($_SERVER['DOCUMENT_ROOT'].x::getTheme().'uri',NULL,$path);
						$out[$id]=str_replace('.php',NULL,$path);
					}else{
						if(md5_file($path)==$id){
							$path=str_replace($_SERVER['DOCUMENT_ROOT'].x::getTheme().'uri',NULL,$path);
							$H[$id]=str_replace('.php',NULL,$path);
						}
					}
				}
				//autoclear other ROOT...
				//if($dot==self::getROOT()){
					//self::autoclear($out+$H,true);
				//}
			}
		}else{
			foreach(x::scandir($_SERVER['DOCUMENT_ROOT'].x::getTheme().'uri'.$dot) as $dr){
				$ext=explode('.', $dr);
				if(x::is_uuidv4($ext[0])){
					$out[$ext[0]]=$path.$ext[0];
				}
			}
		}
		//HIDDEN mode
		if($hidden){
			return $H;
		}
		//SORT THREAD
		if(!$GLOBALS['__XMESSAGE_IGNORE_THREAD']){
			switch($_COOKIE['__XMESSAGE_SORT_THREAD']){
				case 'Высокие просмотры':
					$infos=self::getInfoThreadToArray();//Информация об нити в виде массива
					$arr=[];
					foreach($out as $id=>$thread){
						$arr[$thread]=$infos[$id]['view'];
					}
					//effect
					arsort($arr,SORT_NATURAL);
					//unset out
					$out=[];
					foreach($arr as $thread=>$time){
						$out[basename($thread)]=$thread;
					}
				break;
				case 'Низкие просмотры':
					$infos=self::getInfoThreadToArray();//Информация об нити в виде массива
					$arr=[];
					foreach($out as $id=>$thread){
						$arr[$thread]=$infos[$id]['view'];
					}
					//effect
					asort($arr,SORT_NATURAL);
					//unset out
					$out=[];
					foreach($arr as $thread=>$time){
						$out[basename($thread)]=$thread;
					}
				break;
				case 'Новые по дате':
					$arr=[];
					foreach($out as $id=>$thread){
						$arr[$thread]=filemtime($_SERVER['DOCUMENT_ROOT'].x::getTheme().'uri'."$thread.php");
					}
					//effect
					arsort($arr,SORT_NATURAL);
					//unset out
					$out=[];
					foreach($arr as $thread=>$time){
						$out[basename($thread)]=$thread;
					}
				break;
				case 'Старые по дате':
					$arr=[];
					foreach($out as $id=>$thread){
						$arr[$thread]=filemtime($_SERVER['DOCUMENT_ROOT'].x::getTheme().'uri'."$thread.php");
					}
					//effect
					asort($arr,SORT_NATURAL);
					//unset out
					$out=[];
					foreach($arr as $thread=>$time){
						$out[basename($thread)]=$thread;
					}
				break;
			}
		}
		return $out;
	}

	/**
	 * Очистка файлов не созданных в нити
	 * threads-Стэк с нитями
	 * R-Рекурсия очистка
	 */
	protected function autoclear($threads,$R=false){
		if($R){
			foreach(new RecursiveTreeIterator(new RecursiveDirectoryIterator($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'tmp', RecursiveDirectoryIterator::SKIP_DOTS)) as $path){
				$name=new SplFileInfo(dirname($path));
				$name=$name->getFilename();
				if(!$threads[$name]&&x::is_uuidv4($name)){
					$path=explode('-',dirname($path),2)[1];
					array_map('unlink',array_filter((array)array_merge(glob($path.DIRECTORY_SEPARATOR.'*'))));
					rmdir($path);
				}
			}
		}
	}

	/**
	 * Возвращаем все нити в виде объекта
	 * ----------------------------------
	 * @return object
	 */
	public function getThreadsToObject($path=false,$R=true){
		$arr=[];
		foreach(self::getThreadsToArray($path,$R) as $id=>$thread){
			$arr[$id]=$thread;
		}
		return $arr;
	}

	/**
	 * Возвращаем форму удаление нити
	 * id - Ид формы
	 */
	public function getDelThreadForm($id){
		$action=x::getPathModules(__CLASS__."/execute/delThread.php?id=$id");
		//-->Описание
		$desc=sm::txt(['txt'=>'Внимание удаление нити будет навсегда</br>Обратно вернуть нить будет невозможным!']);
		//-->Выполнить
		$submit=sm::input(['type'=>'submit']);
		sm::modal(['id'=>"delThread$id",'title'=>'Вы точно хотите удалить нить '.sm::badge(['txt'=>$id]).' ?',
			'content'=>sm::form(['id'=>x::RedirectUpdate(),'action'=>$action,'method'=>'post','content'=>$desc.$submit])]);
	}

	/**
	 * Возвращает сообщение об ошибки запроса
	 */
	protected function getAlertError() {
		$bootstrap = new bootstrap();
		$xlib = new xlib();
		return $xlib->margin([
			'top' => 15,
			'content' => $bootstrap->alert($bootstrap->ico('exclamation-sign') . "Ошибка в запросе пожалуйста обновите страницу или выйдите из браузера =(", 'danger')
		]);
	}

	protected function getProgressBar () {
		$bootstrap = new bootstrap();
		$xlib = new xlib();
		return $xlib->margin([
			'top' => 15,
			'content' => $bootstrap->progressbar(100, 'striped')
		]);
	}

	/**
	 * Возвращает postType отправка выполение Создание категорий
	 */
	protected function postType ($pathini) {
return '<?php
class type {
	
	/**
	 * Создать новую категорию
	 */
	function newtype ($name) {
		require_once "../../ini/ini.php";
		$ini = new ini("options");
        require_once "../../bootstrap/bootstrap.php";
		$bootstrap = new bootstrap();
		require_once "../../xlib/xlib.php";
		$xlib = new xlib();
		require_once "../../../../../options.php";
		$options = new options();
        $list = $ini->getSections();
        foreach ($list as $val) {
            if ($name == $val) {
				echo $xlib->margin([
				"top" => 15,
				"content" => $bootstrap->alert($bootstrap->ico("exclamation-sign") . "Не удается создать категорию потому что такая уже есть!", "danger")
				]);
				die();
            }
        }
        if (count($ini->getKeys($list[count($list) - 1])) < 1 && count($list) != 0) {
			echo $xlib->margin([
				"top" => 15,
				"content" => $bootstrap->alert($bootstrap->ico("exclamation-sign") . "Не удается создать категорию потому что в последней категорий ничего нету зачем создавать еще пустую категорию ?)!", "danger")
				]);
        } else {
			$ini->addSection($name);
			echo $xlib->margin([
					"top" => 15,
					"content" => $bootstrap->alert($bootstrap->ico("info-sign") . "Категория успешно создалась ;)", "success")
				]);
			$xlib->js("$(\'#refreshType\').click();");
			return true;
		}
	}

	/**
	 * Создание категорий
	 */
    function execute () {
        $name = trim($_POST["xmessage_typeName"]);
        require_once "../../bootstrap/bootstrap.php";
		$bootstrap = new bootstrap();
        require_once "../../xlib/xlib.php";
		$xlib = new xlib();
        $charlower = $xlib->islowupper($name);
        if ($charlower == true) {
			echo $xlib->margin([
				"top" => 15,
				"content" => $bootstrap->alert($bootstrap->ico("exclamation-sign") . "Большие буквы нельзя использовать!", "danger")
			]);
			die();
        }
		if($name == null) {
			echo $xlib->margin([
				"top" => 15,
				"content" => $bootstrap->alert($bootstrap->ico("exclamation-sign") . "Название категорий не должно быть пустое!", "danger")
			]);
			$xlib->js("$(\'#xmessage_typeName\').val(null);");
			die();
		}
		if(strlen($name) >= 16) {
			echo $xlib->margin([
				"top" => 15,
				"content" => $bootstrap->alert($bootstrap->ico("exclamation-sign") . "Символов в название не более чем 15", "danger")
			]);
			die();
		}
		$char = $xlib->getCharToArray();
		$number = $xlib->getNumberToArray();
		$badName = $xlib->isCharArray($char, $name);
		if ($badName == true) {
			echo $xlib->margin([
				"top" => 15,
				"content" => $bootstrap->alert($bootstrap->ico("exclamation-sign") . "такой символ <b>[$badName]</b> нельзя использовать", "danger")
			]);
			die();
		}
		$badName = $xlib->isCharArray($number, $name);
		if ($badName == true) {
			echo $xlib->margin([
				"top" => 15,
				"content" => $bootstrap->alert($bootstrap->ico("exclamation-sign") . "такую цифру <b>[$badName]</b> нельзя использовать", "danger")
			]);
			die();
		}
        return $this->newtype($name);
	}
}
require_once "../../xlib/xlib.php";
$xlib = new xlib();
require_once "../../ini/ini.php";
$ini = new ini(' . "'$pathini'" . ');
$event = new type();
$response = $event->execute();
if ($response == true) {
	$uuid = $xlib->uuidv4();
	$ini->set("settings", "type", $uuid);
	rename($_SERVER["SCRIPT_FILENAME"], $uuid);
}

';
	}

	/**
     * Возвращаем все точки в виде массива
	 * -----------------------------------
	 * path-путь
	 * R-Нахождение рекурсий точки
	 * @return Array
     */
    public function getDotToArray($PATH=false,$R=false){
    	$ARR=[];
    	if(!$PATH){
    		$PATH=self::$path;
    	}else{
    		//Проверка подлинности
			$valid=explode(DIRECTORY_SEPARATOR,$PATH);
			if($valid[1]!=__XMESSAGE_DOT_NAME_ROOT){
				return $ARR;
			}
			$PATH.=DIRECTORY_SEPARATOR;
    	}
    	if($R){
    		foreach(new RecursiveTreeIterator(new RecursiveDirectoryIterator($_SERVER['DOCUMENT_ROOT'].x::getTheme().'uri'.$PATH,RecursiveDirectoryIterator::SKIP_DOTS)) as $dot){
    			$dot=substr(trim(str_replace('|',NULL,$dot)),2);
				if(!x::startWith(DIRECTORY_SEPARATOR,$dot)){
					$dot=DIRECTORY_SEPARATOR.$dot;
				}
				if(is_dir($dot)){
					$dot=str_replace($_SERVER['DOCUMENT_ROOT'].x::getTheme().'uri',NULL,$dot);
					$name=explode(DIRECTORY_SEPARATOR,$dot);
					$ARR[$dot]=$name[count($name) - 1];
				}
			}
		}else{
			foreach(x::scandir($_SERVER['DOCUMENT_ROOT'].x::getTheme().'uri'.$PATH) as $dot){
				if(is_dir($_SERVER['DOCUMENT_ROOT'].x::getTheme().'uri'.$PATH.$dot)){
					$ARR[$PATH.$dot]=$dot;
				}
			}
		}
		return $ARR;
    }

	/**
	 * Возвращаем название ROOT
	 * @return string
	 */
	public function getROOT(){
		return DIRECTORY_SEPARATOR.__XMESSAGE_DOT_NAME_ROOT;
	}

	/**
	 * Возврщает кол-во нитей в точки
	 * -------------------------------
	 * dot-Полный путь
	 */
	public function getCountThread($dot){
		$i=0;
		foreach(x::scandir($_SERVER['DOCUMENT_ROOT'].x::getTheme().'uri'.$dot) as $thread){
			if(x::is_uuidv4(explode('.',$thread)[0])){
				$i++;
			}
		}
		return $i;
	}

	/**
	 * Возвращаем выбранный путь точки (Исправленный)
	 * url-Переход ссылок
	 */
	public function getPathSelected($url=false){
		$uri=x::geturi();
		//Проверка подлинности
		$valid=explode(DIRECTORY_SEPARATOR,$uri);
		if($valid[1]!=__XMESSAGE_DOT_NAME_ROOT){
			if($url){
				$data=xp::$data;
				$item['item']=[];
				$step=DIRECTORY_SEPARATOR.__XMESSAGE_DOT_NAME_ROOT;
				$count=count(self::getDotToArray($step));
				$item['item']+=['Посмотреть '.sm::badge(['txt'=>self::getCountThread($step)])=>['href'=>$step]];
				if(x::isModule('xprivate',false)){
					//clear
					if($data['xmessage']['dots'][$step]['cls']&&$count>0||$data['root']&&$count>0){
						$item['item']+=['Очистить'=>['href'=>'#'.self::getClearDot($step),'modal'=>true]];
					}
				}
				$lera.=DIRECTORY_SEPARATOR.sm::dropdown([__XMESSAGE_DOT_NAME_ROOT.' '.sm::badge(['txt'=>$count])=>$item]);
				return $lera.DIRECTORY_SEPARATOR;
			}
			return self::getROOT().DIRECTORY_SEPARATOR;
		}
		$thread=$valid[count($valid) - 1];
		if(x::is_uuidv4($thread)){
			$uri=str_replace($thread,NULL,$uri);
		}else{
			$uri=$uri.DIRECTORY_SEPARATOR;
		}
		if($url){
			$data=xp::$data;
			foreach(explode(DIRECTORY_SEPARATOR,$uri) as $foo){
				if(!empty($foo)){
					$step.=DIRECTORY_SEPARATOR.$foo;
					if(is_dir($_SERVER['DOCUMENT_ROOT'].x::getTheme().'uri'.$step)){
						if(x::isModule('xprivate',false)){
							$item['item']=[];
							$count=self::getCountThread($step);
							$foo.=' '.sm::badge(['txt'=>count(self::getDotToArray($step))]);
							$item['item']+=['Посмотреть '.sm::badge(['txt'=>$count])=>['href'=>$step]];
							if($step==self::getROOT()){
								//clear
								if($data['xmessage']['dots'][$step]['cls']&&$count>0||$data['root']&&$count>0){
									$item['item']+=['Очистить'=>['href'=>'#'.self::getClearDot($step),'modal'=>true]];
								}
							}else{
								//clear
								if($data['xmessage']['dots'][$step]['cls']&&$count>0||$data['root']&&$count>0){
									$item['item']+=['Очистить'=>['href'=>'#'.self::getClearDot($step),'modal'=>true]];
								}
								//del
								if($data['xmessage']['dots'][$step]['del']||$data['root']){
									$item['item']+=['Удалить'=>['href'=>'#'.self::getDeleteDot($step),'modal'=>true]];
								}
							}
						}
						$lera.=DIRECTORY_SEPARATOR.sm::dropdown([$foo=>$item]);
					}
				}
			}
			return $lera.DIRECTORY_SEPARATOR;
		}
		//valid
		unset($step);
		foreach(explode(DIRECTORY_SEPARATOR,$uri) as $ls){
			if(!is_dir($_SERVER['DOCUMENT_ROOT'].x::getTheme().'uri'.$step.DIRECTORY_SEPARATOR.$ls)){
				return $step;
			}
			$step.=$ls.DIRECTORY_SEPARATOR;
		}
		return $uri;
	}

	/**
	 * Возвращаем выбранна ли точка
	 * @return bool
	 */
	public function isDot(){
		if(self::$path==self::getROOT().DIRECTORY_SEPARATOR||$_POST['dot']==self::getROOT()){
			return false;
		}else{
			return true;
		}
	}

	/**
	 * Возвращаем кол-во точек (Выбранная точка)
	 * @return int
	 */
	public function getCountDot(){
		$DIRS=x::scandir($_SERVER['DOCUMENT_ROOT'].x::getTheme().'uri'.self::$path);
		$out=[];
		foreach($DIRS as $DIR){
			if(!explode('.',$DIR)[1]){
				array_push($out,$DIR);
			}
		}
		return count($out);
	}

	/**
	 * Возвращаем Информацию об нити
	 * -----------------------------
	 * @return array
	 */
	public function getInfoThreadToArray($hidden=false){
		$arr=[];
		$sql=self::getmysql();
		$GLOBALS['__XMESSAGE_IGNORE_THREAD']=true;
		foreach(self::getThreadsToArray(false,true,$hidden) as $id=>$thread){
			$result=mysqli_query($sql,"SELECT * FROM `view`");
		 	while($R=mysqli_fetch_array($result)){
		 		if($R['uuid']==$id){
		 			$title=$R['title'];
		 			$name=$R['name'];
		 			$result=mysqli_query($sql,"SELECT * FROM `$id` LIMIT 1");
		 			$result=mysqli_fetch_array($result);
		 			$time=explode('(',$result[3])[0];
		 			if(x::is_uuidv4($title)){
		 				$title='Безымянная';
		 			}
		 			$arr+=[$id=>['id'=>$id,'count'=>self::getCountMsg($id),'path'=>$thread,'name'=>$name,'time'=>$time,'title'=>$title,'superuser'=>$result[4],'view'=>$result[7],'type'=>$result[9]]];
		 			$id=false;
		 		}
		 	}
		 	if(isset($id)){
		 		$result=mysqli_query($sql,"SELECT * FROM `$id` LIMIT 1");
		 		$result=mysqli_fetch_array($result);
				$time=explode('(',$result[3])[0];
				if(x::is_uuidv4($id)||is_file($_SERVER['DOCUMENT_ROOT'].x::getTheme()."uri$thread.php")){
					$title='Безымянная';
		 		}
		 		$arr+=[$id=>['id'=>$id,'count'=>self::getCountMsg($id),'path'=>$thread,'title'=>$title,'time'=>$time,'superuser'=>$result[4],'view'=>$result[7],'type'=>$result[9]]];
			}
		}
		$GLOBALS['__XMESSAGE_IGNORE_THREAD']=false;
		return $arr;
	}

	/**
	 * Возвращаем форму (Представление формата нити)
	 * @return object
	 */
	public function getFormatObject(){
		//-->Упорядочить нити
		$list=[];
		$list+=['Сообщение'=>[]];
		$list+=['Студия'=>[]];
		$list=sm::combobox(['css'=>['width'=>'100%'],'name'=>'__XMESSAGE_FORMAT_THREAD',$list]);
		return sm::panel(['title'=>'Тип','content'=>$list]);
	}

	/**
     * Возвращаем форму (Создание нити)
	 * @return form
     */
	public function getCreateThread(){
		return sm::modal(['id'=>'thread','title'=>'Создание новой нити'.self::getVersion(),'content'=>self::getCreateThreadObject()]);
	}

	/**
     * Возвращаем форму (Создание нити)
	 * @return object
     */
	public function getCreateThreadObject(){
		$data=xp::$data;
		$dot=substr(self::$path, 0, -1);
		if($data['xmessage']['dots'][$dot]['newThread']||$data['xmessage']['dots'][$dot]['root']||$data['root']){
			$id=x::RedirectUpdate(x::uuidv4());
			$action=x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'execute'.DIRECTORY_SEPARATOR.'newThread.php');
			$maxTitle=constant('__XMESSAGE_THREAD_TITLE_MAX');
			$minTitle=constant('__XMESSAGE_THREAD_TITLE_MIN');
			//-->Название нити
			if($maxTitle>0){
				if($minTitle>0){
					$title=sm::p(['content'=>sm::input(['name'=>'title','min'=>$minTitle,'max'=>$maxTitle,'placeholder'=>"Название ($maxTitle)",'css'=>['width'=>'100%']])]);
				}else{
					$title=sm::p(['content'=>sm::input(['name'=>'title','max'=>$maxTitle,'placeholder'=>"Название ($maxTitle)",'css'=>['width'=>'100%']])]);
				}
			}else{
				if($minTitle>0){
					$title=sm::p(['content'=>sm::input(['name'=>'title','min'=>$minTitle,'placeholder'=>'Название','css'=>['width'=>'100%']])]);
				}else{
					$title=sm::p(['content'=>sm::input(['name'=>'title','placeholder'=>'Название','css'=>['width'=>'100%']])]);
				}
			}
			//-->Точка выбранная
			$dot=sm::input(['value'=>self::$path,'name'=>'dot','type'=>'hidden']);
			//-->Описание
			$maxDesc=constant('__XMESSAGE_DESC_MAX');
			if($maxDesc>0){
				$desc=sm::p(['content'=>sm::textarea(['name'=>'text','max'=>$maxDesc,'css'=>['width'=>'100%','resize'=>'vertical'],'rows'=>4,'placeholder'=>"Сообщение ($maxDesc)\n/bЖирность/ - Жирность\n/sЗачеркнутый текст/ - Зачеркнутый текст\n/iНаклоненные буквы/ - Наклоненные буквы"])]);
			}else{
				$desc=sm::p(['content'=>sm::textarea(['name'=>'text','css'=>['width'=>'100%','resize'=>'vertical'],'rows'=>4,'placeholder'=>"Сообщение\n/bЖирность/ - Жирность\n/sЗачеркнутый текст/ - Зачеркнутый текст\n/iНаклоненные буквы/ - Наклоненные буквы"])]);
			}
			//-->Комментарий пользователей
			$comment=sm::p(['content'=>'Создание комментарий (*)</br>'.sm::textarea(['css'=>['width'=>'100%','resize'=>'vertical'],'rows'=>4,'name'=>'comment','value'=>'*','placeholder'=>"Создание новых комментарий (Нет)\nЧтобы добавить более используйте (Enter)"])]);
			//-->Скрытый режим
			$hThread=sm::p(['content'=>sm::input(['name'=>'hThread','value'=>'Скрытый режим','type'=>'checkbox','checked'=>$_POST['hThread']])]);
			//-->Загрузчик форма
			$upload=self::getUploadedForm();
			//-->Выполнить
			$submit=sm::input(['type'=>'submit']);
			return sm::form(['id'=>$id,'action'=>$action,'enctype'=>'multipart/form-data','method'=>'post','content'=>$title.$dot.$desc.$comment.$hThread.$upload.self::getFormatObject().$submit.$file.x::generateSession()]);
		}
		$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
		return $logo.'В доступе отказано :(';
	}

	/**
     * Возвращает меню создание точки в виде элемента
     * -------------------------------
     * @return string
     */
	public function getCreateDot(){
		return sm::modal(['id'=>'dot','title'=>'Создание новой точки'.self::getVersion(),'content'=>self::getCreateDotObject()]);
	}

	/**
     * Возвращает меню создание точки в виде объекта
     * -----------------------------
     * @return object
     */
	public function getCreateDotObject(){
		$action=x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'execute'.DIRECTORY_SEPARATOR.'newDot.php');
		//-->Путь создание точки
		$path=sm::input(['name'=>'path','type'=>'hidden','value'=>self::$path]);
		//-->Имя точки
		$max=__XMESSAGE_DOT_TITLE_MAX;
		if($max>0){
			$dot=sm::p(['content'=>sm::input(['name'=>'dot','max'=>$max,'required'=>true,'placeholder'=>"Название ($max)"])]);
		}else{
			$dot=sm::p(['content'=>sm::input(['name'=>'dot','required'=>true,'placeholder'=>'Название'])]);
		}
		//-->Выполнить
		$submit=sm::input(['type'=>'submit']);
		return sm::form(['id'=>x::RedirectUpdate(),'action'=>$action,'method'=>'post','content'=>$path.$dot.$submit.x::generateSession()]);
	}

	/**
	 * Возвращаем форму удаление точки
	 * -----------------------------
	 * path-Путь выбранной точки
	 * @return string
	 */
	public function getDeleteDot($path){
		$action=x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'execute'.DIRECTORY_SEPARATOR.'delDot.php');
		//-->Описание
		$desc=sm::txt(['txt'=>'Внимание удаление точки и внутренних нитей будут навсегда удалены</br>Обратно вернуть точку будет невозможным!']);
		$dot=sm::input(['type'=>'hidden','value'=>$path,'name'=>'dot']);
		//-->Выполнить
		$submit=sm::input(['type'=>'submit']);
		return sm::modal(['title'=>'Вы точно хотите удалить точку '.sm::badge(['txt'=>$path])." ?",'content'=>sm::form(['id'=>x::RedirectUpdate(),'action'=>$action,'method'=>'post','content'=>$dot.$desc.$submit])]);
	}

	/**
	 * Возвращаем форму очистки точки
	 * -----------------------------
	 * path-Путь выбранной точки
	 * @return string
	 */
	public function getClearDot($path){
		$action=x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'execute'.DIRECTORY_SEPARATOR.'clearDot.php');
		//-->Описание
		$desc=sm::txt(['txt'=>'Внимание удаление внутренних нитей будут навсегда удалены</br>Обратно вернуть нити будет невозможным!']);
		$dot=sm::input(['type'=>'hidden','value'=>$path,'name'=>'dot']);
		//-->Выполнить
		$submit=sm::input(['type'=>'submit']);
		return sm::modal(['title'=>'Вы точно хотите Очистить точку '.sm::badge(['txt'=>$path])." ?",'content'=>sm::form(['id'=>x::RedirectUpdate(),'action'=>$action,'method'=>'post','content'=>$dot.$desc.$submit])]);
	}

	/**
	 * Возвращаем форму конфигурация xmessage
	 * -----------------------------
	 * @return string
	 */
	public function getCfgXmessage(){
		$action=x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'cfg.php');
		//-->Упорядочить нити
		$thread=[];
		$thread+=['Высокие просмотры'=>[]];
		$thread+=['Низкие просмотры'=>[]];
		$thread+=['Новые по дате'=>[]];
		$thread+=['Старые по дате'=>[]];
		$sortThread=sm::p(['content'=>sm::txt(['txt'=>'Сортировка нитей:']).sm::combobox(['name'=>'__XMESSAGE_SORT_THREAD','selected'=>$_COOKIE['__XMESSAGE_SORT_THREAD'],$thread])]);
		//-->Выполнить
		$submit=sm::input(['type'=>'submit']);
		return sm::modal(['id'=>'cfgXmessage','title'=>'Конфигурация xmessage','content'=>sm::form(['id'=>x::RedirectUpdate(),'action'=>$action,'method'=>'post','content'=>$sortThread.$submit])]);
	}

	/**
     * Возвращаем точки в виде элемента
	 * -----------------------------
	 * @return string
     */
	public function getDot(){
		$dots=self::getDotToArray();
		$data=xp::$data;
		$action=x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'execute'.DIRECTORY_SEPARATOR.'goSpace.php');
		foreach($dots as $path=>$dot){
			$ls=[];
			$item['item']=[];
			foreach(self::getDotToArray($path) as $nPath){
				array_push($ls,$nPath);
			}
			$count=' '.sm::badge(['txt'=>count($ls)]);
			$form=sm::modal(['title'=>$dot.$count,'content'=>sm::form(['action'=>$action,'content'=>sm::p(['content'=>sm::listView(['name'=>'selectedDot','required'=>true,'css'=>['width' => '100%'],$ls]).sm::input(['value'=>'Перейти','type'=>'submit'])]).sm::input(['type'=>'hidden','name'=>'dot','value'=>$path.DIRECTORY_SEPARATOR])])]);
			//Default
			$countThread=self::getCountThread($path);
			$item['item']+=['Перейти '.sm::badge(['txt'=>$countThread])=>['href'=>$path]];
			$item['item']+=[sm::ico('eye-open').' '.'Посмотреть'=>['href'=>"#$form",'modal'=>true]];
			//DEL DOT
			if($data['xmessage']['dots'][$path]['root']||$data['xmessage']['dots'][$path]['del']||$data['root']){
				$item['item']+=['Удалить'=>['href'=>'#'.self::getDeleteDot($path),'modal'=>true]];
			}
			//CLS DOT
			if($data['xmessage']['dots'][$path]['root']&&$countThread>0||$data['xmessage']['dots'][$path]['cls']&&$countThread>0||$data['root']&&$countThread>0){
				$item['item']+=['Очистить'=>['href'=>'#'.self::getClearDot($path),'modal'=>true]];
			}
			$list.=sm::dropdown([$dot.$count=>$item]);
		}
		if(!$list){
			$list='Упс ничего не нашлось :(';
			if($data['xmessage']['dots'][$path]['root']||$data['xmessage']['dots'][$path]['new']){
				$list.=sm::a(['title'=>'Создать новую точку','href'=>'#dot','modal'=>'dot']);
			}
		}
	    return sm::panel(['title'=>self::getPathSelected(true),'css'=>['text-align'=>'center'],'content'=>$list]);
		/*
		$progress = $this->getProgressBar();
		$alert = $this->getAlertError();
		$type = $xlib->getPathModules("xmessage/execute/type.php");
		$refresh = $xlib->getPathModules("xmessage/execute/refreshType.php");
		$execute = $this->generateSession($xlib->uuidv4());//Создание сессий против cURL ;)
		$timerHtml = $xlib->uuidv4();
		$timer = $xlib->uuidv4();
		//Новый тип
		$js1 = "$('#$idNew').submit(function(){ $('#getType').html('$progress');$.post('$type',$(this).serialize(),function(data){ $('#getType').html(data);$('#$Update').click();}).fail(function(){ $('#getType').html('$alert');});return false;});";
		$jquery->addLoad($js1);
		//Получение типов
		$js2 = "$('#$Update').click(function(){ $('#get').html('$progress');var arr=$(this).serializeArray();arr.push({name: 'theme', value:getThemeBootstrap()});$.post('$refresh', arr, function(data){ $('#get').html(data);}).fail(function() { $('#get').html('$alert');});return false;});$('#$Update').click();";
		$jquery->addLoad($js2);
        $content = $bootstrap->border([
			'content' => $xlib->evnform([
				'id' => $idNew,
				'content' =>
					$bootstrap->input("Название", "xmessage_typeName") .
					$bootstrap->sep([
						'modal' => true,
						'content' => $xlib->padding([
							'top' => 15,
							'content' => $bootstrap->btn([
								'modal' => true,
								'type' => 'submit',
								'title' => $bootstrap->ico('ok')
							]) . $xlib->inputhidden($_SESSION[$execute . '4'], 'token') . $xlib->inputhidden($execute, 'execute')
						])
					]) .
				$xlib->div([
					'id' => 'getType'
				])
			])
	    ]);

		$xmessageSettingsType = $bootstrap->form([
			'title' => 'Настройки xmessageType v1.0',
			'id' => $xmessageSettingsType,
			'content' => "<input type='number' id='$timer' placeholder='10' value='10' required style='width:100%;'>"
		]);
		$refresh = $bootstrap->btn([
			'title' => $bootstrap->ico('refresh') . $xlib->div(['id' => $timerHtml]),
			'id' => $Update
		]);
		$xmessageopen = $bootstrap->btn([
			'id' => $xmessageSettingsType,
			'modal' => true,
			'title' => $bootstrap->ico('cog')
		]);
		//Таймер
		$timer = "timer.Update('xmessageUpdateType', '$timer', '$Update', '$timerHtml');";
		$jquery->addLoad($timer);
		return $bootstrap->border([
			'align' => 'right',
			'stretch' => true,
			'content' => $bootstrap->btn([
				'title' => $bootstrap->ico('option-horizontal'),
				'id' => $idCollaps,
				'collaps' => '-' . $refresh . '-' . $xmessageopen . $bootstrap->collaps($xlib->margin([
					'top' => 15,
					'content' => $content
				]), $idCollaps)
			])
		]);*/	
    }

	/**
	 * Возвращаем нить
	 * -------------------------------
	 * id		-	Адрес нити
	 * count	-	Кол-во постов (Все)
	 * title	-	Загаловок
	 * -------------------------------
	 * @return string
	 */
	public function getThread($id=false,$count=NULL,$title=NULL){
		require_once __dir__.DIRECTORY_SEPARATOR.'execute'.DIRECTORY_SEPARATOR.'refresh.php';
		$refresh=new refresh();
		return $refresh->get($id,$count,$title);
	}

	/**
	 * Возвращаем Настройки постинга
	 * -------------------------------
	 */
	 public function getSettingsView(){
	 	//-->ид нити
		if($uniqid){
			$uniqid=sm::input(['type'=>'hidden','name'=>'id','value'=>$id,'placeholder'=>"ид отправителя нити ($id)"]).sm::input(['form'=>$form,'type'=>'hidden','name'=>'id','value'=>$id,'placeholder'=>"ид отправителя нити ($id)"]);
		}
		//-->NumberPost (Номер сообщение)
		if($_REQUEST['NUMBER']||$_SERVER['REQUEST_METHOD']=='POST'){
			$number=$_REQUEST['NUMBER'];
		}elseif($_COOKIE['__XMSG_NUMBER']){
			$number=$_COOKIE['__XMSG_NUMBER'];
		}else{
			$number=false;
        }
		//-->DatePost (Дата сообщение)
		if($_REQUEST['DATE']||$_SERVER['REQUEST_METHOD']=='POST'){
			$date=$_REQUEST['DATE'];
		}elseif($_COOKIE['__XMSG_DATE']){
        	$date=$_COOKIE['__XMSG_DATE'];
		}else{
			$date=false;
		}
		//-->IdMessage (Уникальный номер сообщение)
		if($_REQUEST['IDMSG']||$_SERVER['REQUEST_METHOD']=='POST'){
			$idMessage=$_REQUEST['IDMSG'];
		}elseif($_COOKIE['__XMSG_ID']){
			$idMessage=$_COOKIE['__XMSG_ID'];
		}else{
			$idMessage=false;
        }
	 	//-->Номер поста
		$NumberPost=sm::p(['content'=>sm::input(['type'=>'checkbox','name'=>'NUMBER','value'=>'Получение номера поста','checked'=>$number])]);
		//-->Дата отправки
		$date=sm::p(['content'=>sm::input(['type'=>'checkbox','name'=>'DATE','value'=>'Дата отправки','checked'=>$date])]);
		//-->Ид сообщение
		$idMessage=sm::p(['content'=>sm::input(['type'=>'checkbox','name'=>'IDMSG','value'=>'Ид сообщение','checked'=>$idMessage])]);
	 }

	/**
     * Возвращаем готовую форму с нити и форму отправки сообщение
	 * -------------------------------
	 * id		-	Адрес нити
	 * count	-	Кол-во постов (Все)
	 * title	-	Загаловок
	 * -------------------------------
	 * @return string
     */
	public function multiForm($id,$count=NULL,$title=NULL){
		$id=self::$id;
		//view++
		self::updateView($id);
		//info
		if(!x::is_uuidv4($id)){
			$info=self::getInfoThreadToArray(true)[$id];
		}else{
			$info=self::getInfoThreadToArray()[$id];
		}
		$count=$info['count'];
		$view=count(unserialize($info['view']));
		$type=$info['type'];
		$menu=self::getMenuCfgThread($info);
		//Тип
		switch($info['type']){
			case 'Студия':
				$count=$info['count'] - 1;
				$views=x::genFormatMoney($view);
				$views=sm::badge(['txt'=>$views . ' ' . x::getNumberName($views) . ' просмотр']);
				return self::getThread($id,-1,$info['title'].' '.sm::badge(['txt'=>$info['count']]) . ' ' . $views . ' ' . sm::badge(['txt'=>$type]).' | '.$menu).self::getSendBox($id).self::getThread($id,$count,'Сообщение '.sm::badge(['txt'=>$count]));
			break;
		}
		//Сообщение
		$count=self::getCountMsg($id);
		$views=x::genFormatMoney($view);
		$views=sm::badge(['txt'=>$views . ' ' . x::getNumberName($views) . ' просмотр']);
		return self::getSendBox($id).self::getThread($id,$count,'Сообщение '.sm::badge(['txt'=>$count]). ' ' . $views . ' ' . sm::badge(['txt'=>$type]).' | '.$menu);
	}

	/**
	 * Возвращаем валидная ли нить
	 * -------------------------------
	 * id		-	Адрес нити
	 * -------------------------------
	 * @return string
	 */
	public function is_thread($id){
		if(x::is_uuidv4($id)){
			$path=self::getInfoThreadToArray()[$id]['path'];
			//valid file
			if(is_file($_SERVER['DOCUMENT_ROOT'].x::getTheme()."uri$path.php")){
				return $path;
			}
		}else{//HIDDEN
			$path=self::getInfoThreadToArray(true)[$id]['path'];
			if(is_file($_SERVER['DOCUMENT_ROOT'].x::getTheme()."uri$path.php")){
				return $path;
			}
		}
		return false;
	}

	/**
	 * Возвращаем меню конфигурация нити
	 * array thread-Определенная нить
	 */
	public function getMenuCfgThread(array $info){
		$id=$info['id'];
		$path=$info['path'];
		$item['item']=[];
		$item['item']+=['Посмотреть '.sm::badge(['txt'=>self::getCountMsg($info['id'])])=>['href'=>$path]];
		if(x::isModule('xprivate',false)){
			$data=xp::$data;
			//threads
			if($data['xmessage']['threads'][$id]['edit']||$data['xmessage']['threads'][$id]['root']||$data['root']){
				$modal=self::getChangeThread($id);
				$item['item']+=['Конфигурация'=>['href'=>"#$modal",'modal'=>$modal]];
			}
			//dot selected
			$dot=substr(self::$path, 0, -1);
			if(	$data['xmessage']['dots'][$dot]['del'] ||
				$data['xmessage']['dots'][$dot]['cls'] ||
				$data['xmessage']['dots'][$dot]['root'] ||
				$data['id'] == $info['superuser'] ||
				$data['root']){
				$item['item']+=['Удалить'=>['href'=>"#delThread$id",'modal'=>"delThread$id"]];
			}
		}
		return sm::dropdown(['Меню'=>$item]);
	}

	/**
	 * Возвращаем форму изменение точек
	 * dot-Выбранная точка
	 * -------------------------------
	 */
	public function getChangeDot($dot){
		if(!is_dir($_SERVER['DOCUMENT_ROOT'].x::getTheme().'uri'.DIRECTORY_SEPARATOR.$dot)){
			$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			return sm::modal(['title'=>'Точка не найдена','content'=>$logo.'Точка не найдена :(</br>Пожалуйста повторите попытку снова']);
		}
		$action=x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'execute'.DIRECTORY_SEPARATOR.'changeDot.php');
		//account
		$ids=xp::getIdsToArray();
		//PERMS
		foreach($ids as $foo){
			$data=xp::getDataId($foo);
			if($data['xmessage']['dots'][$dot]['newThread'] == true && $markNewThread != '*'){
				$markNewThread.=$data['mark']."\n";
			}
			if($data['xmessage']['dots'][$dot]['new'] == true && $markNew != '*'){
				$markNew.=$data['mark']."\n";
			}
			if($data['xmessage']['dots'][$dot]['del'] == true && $markDel != '*'){
				$markDel.=$data['mark']."\n";
			}
			if($data['xmessage']['dots'][$dot]['cls'] == true && $markCls != '*'){
				$markCls.=$data['mark']."\n";
			}
			//ALL FORMAT
			if($data['xmessage']['dots'][$dot]['newThread'] == '*'){
				$markNewThread='*';
			}
			if($data['xmessage']['dots'][$dot]['new'] == '*'){
				$markNew='*';
			}
			if($data['xmessage']['dots'][$dot]['del'] == '*'){
				$markDel='*';
			}
			if($data['xmessage']['dots'][$dot]['cls'] == '*'){
				$markCls='*';
			}
		}
		$dot=sm::input(['type'=>'hidden','name'=>'dot','value'=>$dot]);
		$del=sm::p(['content'=>'Удаление точки (*)</br>'.sm::textarea(['css'=>['width'=>'100%','resize'=>'vertical'],'rows'=>4,'value'=>$markDel,'name'=>'del','placeholder'=>"Удаление точки (Нет)\nЧтобы добавить более используйте (Enter)"])]);
		$cls=sm::p(['value'=>$markDel,'content'=>'Очистка точки и встроенных нитей (*)</br>'.sm::textarea(['css'=>['width'=>'100%','resize'=>'vertical'],'rows'=>4,'value'=>$markCls,'name'=>'cls','placeholder'=>"Очистка точки и встроенных нитей (Нет)\nЧтобы добавить более используйте (Enter)"])]);
		$new=sm::p(['content'=>'Создание новых точек (*)</br>'.sm::textarea(['css'=>['width'=>'100%','resize'=>'vertical'],'rows'=>4,'value'=>$markNew,'name'=>'new','placeholder'=>"Создание новых точек (Нет)\nЧтобы добавить более используйте (Enter)"])]);
		$newThread=sm::p(['content'=>'Создание новых нитей (*)</br>'.sm::textarea(['css'=>['width'=>'100%','resize'=>'vertical'],'rows'=>4,'value'=>$markNewThread,'name'=>'newThread','placeholder'=>"Создание новых нитей (Нет)\nЧтобы добавить более используйте (Enter)"])]);
		//submit
		$submit=sm::input(['type'=>'submit']);
		return sm::modal(['title'=>'Изменение точки','content'=>sm::form(['id'=>x::RedirectUpdate(),'action'=>$action,'method'=>'post','content'=>$dot.$newThread.$new.$del.$cls.$submit])]);
	}
	/**
	 * Возвращаем форму изменение нитей
	 * -------------------------------
	 * dot-Выбранная точка
	 * -------------------------------
	 */
	public function getChangeThread($id){
		if(!self::is_thread($id)){
			$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
			return sm::modal(['title'=>'Нить не найдена','content'=>$logo.'Нить не найдена :(</br>Пожалуйста повторите попытку снова']);
		}
		$action=x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'execute'.DIRECTORY_SEPARATOR.'changeThread.php');
		//account
		$ids=xp::getIdsToArray();
		//COMMENT PERMS
		foreach($ids as $foo){
			$data=xp::getDataId($foo);
			if($data['xmessage']['threads'][$id]['comment']==true){
				$markComment.=$data['mark']."\n";
			}
			if($data['xmessage']['threads'][$id]['comment']==='*'){
				$markComment='*';
				break;
			}
		}
		$path=sm::input(['type'=>'hidden','name'=>'id','value'=>$id]);
		//-->Комментарий пользователей
		$comment=sm::p(['content'=>'Создание комментарий (*)</br>'.sm::textarea(['css'=>['width'=>'100%','resize'=>'vertical'],'rows'=>4,'name'=>'comment','value'=>$markComment,'placeholder'=>"Создание новых комментарий (Нет)\nЧтобы добавить более используйте (Enter)"])]);
		//-->Загрузка файлов
		$exts=self::getExtensionsForm(['thread'=>$id,'cfg'=>true,'images'=>true]);
		$max=ini_get('post_max_size');
		$count=ini_get('max_file_uploads') - 1;
		$upload=sm::panel(['title'=>'Загрузка файлов '.sm::badge(['txt'=>$count.' '.'Файлов']).' '.sm::badge(['txt'=>$max]),'content'=>$exts['content']]);
		//submit
		$submit=sm::input(['type'=>'submit']);
		return sm::modal(['id'=>"changeThread$id",'title'=>'Конфигурация нити '.sm::badge(['txt'=>$id]),'content'=>sm::form(['id'=>x::RedirectUpdate(),'action'=>$action,'method'=>'post','content'=>$path.$comment.$upload.$submit])]);
	}

	/**
	 * Возвращаем общие создание меню
	 * -------------------------------
	 * @return string
	 */
	public function getMenuCreated(){
		$data=xp::$data;
		$dot=substr(self::$path,0,-1);
		//-->Модальная форма (#thread)
		$item['item']=[];
		if($data['xmessage']['dots'][$dot]['newThread']||$data['xmessage']['dots'][$dot]['root']||$data['root']){
			$thread=self::getCreateThread();
			$item['item']+=['Новая нить'=>['href'=>"#$thread",'modal'=>$thread]];
		}
		//-->Модальная форма (#dot)
		if($data['xmessage']['dots'][$dot]['new']||$data['xmessage']['dots'][$dot]['root']||$data['root']){
			$dot=self::getCreateDot();
			$item['item']+=['Новая точка'=>['href'=>"#$dot",'modal'=>$dot]];
		}
		if(!empty($item['item'])){
			return sm::dropdown([sm::ico('comment').' '.'Общение'=>$item]);
		}
		return false;
	}

	/**
	 * Возвращаем форму поддержка форматов файлов
	 * ----------------------------------
	 * @return string
	 */
	public function getExtensionsForm($E){
		$i=0;
		$thread=$E['thread'];//Выбранная нить
		if($E['cfg']){
			$data=xp::$data;
			$extsArr=explode(',',$data['xmessage']['threads'][$thread]['exts']);
			if($E['images']){
				$i++;
				foreach($extsArr as $ext){
					switch($ext){
						case 'image/jpg':
							$UPLOAD_EXT_imagejpg=true;
							$format.='image/jpg,';
						break;
						case 'image/jpeg':
							$UPLOAD_EXT_imagejpeg=true;
							$format.='image/jpeg,';
						break;
						case 'image/png':
							$UPLOAD_EXT_imagepng=true;
							$format.='image/png,';
						break;
						case 'image/gif':
							$UPLOAD_EXT_imagegif=true;
							$format.='image/gif,';
						break;
						case 'image/webp':
							$UPLOAD_EXT_imagewebp=true;
							$format.='image/webp,';
						break;
					}
					if($UPLOAD_EXT_imagejpg&&$UPLOAD_EXT_imagejpeg&&$UPLOAD_EXT_imagepng&&$UPLOAD_EXT_imagegif&&$UPLOAD_EXT_imagewebp){
						$UPLOAD_EXT_ALLIMAGES=true;
					}
				}
				$exts.=sm::input(['name'=>'UPLOAD_EXT_image/jpg','value'=>'image/jpg','type'=>'checkbox','checked'=>$UPLOAD_EXT_imagejpg]).'|';
				$exts.=sm::input(['name'=>'UPLOAD_EXT_image/jpeg','value'=>'image/jpeg','type'=>'checkbox','checked'=>$UPLOAD_EXT_imagejpeg]).'|';
				$exts.=sm::input(['name'=>'UPLOAD_EXT_image/png','value'=>'image/png','type'=>'checkbox','checked'=>$UPLOAD_EXT_imagepng]).'|';
				$exts.=sm::input(['name'=>'UPLOAD_EXT_image/gif','value'=>'image/gif','type'=>'checkbox','checked'=>$UPLOAD_EXT_imagegif]).'|';
				$exts.=sm::input(['name'=>'UPLOAD_EXT_image/webp','value'=>'image/webp','type'=>'checkbox','checked'=>$UPLOAD_EXT_imagewebp]);
				$images=sm::panel(['title'=>sm::input(['name'=>'UPLOAD_EXT_ALLIMAGES','value'=>'Картинки (ALL)','type'=>'checkbox','checked'=>$UPLOAD_EXT_ALLIMAGES]),'stretch'=>false,'content'=>$exts]);
			}
			if($i>0){
				return ['content'=>sm::panel(['title'=>'Поддержка форматов','content'=>$images]),'ext'=>substr($format,0,-1)];
			}
		}if(!$thread){
			//-->Картинки
			if($E['images']){
				$i++;
				$exts.=sm::input(['name'=>'UPLOAD_EXT_image/jpg','value'=>'image/jpg','type'=>'checkbox','checked'=>$_POST['UPLOAD_EXT_image/jpg']]).'|';
				$ext.='image/jpg,';
				$exts.=sm::input(['name'=>'UPLOAD_EXT_image/jpeg','value'=>'image/jpeg','type'=>'checkbox','checked'=>$_POST['UPLOAD_EXT_image/jpeg']]).'|';
				$ext.='image/jpeg,';
				$exts.=sm::input(['name'=>'UPLOAD_EXT_image/png','value'=>'image/png','type'=>'checkbox','checked'=>$_POST['UPLOAD_EXT_image/png']]).'|';
				$ext.='image/png,';
				$exts.=sm::input(['name'=>'UPLOAD_EXT_image/gif','value'=>'image/gif','type'=>'checkbox','checked'=>$_POST['UPLOAD_EXT_image/gif']]).'|';
				$ext.='image/gif,';
				$exts.=sm::input(['name'=>'UPLOAD_EXT_image/webp','value'=>'image/webp','type'=>'checkbox','checked'=>$_POST['UPLOAD_EXT_image/webp']]);
				$ext.='image/webp,';
				$images=sm::panel(['title'=>sm::input(['name'=>'UPLOAD_EXT_ALLIMAGES','value'=>'Картинки (ALL)','type'=>'checkbox','checked'=>$_POST['UPLOAD_EXT_ALLIMAGES']]),'stretch'=>false,'content'=>$exts]);
			}
			if($i>0){
				return ['content'=>sm::panel(['title'=>'Поддержка форматов','content'=>$images]),'ext'=>substr($ext,0,-1)];
			}
		}else{
			$data=xp::$data;
			$extsArr=explode(',',$data['xmessage']['threads'][self::$id]['exts']);
			//-->Картинки
			if($E['images']){
				foreach($extsArr as $ext){
					if(!empty($ext)){
						$i++;
					}
					switch($ext){
						case 'image/jpg':
							$UPLOAD_EXT_imagejpg=true;
							$format.='image/jpg,';
						break;
						case 'image/jpeg':
							$UPLOAD_EXT_imagejpeg=true;
							$format.='image/jpeg,';
						break;
						case 'image/png':
							$UPLOAD_EXT_imagepng=true;
							$format.='image/png,';
						break;
						case 'image/gif':
							$UPLOAD_EXT_imagegif=true;
							$format.='image/gif,';
						break;
						case 'image/webp':
							$UPLOAD_EXT_imagewebp=true;
							$format.='image/webp,';
						break;
					}
					if($UPLOAD_EXT_imagejpg&&$UPLOAD_EXT_imagejpeg&&$UPLOAD_EXT_imagepng&&$UPLOAD_EXT_imagegif&&$UPLOAD_EXT_imagewebp){
						$UPLOAD_EXT_ALLIMAGES=true;
					}
				}
				//exts
				$format=substr($format,0,-1);
				$exts.=sm::input(['enabled'=>false,'value'=>'image/jpg','type'=>'checkbox','checked'=>$UPLOAD_EXT_imagejpg]);
				$exts.=sm::input(['enabled'=>false,'value'=>'image/jpeg','type'=>'checkbox','checked'=>$UPLOAD_EXT_imagejpeg]);
				$exts.=sm::input(['enabled'=>false,'value'=>'image/png','type'=>'checkbox','checked'=>$UPLOAD_EXT_imagepng]);
				$exts.=sm::input(['enabled'=>false,'value'=>'image/gif','type'=>'checkbox','checked'=>$UPLOAD_EXT_imagegif]);
				$exts.=sm::input(['enabled'=>false,'value'=>'image/webp','type'=>'checkbox','checked'=>$UPLOAD_EXT_imagewebp]);
				$images=sm::panel(['title'=>sm::input(['enabled'=>false,'value'=>'Картинки (ALL)','type'=>'checkbox','checked'=>$UPLOAD_EXT_ALLIMAGES]),'stretch'=>false,'content'=>$exts]);
			}
			if($i>0){
				return ['content'=>sm::panel(['title'=>'Поддержка форматов','content'=>$images]),'ext'=>$format];
			}
		}
		return false;
	}

	/**
	 * Возвращаем валидацию расширение (Файлов)
	 * ----------------------------------
	 * @return string
	 */
	public function is_Extension($ext){
		$thread=self::$id;
		if(!$thread){
			$exts=explode(',',self::getExtensionsCompile());
		}else{
			$data=xp::$data;
			$exts=explode(',',$data['xmessage']['threads'][$thread]['exts']);
		}
		foreach($exts as $e){
			if($e==$ext){
				return true;
			}
		}
		return false;
	}

	/**
	 * unset Extensions Compile
	 */
	public function UnsetExtensionsCompile(){
		unset($_POST['UPLOAD_EXT_image/jpg']);
		unset($_POST['UPLOAD_EXT_image/jpeg']);
		unset($_POST['UPLOAD_EXT_image/png']);
		unset($_POST['UPLOAD_EXT_image/gif']);
		unset($_POST['UPLOAD_EXT_image/webp']);
		unset($_POST['UPLOAD_EXT_ALLIMAGES']);
	}

	/**
	 * Возвращаем компилируемые расширение
	 * @return string
	 */
	public function getExtensionsCompile(){
		$exts=[];
		//images
		if($_POST['UPLOAD_EXT_ALLIMAGES']){
			array_push($exts,'image/jpg');
			array_push($exts,'image/jpeg');
			array_push($exts,'image/png');
			array_push($exts,'image/gif');
			array_push($exts,'image/webp');
		}else{
			if($_POST['UPLOAD_EXT_image/jpg']){
				array_push($exts,'image/jpg');
			}
			if($_POST['UPLOAD_EXT_image/jpeg']){
				array_push($exts,'image/jpeg');
			}
			if($_POST['UPLOAD_EXT_image/png']){
				array_push($exts,'image/png');
			}
			if($_POST['UPLOAD_EXT_image/gif']){
				array_push($exts,'image/gif');
			}
			if($_POST['UPLOAD_EXT_image/webp']){
				array_push($exts,'image/webp');
			}
		}
		$i=0;
		foreach($exts as $ext){
			$i++;
			$str.=$ext;
			if(count($exts)!=$i){
				$str.=',';
			}
		}
		if(!empty($str)){
			return $str;
		}
		return false;
	}

	/**
	 * Возвращаем форму загрузки файлов
	 * thread-Использовать только для нити
	 * @return string
	 */
	public function getUploadedForm($thread=false){
		//Дополнение (Расширение)
		$exts=self::getExtensionsForm(['thread'=>$thread,'images'=>true]);//Поддержка форматов
		//Загрузчик файлов
		if($exts||xp::$data['xmessage']['threads'][self::$id]['root']||xp::$data['root']){
			$file=sm::input(['name'=>'upload[]','type'=>'file','multiple'=>true,'accept'=>$exts['ext']]);
			$max=ini_get('post_max_size');
			$count=ini_get('max_file_uploads') - 1;
			return sm::panel(['title'=>'Загрузка файлов '.sm::badge(['txt'=>$count.' '.'Файлов']).' '.sm::badge(['txt'=>$max]),'content'=>$exts['content'].$file]);
		}
		return false;
	}

	/**
	 * Возвращаем ид выбранной нити
	 * @return string
	 */
	public function getSelectedThread(){
		//search
		if(!$_POST['__XMESSAGE_THREAD']){
			$id=basename(str_replace('?'.x::getData(),NULL,x::geturi()));
		}else{
			$id=$_POST['__XMESSAGE_THREAD'];
		}
		if(self::is_thread($id)){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				$_POST['__XMESSAGE_THREAD']=$id;
			}
			return $id;
		}
		return false;
	}

    /**
     * Возвращаем мульти-форму отправки постов
	 * ----------------------------------------
	 * id	-	ид отправки в нить
	 * ----------------------------------------
	 * @return string
     */
	public function getSendBox($id){
		$data=xp::$data;
		if($data['xmessage']['threads'][$id]['comment']||$data['xmessage']['threads'][$id]['root']||$data['root']){
			$action=x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'execute'.DIRECTORY_SEPARATOR.'post.php');
			//-->ид нити
			if($id){
				$id=sm::p(['content'=>sm::input(['name'=>'__XMESSAGE_THREAD','type'=>'hidden','value'=>str_replace('?'.x::getData(),NULL,$id),'placeholder'=>"ид отправителя нити ($id)"])]);
			}
			//-->Описание
			$maxDesc=constant('__XMESSAGE_DESC_MAX');
			if($maxDesc>0){
				$desc=sm::p(['content'=>sm::textarea(['name'=>'text','value'=>$_POST['text'],'max'=>$maxDesc,'css'=>['width'=>'100%','resize'=>'vertical'],'rows'=>4,'placeholder'=>"Сообщение ($maxDesc)\n/bЖирность/ - Жирность\n/sЗачеркнутый текст/ - Зачеркнутый текст\n/iНаклоненные буквы/ - Наклоненные буквы"])]);
			}else{
				$desc=sm::p(['content'=>sm::textarea(['name'=>'text','value'=>$_POST['text'],'css'=>['width'=>'100%','resize'=>'vertical'],'rows'=>4,'placeholder'=>"Сообщение\n/bЖирность/ - Жирность\n/sЗачеркнутый текст/ - Зачеркнутый текст\n/iНаклоненные буквы/ - Наклоненные буквы"])]);
			}
			//xmotion (Умные эмоций)
			$TmojaCount=0;
			$ImojaCount=0;
			if(moja::getCountConnectedImg()>0){//IMG
				$TmojaCount++;
				foreach($data['xmotion']['img'] as $name=>$package){
					//compile load...
					if(count($package['load'])>0){
						$ImojaCount++;
						unset($mojas);
						//mojas
						foreach($package['load'] as $moja){
							$mojas.=moja::getBoxMojaImg($name,$moja);
						}
						//menu
						$about=xmotion::getFormAboutImg($name,false);//Об пакете
						$menu=sm::dropdown(['Меню'=>['item'=>
								['Об пакете'=>['href'=>"#$about",'modal'=>$about]]
							]
						]);
						$mojaImgs.=sm::panel(['title'=>$name.' '.sm::badge(['txt'=>count($data['xmotion']['img'][$name]['load'])]).' | '.$menu,'content'=>$mojas]);
					}
				}
				//list compile...
				if($mojaImgs){
					$mojaImgs=sm::panel(['title'=>'Стандартные '.sm::badge(['txt'=>$ImojaCount]),'content'=>$mojaImgs]);
				}
			}
			//if(moja::getCountConnectedAnimate()>0){//img
				
			//}
			if($mojaImgs){
				$xmotion=sm::panel(['title'=>'xmotion (Умные эмоций) '.sm::badge(['txt'=>$TmojaCount]),'content'=>$mojaImgs]);
			}
			//-->Отправить файлы
			$upload=self::getUploadedForm(true);
			//-->Выполнить (Отправить)
			$submit=sm::input(['type'=>'submit']);
			//-->Модальная форма (#donate)
			sm::modal([
				'id'=>'syntax',
				'title'=>'ЧИВО',
				'content'=>"Ссылки использование их<br><hr><br>Ютубище - https://youtu.be/mo6APOpfS3U -> Отоброжается как видео<br>Расширение картинок - .jpeg, .jpg, .png, .gif -> Отоброжается как картинки"
			]);
			//-->Открытие формы о Syntax (#syntax)
			$syntax=sm::a(['title'=>'ЧИВО','href'=>'#syntax','modal'=>'syntax']);
			//-->Syntax (Возможности упрощенного)
			//$b=$skinmanager->btn(['type'=>'submit','title'=>'Жирный']);
			//$s=$skinmanager->btn(['type'=>'submit','title'=>'Зачеркнутый']);
			//$i=$skinmanager->btn(['type'=>'submit','title'=>'Курсив']);
			return sm::panel(['title'=>'Новое сообщение','content'=>$syntax.sm::form(['method'=>'post','enctype'=>'multipart/form-data','action'=>$action,'id'=>x::RedirectUpdate(),'content'=>$id.$desc.$xmotion.$upload.$submit.$b.$s.$i.x::generateSession()])]);
		}
		return sm::panel(['title'=>'Ответить на сообщение','content'=>sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]).'В доступе отказано :(']);
		/*
        $bootstrap = new bootstrap();
		$progress = $this->getProgressBar();
		$alert = $this->getAlertError();
		$execute = $this->generateSession($xlib->uuidv4());//Создание сессий против cURL ;)
		$refresh = $xlib->getPathModules("xmessage/execute/refresh.php");
		$post = $xlib->getPathModules("xmessage/execute/post.php");
		$Update = $xlib->uuidv4();
		$timerHtml = $xlib->uuidv4();
		//Форма отправки
		$js = "$('#$Update').submit(function(event){event.preventDefault();var arr=$(this).serializeArray();$('#loadingcontent').html('$progress');arr.push({name: 'post_index', value:$('#post_index').val()});arr.push({name: 'theme', value:getThemeBootstrap()});$.post('$refresh', arr, function(data){ $('#response').html(data);youtube.Update();$('#loadingcontent').empty();}).fail(function(){ $('#response').html('$alert');});});$('#$Update').submit();$('#post').submit(function(event){ event.preventDefault();$('#loadingcontent').html('$progress');var arr = $(this).serializeArray();var y = youtube.getEmbedStr(arr[2]['value']);arr.push({name: 'vs', value:y});arr.push({name: 'theme', value:getThemeBootstrap()});$('#response').empty();$.post('$post', arr, function(data){ $('#response').html(data);$('#loadingcontent').empty();youtube.Update();$(\"#posttext\").val(null);}).fail(function(){ $('#response').html('$alert');});});";
		$jquery->addLoad($js);
		//$xlib->js("$(document).ready(function(){ $('#$Update').submit(function(event){event.preventDefault();var arr=$(this).serializeArray();$('#loadingcontent').html('$progress');arr.push({name: 'post_index', value:$('#post_index').val()});arr.push({name: 'theme', value:getThemeBootstrap()});$.post('$refresh', arr, function(data){ $('#response').html(data);youtube.Update();$('#loadingcontent').empty();}).fail(function(){ $('#response').html('$alert');});});$('#$Update').submit();$('#post').submit(function(event){ event.preventDefault();$('#loadingcontent').html('$progress');var arr = $(this).serializeArray();var y = youtube.getEmbedStr(arr[2]['value']);arr.push({name: 'vs', value:y});arr.push({name: 'theme', value:getThemeBootstrap()});$('#response').empty();$.post('$post', arr, function(data){ $('#response').html(data);$('#loadingcontent').empty();youtube.Update();$(\"#posttext\").val(null);}).fail(function(){ $('#response').html('$alert');});});});");
        $formsendpost = $xlib->padding([
        	'top' => 15,
        	'content' => $xlib->evnform([
        		'id' => 'post',
        		'content' => $bootstrap->border([
        			'align' => 'left',
        			'content' =>
		        		$bootstrap->input('ид отправителя треда', 'post_index', $xlib->geturi(3)) .
		        		$bootstrap->input("Имя создателя поста (Неизвестный)", 'name') .
		        		$bootstrap->textarea("Описание (текст) (используйте знак => \"Пробел\" чтобы добавить более одного файла)", 'posttext') .
		        		$bootstrap->btn([
		        			'title' => $bootstrap->ico('ok'),
		        			'type' => 'submit'
	        			]) . $xlib->inputhidden($_SESSION[$execute . '4'], 'token') . $xlib->inputhidden($execute, 'execute')
        		])
        	])
        ]);
		$timer = $xlib->uuidv4();
		/**
		 * форма настройки поста ;)
		 *
		$Settings = $bootstrap->form([
			'title' => 'Настройки xmessageSend v1.0',
			'id' => $Settings,
			'content' => "<input type='number' id='$timer' placeholder='10' value='10' required style='width:100%;'>"
		]);
		//Таймер
		$timer = "timer.Update('xmessageUpdatePost', '$timer', '$Update', '$timerHtml', 'submit');";
		$jquery->addLoad($timer);
     	return $bootstrap->border([
     		'align' => 'left',
     		'content' => $xlib->div(['id' => 'loadingcontent']) . $xlib->evnform([
				'id' => $Update,
				'content' =>
			 		$bootstrap->btn([
			 			'id' => 'postform',
				 		'title' => $bootstrap->ico('option-horizontal'),
						'collaps' => '-'
			 		]) .
					$bootstrap->btn([
						'type' => 'submit', 
				 		'title' => $bootstrap->ico('refresh') . $xlib->div(['id' => $timerHtml])
			 		]) . '-' . $bootstrap->btn([
			 			'id' => $Settings,
			 			'modal' => true,
				 		'title' => $bootstrap->ico('cog')
			 		])
				]) . $bootstrap->collaps($formsendpost, 'postform')
	     	]) . $xlib->div(['id' => 'response']);
	     	*/
	}

	/**
	 * Обновить кол-во просмотров
	 */
	public function updateView($id){
		$count="LIMIT 1";
		$sql=self::getmysql();
		//view
		$view=unserialize(mysqli_fetch_array(mysqli_query($sql,"SELECT * FROM `$id` ORDER BY `id` ASC $count"))['view']);
		foreach($view as $v){
			if($v==xp::$data['id']){
				mysqli_close($sql);
				return false;
			}
		}
		if(!$view){
			$view=[];
		}
		array_push($view,xp::$data['id']);
		$view=serialize($view);
		mysqli_query($sql,"UPDATE `$id` SET `view` = '$view' WHERE `$id`.`id`=1");
		mysqli_close($sql);
	}

}
