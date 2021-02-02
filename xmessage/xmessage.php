<?php

/**
 * Отправка сообщение (Создание своего форума)
 * ---------------------------------------------
 * ver 1.45
 */
use xlib as x;
use skinmanager as sm;
use jquery as jq;
use xprivate as xp;
class xmessage{

	protected $pathSelected;

	/**
     * Возвращаем версию
	 * -----------------
	 * @return string
	 */
	public function getVersion () {
		return' ('.__CLASS__.' '.sm::badge(['txt'=>'1.65']).')';
	}
	/**
	 * Возвращаем путь загрузки файлов
	 * id - нить
	 */
	public function getUploadFile($id){
		if(x::is_uuidv4($id)){
			$id=self::getPathSelected().$id;
		}
		mkdir($_SERVER['DOCUMENT_ROOT']."/tmp$id/",0777,true);
		return "/tmp$id/";
	}
    /**
     * Удаление сломанных файлов не до конца которые загрузились
     */
     public function ClearFileThreadBAD($id){
     	$path='/tmp'.self::getPathSelected().$id;
     	$DIRS=scandir($path);
     	$DRX=[];
     	$BAD=false;
     	foreach($DIRS as $DIR){
     		$DRX[$DIR]=false;
     	}
        $result=mysqli_query(x::getmysql(),"SELECT * FROM `$id` ORDER BY `id`");
     	while($R=mysqli_fetch_array($result)){
     		$files=unserialize($R['img']);
     		foreach($files as $file){
     			$file=$file;
     			$DRX[$file]=true;
     		}
     	}
     	foreach($DRX as $FILE=>$DELETED){
     		if($FILE&&!$DELETED&&$FILE!='.'&&$FILE!='..'){
     			$BAD.='|'.$FILE;
     			unlink($_SERVER['DOCUMENT_ROOT']."/tmp".self::getPathSelected()."$id/$FILE");
            }
     	}
     	return $BAD;
     }
	/**
     * Возвращает кол-во Сообщение в нити
	 * id - нить
     */
	public function getCountMsg($id){
		$result=mysqli_query(x::getmysql(),"SELECT COUNT(*) FROM `$id`");
		return mysqli_fetch_array($result)[0];
	}
	/**
	 * Возвращаем все нити в виде массива
	 * return array
	 */
	public function getThreadsToArray(){
		$out=[];
		$dot=self::getPathSelected();
		if($dot==self::getROOT()||!$dot){
			foreach(new RecursiveTreeIterator(new RecursiveDirectoryIterator($_SERVER['DOCUMENT_ROOT'].x::getTheme().'uri'.self::getROOT(), RecursiveDirectoryIterator::SKIP_DOTS)) as $path){
				foreach(explode('/',$path) as $p){
					if($p==explode('/',self::getROOT())[1]){
						$name=basename($path);
						$file=explode('.',$name);
						if($file[1]=='php'&&x::is_uuidv4($file[0])){
							$DIR=explode('.',self::getROOT().explode(self::getROOT(),$path)[1])[0];
							$out[$file[0]]=$DIR;
						}
					}
				}
			}
		}else{
			$path=$_SERVER['DOCUMENT_ROOT'].x::getTheme().'uri'.self::getPathSelected();
			$dir=scandir($path);
			array_shift($dir);
			array_shift($dir);
			foreach($dir as $dr){
				$ext=explode('.', $dr);
				if(x::is_uuidv4($ext[0])){
					$out[$ext[0]]=self::getPathSelected().'/'.$ext[0];
				}
			}
		}
		self::autoclear($out);
		return $out;
	}
	/**
	 * Очистка файлов не созданных в нити
	 */
	public function autoclear($threads){
		foreach(new RecursiveTreeIterator(new RecursiveDirectoryIterator($_SERVER['DOCUMENT_ROOT'].'/tmp', RecursiveDirectoryIterator::SKIP_DOTS)) as $path){
			$name=new SplFileInfo(dirname($path));
			$name=$name->getFilename();
			if(!$threads[$name]&&x::is_uuidv4($name)){
				$path=explode('-',dirname($path),2)[1];
				array_map('unlink',array_filter((array)array_merge(glob("$path/*"))));
				rmdir($path);
			}
		}
	}
	/**
	 * Возвращаем все нити в виде объекта
	 * ----------------------------------
	 * @return object
	 */
	public function getThreadsToObject(){
		$arr=[];
		foreach(self::getThreadsToArray() as $id=>$thread){
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
		$desc=sm::text(['text'=>'Внимание удаление нити будет навсегда</br>Обратно вернуть нить будет невозможным!']);
		//-->Выполнить
		$submit=sm::input(['type'=>'submit']);
		sm::modal(['id'=>"delThread$id",'title'=>"Вы точно хотите удалить нить ".sm::badge(['txt'=>$id])." ?",
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
	 * $path-путь
	 * @return Array
     */
    public function getDotToArray($PATH=false){
        $PATH=str_replace('?'.$_SERVER['QUERY_STRING'],NULL,$PATH);
		$ROOT=explode('/',self::getROOT())[1];
		$ARR=[];
		if($_POST){
			$DIRS=scandir($_SERVER['DOCUMENT_ROOT'].x::getTheme().'uri/'.$PATH);
			array_shift($DIRS);
			array_shift($DIRS);
			foreach($DIRS as $DIR){
				if(!explode('.',$DIR)[1]){
					$ARR[$DIR]=$_SERVER['DOCUMENT_ROOT'].x::getTheme().'uri/'.$ROOT;
				}
			}
			return $ARR;
		}
		if(explode('/',$PATH)[1]!=$ROOT){
			$PATH=self::getROOT();
			$DIRS=scandir('.'.x::getTheme().'uri'.$PATH);
			$R=[];
			array_shift($DIRS);
			array_shift($DIRS);
			foreach($DIRS as $DIR){
				if(!explode('.',$DIR)[1]){
					array_push($R,$DIR[0]);
				}
			}
			if(!count($R)){
				return false;
			}
		}
		$DIRS=scandir('.'.x::getTheme().'uri'.$PATH);
		array_shift($DIRS);
		array_shift($DIRS);
		foreach($DIRS as $DIR){
			if(!explode('.',$DIR)[1]){
				$ARR[$DIR]=$PATH;
			}
		}
		if(is_dir('.'.x::getTheme().'uri'.$PATH)){
			if(!$ARR){
				$p=explode('/',$PATH);
				$path=str_replace($p[count($p) - 1],NULL,$PATH);
				return self::getDotToArray(mb_substr($PATH, 0, -1));
			}else{
				$GLOBALS['p']=$PATH;
			}
		}
		if(!$GLOBALS['p']){
			$GLOBALS['p']=$PATH;
		}
		return $ARR;
    }
	/**
	 * Возвращаем название ROOT
	 */
	public function getROOT(){
		return DIRECTORY_SEPARATOR.'о'.DIRECTORY_SEPARATOR;
	}
	/**
	 * Возвращаем выбранный путь точки
	 */
	protected function getPathSelectedLegacy(){
		$DIRS=str_replace('?'.x::getData(),NULL,$_SERVER['REQUEST_URI']);
		$ROOT=explode('/',self::getROOT())[1];
		if($ROOT==explode('/',$DIRS)[1]){//ISROOT
			if(is_dir('.'.x::getTheme().'uri'.x::geturi())){
				return x::geturi();
			}else{
				$b=explode('/',$DIRS);
				/*if(!$GLOBALS['p']){
					$b=explode('/', $DIRS);
				}else{
					$b=explode('/', $GLOBALS['p']);
				}
				*/
				$t=null;
				foreach($b as $c){
					if(!x::is_uuidv4($c)){
						$t.=$c.'/';
					}
				}
				if(x::endsWith($t,DIRECTORY_SEPARATOR)){
					$t=substr($t,0,-1);
				}
				return $t;
			}
		}else{
			return self::getROOT();
		}
	}
	/**
	 * Возвращаем выбранный путь точки (Исправленный)
	 */
	public function getPathSelected(){
		$DOTS=[];
		$DOT='';
		$PATH=self::getPathSelectedLegacy();
		foreach(explode(DIRECTORY_SEPARATOR,$PATH.DIRECTORY_SEPARATOR) as $DOT){
			if($DOT!='.'&&$DOT!='..'&&trim($DOT)){
				array_push($DOTS,$DOT);
			}
		}
		foreach($DOTS as $D){
			$DOT.=DIRECTORY_SEPARATOR.$D;
		}
		$DOT.=DIRECTORY_SEPARATOR;
		return $DOT;
	}
	/**
	 * Возвращаем выбранна ли точка
	 */
	public function isDot(){
		if(self::getPathSelected()==self::getROOT()&&!$_POST['dot']||$_POST['dot']==self::getROOT()){
			return false;
		}else{
			return true;
		}
	}
	/**
	 * Возвращаем кол-во точек
	 * -------------------------------
	 * @return int
	 */
	public function getCountDot(){
		$DIRS=scandir($_SERVER['DOCUMENT_ROOT'].x::getTheme().'uri'.self::getPathSelected());
		$out=[];
		array_shift($DIRS);
		array_shift($DIRS);
		foreach($DIRS as $DIR){
			if(!explode('.',$DIR)[1]){
				array_push($out, $DIR);
			}
		}
		return count($out);
	}
	/**
	 * Возвращаем Информацию об нити
	 * -----------------------------
	 * @return array
	 */
	public function getInfoThreadToArray(){
		$arr=[];
		$sql=x::getmysql();
		foreach(self::getThreadsToArray() as $id=>$thread){
			$result=mysqli_query($sql,"SELECT * FROM `view`");
		 	while($R=mysqli_fetch_array($result)){
		 		if($R['uuid']==$id){
		 			$title=$R['title'];
		 			$name=$R['name'];
		 			$result=mysqli_query($sql,"SELECT * FROM `$id` LIMIT 1");
		 			$result=mysqli_fetch_array($result);
		 			$time=explode('(',$result[3])[0];
		 			$arr+=[$id=>['path'=>$thread,'name'=>$name,'time'=>$time,'title'=>$title,'superuser'=>$result[4]]];
		 			$id=false;
		 		}
		 	}
		 	if(isset($id)){
		 		$result=mysqli_query($sql,"SELECT * FROM `$id` LIMIT 1");
		 		$result=mysqli_fetch_array($result);
				$time=explode('(',$result[3])[0];
		 		$arr+=[$id=>['path'=>$thread,'title'=>$id,'time'=>$time,'superuser'=>$result[4]]];
			}
		}
		return $arr;
	}
	/**
     * Возвращаем форму (Создание нити)
	 * -------------------------------
	 * @return string
     */
	public function getCreateThread(){
		return sm::modal(['id'=>'thread','title'=>'Создание новой нити'.self::getVersion(),'content'=>self::getCreateThreadObject()]);
	}
	/**
     * Возвращаем форму (Создание нити)
	 * -------------------------------
	 * @return string
     */
	public function getCreateThreadObject(){
        $action=x::getPathModules("xmessage/execute/newThread.php");
		//-->Название нити
		$title=sm::p(['content'=>sm::input(['name'=>'title','placeholder'=>'Название нити:'])]);
		//-->Точка выбранная
		$dot=sm::input(['value'=>self::getPathSelected(),'name'=>'dot','type'=>'hidden']);
		//-->Описание
		$txt=sm::p(['content'=>sm::textarea(['name'=>'text','css'=>['width'=>'100%','resize'=>'vertical'],'rows'=>4,'placeholder'=>"Сообщение (8096)\n/bЖирность/ - Жирность\n/sЗачеркнутый текст/ - Зачеркнутый текст\n/iНаклоненные буквы/ - Наклоненные буквы"])]);
		//-->Отправить файлы
		$file=sm::input(['name'=>'upload[]','type'=>'file','multiple'=>1,'accept'=>'image/jpg,image/jpeg,image/png,image/gif']);
		//-->Выполнить
		$submit=sm::input(['type'=>'submit']);
		$key=x::generateSession(x::uuidv4());
		return sm::form(['id'=>x::RedirectUpdate(),'action'=>$action,'enctype'=>'multipart/form-data','method'=>'post','content'=>$title.$dot.$txt.$submit.$file.$key]);
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
     * -------------------------------
     * @return object
     */
	public function getCreateDotObject(){
		$action=x::getPathModules("xmessage/execute/newDot.php");
		$key=x::generateSession(x::uuidv4());
		//-->Путь создание точки
		$path=sm::input(['name'=>'path','type'=>'hidden','value'=>self::getPathSelected()]);
		//-->Имя точки
		$dot=sm::p(['content'=>sm::input(['name'=>'dot','placeholder'=>'Название точки:'])]);
		//-->Выполнить
		$submit=sm::input(['type'=>'submit']);
		return sm::form(['id'=>x::RedirectUpdate(),'action'=>$action,'method'=>'post','content'=>$key.$path.$dot.$submit]);
	}
	/**
	 * Возвращаем выбранную точку
	 * -------------------------------
	 * @return string
	 */
	public function getDotSelected(){
		return x::geturi();
	}
	/**
     * Возвращаем точки в виде элемента
	 * ---------------------------------
	 * @return string
     */
	public function getDot(){
		$idNew=x::uuidv4();
		$idCollaps=x::uuidv4();
		$Update=x::uuidv4();
		$Dot=self::getPathSelected();
		foreach(self::getDotToArray($Dot) as $dot=>$key){
			$item=[];
			$path=$Dot.$dot;//Путь
			$newSpace=x::uuidv4();
			$btnSpace=sm::input(['type' => 'hidden','name'=>'dot','form'=>$newSpace,'value'=>$dot]) .
			sm::btn(['title'=>'+','modal'=>'newPoint']);
			foreach (array_keys(self::getDotToArray($path)) as $newDot){
				array_push($item,$newDot);
			}
			$count=' '.sm::badge(['txt'=>count(self::getDotToArray($path))]);
			$listview=x::uuidv4();
			$id=sm::modal([
							'title'=>"$dot$count",
							'css'=>['text-align'=>'right'],
							'content'=>$btnSpace.sm::p([
										'content'=>sm::listView([
											'form'=>$listview,
											'name'=>'selectedDot',
											'required'=>true,
											'css'=>['width' => '100%'],
											$item
									])
								]).sm::input(['type'=>'hidden','name'=>'dot','form'=>$listview,'value'=>"$Dot/$dot/"])
								.sm::form([
									'action'=>"/theme/borda/linux/plugins/xmessage/execute/goSpace.php",
									'id'=>$listview,
									'content'=>sm::input([
												'value'=>'Перейти',
												'type'=>'submit'
											])
										])
							]);
		$list.=sm::dropdown([
								$dot.$count=>[
									'item'=>[
										'Перейти'=>['href'=>$Dot.$dot],
										'Создать'=>['href'=>'#newPoint','modal'=>true],
										sm::ico('eye-open').' '.'Посмотреть'=>['href'=>"#$id",'modal'=>true]
									]
								]
							]);
		}
		if(!$list){
			$list="Упс ничего не нашлось :(".sm::a(['title'=>'Создать новую точку','href'=>'#dot','modal'=>'dot']);
		}
	    return sm::panel(['title'=>$Dot.' '.sm::badge(['txt'=>self::getCountDot()]),'css'=>['text-align'=>'center'],'content'=>$list]);
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
		require_once __dir__.DIRECTORY_SEPARATOR.'execute'.DIRECTORY_SEPARATOR. 'refresh.php';
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
     * Возвращаем готовую форму с нити и форму отправки постов
	 * -------------------------------
	 * id		-	Адрес нити
	 * count	-	Кол-во постов (Все)
	 * title	-	Загаловок
	 * -------------------------------
	 * @return string
     */
	public function multiForm($id,$count=NULL,$title=NULL){
		if($_POST['id']){
			$id=$_POST['id'];
		}elseif(!$id){
			$uri=x::geturi();
			$uri=str_replace('?'.x::getData(),NULL,$uri);
			$id=explode('/',$uri)[count(explode('/',$uri)) - 1];
		}
		$info=self::getInfoThreadToArray()[$id];
		$count=self::getCountMsg($id)-1;
		if($info['superuser']==xp::getData()['id']){
			$menu=sm::dropdown(['Меню'=>['item'=>['Ликвидировать'=>['href'=>"#delThread$id",'modal'=>"delThread$id"]]]]);
		}
		return self::getThread($id,-1,$info['title'].' '.$menu).self::getSendBox($id).self::getThread($id,$count,'Сообщение '.sm::badge(['txt'=>$count]));
	}

    /**
     * Возвращаем мульти-форму отправки постов
	 * ----------------------------------------
	 * id	-	ид отправки в нить
	 * ----------------------------------------
	 * @return string
     */
	public function getSendBox($id){
		$action=x::getPathModules("xmessage/execute/post.php");
		//-->ид нити
		if($id){
			$id=sm::p(['content'=>sm::input(['name'=>'id','type'=>'hidden','value'=>str_replace('?'.x::getData(),NULL,$id),'placeholder'=>"ид отправителя нити ($id)"])]);
		}
		//-->Сообщение поста
		$desc=sm::p(['content'=>sm::textarea(['name'=>'text','css'=>['width'=>'100%','resize'=>'vertical'],'rows'=>4,'placeholder'=>"Сообщение (8096)\n/bЖирность/ - Жирность\n/sЗачеркнутый текст/ - Зачеркнутый текст\n/iНаклоненные буквы/ - Наклоненные буквы"])]);
		//-->Отправить файлы
		$file=sm::input(['name'=>'upload[]','type'=>'file','multiple'=>true,'accept'=>'image/jpg,image/jpeg,image/png,image/gif']);
		//-->Выполнить (Отправить)
		$submit=sm::input(['type'=>'submit']);
		$key=x::generateSession(x::uuidv4());
		//-->Модальная форма (#donate)
		$любовь=sm::img(['src'=>'/theme/borda/linux/plugins/xmessage/sticker/default/любовь']);
		$радость=sm::img(['src'=>'/theme/borda/linux/plugins/xmessage/sticker/default/радость']);
		$рассплакаться=sm::img(['src'=>'/theme/borda/linux/plugins/xmessage/sticker/default/рассплакаться']);
		$удивление=sm::img(['src'=>'/theme/borda/linux/plugins/xmessage/sticker/default/удивление']);
		sm::modal([
			'id'=>'syntax',
			'title'=>'Помощь ;)',
			'content'=>"Привет это страница помощь написание своей первой статьи :)<br><hr><br>Ссылки использование их<br><hr><br>Ютубище - https://youtu.be/mo6APOpfS3U -> Отоброжается как видео<br>Расширение картинок - .jpeg, .jpg, .png, .gif -> Отоброжается как картинки<hr>Стикеры - стандартный пак (default)</br> $любовь </br> (любовь) </br> $радость </br> (радость) </br> $рассплакаться </br> (рассплакаться) </br> $удивление </br> (удивление)"
		]);
		//-->Открытие формы о Syntax (#syntax)
		$syntax=sm::a(['title'=>'Помощь','href'=>'#syntax','modal'=>'syntax']);
		//-->Syntax (Возможности упрощенного)
		//$b=$skinmanager->btn(['type'=>'submit','title'=>'Жирный']);
		//$s=$skinmanager->btn(['type'=>'submit','title'=>'Зачеркнутый']);
		//$i=$skinmanager->btn(['type'=>'submit','title'=>'Курсив']);
		return	sm::panel(['title'=>'Ответить на сообщение','content'=>$syntax.sm::form(['method'=>'post','enctype'=>'multipart/form-data','action'=>$action,'id'=>x::RedirectUpdate(),'content'=>$id.$name.$desc.$submit.$file.$key.$b.$s.$i])]);
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
}
