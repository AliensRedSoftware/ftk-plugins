<?php
/**
 * Личный кабинет
 * ---------------------------------------------
 * ver 1.50
 */
 //Конфигурация
define('__XPRIVATE_TYPE_ACCOUNT','anon');//Тип аккаунта
//---------------------------------------------
use xlib as x;
use skinmanager as sm;
use xmessage as xm;
use rialto as rt;
use jquery as jq;
use xmotion as moja;
class xprivate{

	public static $data;//Данные пользователя

    /**
     * Возвращаем есть ли доступ к действию
     */
    public function isAccess($p=['createdDot','DelThread']){
        if($_COOKIE['__XPRIVATE_ROOT']!='654rootabc'){
        	return false;
        }
        return true;
    }

    /**
	 * Возвращаем настройки
	 * @return string
	 */
	public function getSettings(){
		//Подключение аккаунта
		$data=self::$data;
		//Выполнение
		$auth=x::getPathModules(__CLASS__ . DIRECTORY_SEPARATOR . 'execute' . DIRECTORY_SEPARATOR . 'auth.php');
		$newPass=x::getPathModules(__CLASS__ . DIRECTORY_SEPARATOR . 'execute' . DIRECTORY_SEPARATOR . 'newPass.php');
		$newAuth=x::getPathModules(__CLASS__ . DIRECTORY_SEPARATOR . 'execute' . DIRECTORY_SEPARATOR . 'newAuth.php');
		$delete=x::getPathModules(__CLASS__ . DIRECTORY_SEPARATOR . 'execute' . DIRECTORY_SEPARATOR . 'delete.php');
		//Данные
		$id=$data['id'];
		//-->login
    	$login=sm::p(['content'=>sm::input(['required'=>true,'name'=>'__XPRIVATE_AUTH','placeholder'=>'ID','value'=>$id,'size'=>32])]);
		//-->pass
    	$pass=sm::p(['content'=>sm::input(['required'=>true,'name'=>'__XPRIVATE_PASS','value'=>$_COOKIE['__XPRIVATE_PASS'],'placeholder'=>'Пароль доступа','type'=>'password','size'=>32])]);
    	//-->logo
    	$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules(__CLASS__ . DIRECTORY_SEPARATOR . 'ico' . DIRECTORY_SEPARATOR . 'logo'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
    	//-->desc
    	$desc=sm::p(['content'=>sm::txt(['txt'=>'Внимание:Для изменение профиля пожалуйста включите пароль доступа'])]);
		//-->Выполнить
		$submit=sm::input(['type'=>'submit','value'=>'Изменить']);
		//-->Изменить аккаунт
		$change=sm::input(['type'=>'submit','value'=>'Сбросить ID']);
		$newPassword=sm::input(['type'=>'submit','value'=>'Генерировать новый пароль']);
		$unlink=sm::input(['type'=>'submit','value'=>'Удалить']);
		//-->Конфигурация Правил
		$private=self::getSettingsPrivate($data);
		$pmp=self::pmp($data);
		//Другие модули
		foreach(x::getModules(false) as $module){
			switch($module){
				case 'xmessage':
					$i=0;
					//cfg dots
					$dots=[];
					if($data['xmessage']['dots'][xm::getROOT()]['root']||$data['root']){
						array_push($dots,basename(xm::getROOT()).':'.xm::getROOT());
					}
					foreach(xm::getDotToArray(xm::getROOT(),true) as $path=>$dot){
						if($data['xmessage']['dots'][$path]['root']||$data['root']){
							array_push($dots,"$dot:$path");
						}
					}
					if(!empty($dots)){
						$i++;
						$effect=[];
						$effect+=['Перейти'=>[]];
						$effect+=['Конфигурация'=>[]];
						$effect+=['Удалить'=>[]];
						$effect=sm::p(['content'=>'Эффект:'.sm::combobox([
							'name'=>'effect',
							'required'=>true,
							$effect
						])]);
						$ListDots=sm::p(['content'=>sm::listView([
							'name'=>'dots',
							'required'=>true,
							'css'=>['width'=>'100%'],
							$dots
						])]);
						sm::modal(['id'=>'xmessage-cfg-dots','title'=>'Конфигурация точек '.sm::badge(['txt'=>count($dots)]),'content'=>
								sm::form([
									'action'=>x::getPathModules(__CLASS__ . DIRECTORY_SEPARATOR . 'execute' . DIRECTORY_SEPARATOR . 'xmessage' . DIRECTORY_SEPARATOR . 'effectCfgDot.php'),
									'id'=>x::RedirectUpdate(),
									'method'=>'post',
									'content'=>$effect.$ListDots.sm::input(['type'=>'submit','value'=>'Bыполнить']).' '.sm::a(['title'=>'Вернуться','href'=>'#xmessage-cfg','modal'=>'xmessage-cfg'])
								])
						]);
						$cfgDots=sm::p(['content'=>sm::a(['title'=>'Конфигурация точек','href'=>'#xmessage-cfg-dots','modal'=>'xmessage-cfg-dots'])]);
					}
					//cfg threads
					$threads=[];
					//SHARE
					foreach(xm::getThreadsToArray(xm::getROOT()) as $id => $thread){
						$info=xm::getInfoThreadToArray()[$id];
						$title=$info['title'];
						$data=self::$data;
						//dot selected
						$dot=substr(xm::getPathSelected(),0,-1);
						if($data['xmessage']['dots'][$dot]['del']||$data['xmessage']['dots'][$dot]['cls']||$data['xmessage']['dots'][$dot]['root']||$info['superuser']==$data['id']||$data['root']){
							//Создание нитей
							xm::getDelThreadForm($id);
							if(!x::is_uuidv4($title)&&$title){
								array_push($threads,"$title:$thread");
							}else{
								array_push($threads,$thread);
							}
						}
					}
					//HIDDEN
					foreach(xm::getThreadsToArray(xm::getROOT(),true, true) as $id => $thread){
						$info=xm::getInfoThreadToArray(true)[$id];
						$title=$info['title'];
						$data=self::$data;
						//dot selected
						$dot=substr(xm::getPathSelected(), 0, -1);
						if($data['xmessage']['dots'][$dot]['del']||$data['xmessage']['dots'][$dot]['cls']||$data['xmessage']['dots'][$dot]['root']||$info['superuser']==$data['id']||$data['root']){
							//Создание нитей
							xm::getDelThreadForm($id);
							if($title){
								array_push($threads,"$title:$thread");
							}else{
								array_push($threads,$thread);
							}
						}
					}
					if(!empty($threads)){
						$i++;
						$effect=[];
						$effect+=['Перейти'=>[]];
						$effect+=['Конфигурация'=>[]];
						$effect+=['Удалить'=>[]];
						$effect=sm::p(['content'=>'Эффект:'.sm::combobox([
							'name'=>'effect',
							'required'=>true,
							$effect
						])]);
						$ListThreads=sm::p(['content'=>sm::listView([
							'name'=>'threads',
							'required'=>true,
							'css'=>['width'=>'100%'],
							$threads
						])]);
						sm::modal(['id'=>'xmessage-cfg-threads','title'=>'Конфигурация нитей '.sm::badge(['txt'=>count($threads)]),'content'=>
								sm::form([
									'action'=>x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'execute'.DIRECTORY_SEPARATOR.'xmessage'.DIRECTORY_SEPARATOR.'effectCfgThread.php'),
									'id'=>x::RedirectUpdate(),
									'method'=>'post',
									'content'=>$effect.$ListThreads.sm::input(['type'=>'submit','value'=>'Bыполнить']).' '.sm::a(['title'=>'Вернуться','href'=>'#xmessage-cfg','modal'=>'xmessage-cfg'])
								])
						]);
						$cfgThreads=sm::p(['content'=>sm::a(['title'=>'Конфигурация нитей','href'=>'#xmessage-cfg-threads','modal'=>'xmessage-cfg-threads'])]);
					}
					if($i>0){
						$cfg=sm::modal(['id'=>'xmessage-cfg','title'=>'Конфигурация xmessage','content'=>$cfgDots.$cfgThreads.sm::p(['content'=>sm::a(['title'=>'Вернуться','href'=>'#xprivate','modal'=>'xprivate'])])]);
						$xmsg=sm::p(['content'=>sm::a(['title'=>'Конфигурация xmessage','href'=>"#$cfg",'modal'=>$cfg])]);
					}
				break;
				case 'rialto':
					$cfg=rt::getCfgRialto();
					$rialto=sm::p(['content'=>sm::a(['title'=>'Конфигурация rialto','href'=>"#$cfg",'modal'=>$cfg])]);
				break;
				case 'xmotion':
					$cfg=moja::getCfg();
					$xmotion=sm::p(['content'=>sm::a(['title'=>'Конфигурация xmotion','href'=>"#$cfg",'modal'=>$cfg])]);
				break;
			}
		}
		return sm::modal(['id'=>'xprivate','title'=>'xprivate Личный кабинет','content'=>sm::form(['id'=>x::RedirectUpdate(),'action'=>$auth,'method'=>'post','content'=>$private.$pmp.$rialto.$xmotion.$xmsg.$login.$pass.$submit]).sm::form(['action'=>$newAuth,'id'=>x::RedirectUpdate(),'method'=>'post','content'=>$change]).sm::form(['action'=>$newPass,'id'=>x::RedirectUpdate(),'method'=>'post','content'=>$newPassword]).sm::form(['action'=>$delete,'id'=>x::RedirectUpdate(),'method'=>'post','content'=>$unlink]).$logo.$desc]);
	}

	/**
	 * Возвращаем правила
	 */
	public function pmp($data){
		$AVA=sm::p(['content'=>sm::input(['name'=>'ava','value'=>'Видимость аватарки','type'=>'checkbox'])]);
		$name=sm::p(['content'=>sm::input(['name'=>'name','value'=>'Видимость имени','type'=>'checkbox'])]);
		$desc=sm::p(['content'=>sm::input(['name'=>'desc','value'=>'Видимость описание','type'=>'checkbox'])]);
		$submit=sm::input(['value'=>'Изменить','type'=>'submit']);
		$return=sm::a(['title'=>'Вернуться','modal'=>'xprivate','href'=>'#xprivate']);
		sm::modal(['id'=>'pmp','title'=>'Конфигурация приватности','content'=>$AVA.$name.$desc.$submit.' '.$return]);
		return sm::p(['content'=>sm::a(['title'=>'Конфигурация приватности','modal'=>'pmp','href'=>'#pmp'])]);
	}

	/**
	 * Авто-удаление аккантов по сроку
	 */
	public function autoclear(){
		$IDS=[];
		//ACCOUNT CLEAR
		foreach(x::scandir(__DIR__.DIRECTORY_SEPARATOR.'account'.DIRECTORY_SEPARATOR.'anon'.DIRECTORY_SEPARATOR) as $id){
			$data=self::getDataId($id);
			$y=date('Y');
			$m=date('m');
			if($y==$data['active']['y']){
				if($m!=$data['active']['m']){
					array_map('unlink',array_filter((array)array_merge(glob(__DIR__.DIRECTORY_SEPARATOR.'account'.DIRECTORY_SEPARATOR.'anon'.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.'*'))));
					array_map('rmdir',array_filter((array)array_merge(glob(__DIR__.DIRECTORY_SEPARATOR.'account'.DIRECTORY_SEPARATOR.'anon'.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.'*'))));
					rmdir(__DIR__.DIRECTORY_SEPARATOR.'account'.DIRECTORY_SEPARATOR.'anon'.DIRECTORY_SEPARATOR.$id);
				}else{
					$id=substr($id,0,12);
					$IDS[$id]=true;
				}
			}
		}
		//OTHER CLEAR
		//CACHE (small)
		foreach(x::scandir(__DIR__.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'small'.DIRECTORY_SEPARATOR) as $id){
			if(!$IDS[$id]){
				unlink(__DIR__.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'small'.DIRECTORY_SEPARATOR.$id);
			}
		}
		//CACHE (profile)
		foreach(x::scandir(__DIR__.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'profile'.DIRECTORY_SEPARATOR) as $id){
			if(!$IDS[$id]){
				unlink(__DIR__.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'profile'.DIRECTORY_SEPARATOR.$id);
			}
		}
	}
	/**
	 * Инициализация
	 * data-Аккаунт
	 * active-Поддержка состояние жизни аккаунта
	 */
	public function init($data,$active=false){
		$id=$data['id'];
		//Обновление времени
		if($id!='undefined'||$id){
			//extensions
			if(x::isModule('rialto',false)){
				foreach(rt::getNameWallets() as $name){
					//balance update balance
					if(!$data['rialto'][$name]){
						$data['rialto'][$name]=['value'=>0,'attempt'=>3];
					}
				}
			}
			//date update active session
			if($active){
				//Дополнение
				//rialto
				if(x::isModule('rialto',false)){
					foreach(rt::getNameWallets() as $name){
						//attempt update
						if($data['active']['y']!=date('Y')){
							$data['rialto'][$name]['attempt']=3;
						}elseif($data['active']['m']!=date('m')){
							$data['rialto'][$name]['attempt']=3;
						}elseif($data['active']['d']!=date('d')){
							$data['rialto'][$name]['attempt']=3;
						}
					}
				}
				//xmotion
				if(x::isModule('xmotion',false)){
					if(!is_array($data['xmotion'])){
						//cfg
						$data['xmotion']['ConnectMax']=__XMOTION_CONNECT_MAX;//Максимальное кол-во подключение паков
						$data['xmotion']['ConnectSlotMax']=__XMOTION_CONNECT_SLOT_MAX;//Максимальное кол-во связывание moja в слот
						//animate
						$data['xmotion']['animate']=[];
						//img
						$data['xmotion']['img']=[];
					}
				}
				//update info account (XMESSAGE)
				if(x::isModule('xmessage',false)){
					foreach(self::getIdsToArray() as $foo){
						$stream=self::getDataId($foo);
						//thread
						foreach($stream['xmessage']['threads'] as $path=>$stack){
							if($stream['xmessage']['threads'][$path]['root']){
								//comment
								if(!is_bool($data['xmessage']['threads'][$path]['comment']) && !is_string($data['xmessage']['threads'][$path]['comment'])){
									if($stream['xmessage']['threads'][$path]['comment']=='*'){
										$data['xmessage']['threads'][$path]['comment']=true;
									}
								}
								//exts
								if(is_null($data['xmessage']['threads'][$path]['exts'])){
									$data['xmessage']['threads'][$path]['exts']=$stream['xmessage']['threads'][$path]['exts'];
								}
							}
						}
						//DOT
						foreach($stream['xmessage']['dots'] as $path=>$stack){
							//newThread
							if($stream['xmessage']['dots'][$path]['newThread']=='*'){
								if(!is_bool($data['xmessage']['threads'][$path]['newThread']) && !is_string($data['xmessage']['threads'][$path]['newThread'])){
									//add rule
									$data['xmessage']['dots'][$path]['newThread']=true;
								}
							}
							//new
							if($stream['xmessage']['dots'][$path]['new']=='*'){
								if(!is_bool($data['xmessage']['threads'][$path]['new']) && !is_string($data['xmessage']['threads'][$path]['new'])){
									//add rule
									$data['xmessage']['dots'][$path]['new']=true;
								}
							}
							//del
							if($stream['xmessage']['dots'][$path]['del']=='*'){
								if(!is_bool($data['xmessage']['threads'][$path]['del']) && !is_string($data['xmessage']['threads'][$path]['del'])){
									//add rule
									$data['xmessage']['dots'][$path]['del']=true;
								}
							}
							//cls
							if($stream['xmessage']['dots'][$path]['cls']=='*'){
								if(!is_bool($data['xmessage']['threads'][$path]['cls']) && !is_string($data['xmessage']['threads'][$path]['cls'])){
									//add rule
									$data['xmessage']['dots'][$path]['cls']=true;
								}
							}
						}
					}
				}
				//Срок жизни аккаунт
				$data['active']=['y'=>date('Y'),'m'=>date('m'),'d'=>date('d')];
				//share var
				self::$data=$data;
			}
			file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'anon' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . 'user.json', json_encode($data));
			return $data;
		}
		return false;
	}

	/**
	 * Выполнить
	 */
	public function execute(){
		//autoclear
		self::autoclear();
		//cfg
		$type=false;
		if(!$_COOKIE['__XPRIVATE_AUTH']){
			if(empty($type)){
				$id=str_replace('-',NULL,x::uuidv4());
				//Ид
				setcookie('__XPRIVATE_AUTH',$id, 0, '/');
				$_COOKIE['__XPRIVATE_AUTH']=$id;
				//Пароль
				$pass=x::uuidv4();
				//Создание пользователя анонимного
				mkdir(__DIR__ . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'anon' . DIRECTORY_SEPARATOR . $id);
				chmod(__DIR__ . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'anon' . DIRECTORY_SEPARATOR . $id, 0777);
				//ava
				mkdir(__DIR__ . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'anon' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . 'ico');
				chmod(__DIR__ . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'anon' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . 'ico', 0777);
//информация
//id		-	ид
//pass		-	Пароль
//mark		-	Маркеровка
//auth		-	Авторизация нового пользователя
//root		-	Права суперпользователя
//name		-	Имя или ник
//date		-	Дата создание
//active	-	Дата последнего актива
//private	-	Приватность
$data=[
	'id'		=>	$id,
	'pass'		=>	$pass,
	'mark'		=>	uniqid(),
	'auth'		=>	false,
	'root'		=>	false,
	'date'		=>	['y'=>date('Y'),'m'=>date('m'),'d'=>date('d')],
    'active'	=>	['y'=>date('Y'),'m'=>date('m'),'d'=>date('d')],
    'private'	=>	[
    					'name'		=>	'Нейзвестный',
    					'desc'		=>	'Ничего не найдено',
    					'gender'	=>	false,
    					'dateIn'	=>	date('Y')-50
    				]
];
				//extensions
				//rialto
				if(x::isModule('rialto',false)){
					foreach(rt::getNameWallets() as $name){
						$data['rialto'][$name]=['value'=>0,'attempt'=>3];
					}
				}
				//xmessage
				if(x::isModule('xmessage',false)){
					//threads
					$data['xmessage']['threads']=[];
					//dots
					$data['xmessage']['dots']=[];
				}
				//xmotion (Умные поведение)
				if(x::isModule('xmotion',false)){
					//cfg
					$data['xmotion']['ConnectMax']=__XMOTION_CONNECT_MAX;//Максимальное кол-во подключение паков
					$data['xmotion']['ConnectSlotMax']=__XMOTION_CONNECT_SLOT_MAX;//Максимальное кол-во связывание moja в слот
					//animate
					$data['xmotion']['animate']=[];
					//img
					$data['xmotion']['img']=[];
				}
				file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'anon' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . 'user.json', json_encode($data));//exec user
				chmod(__DIR__ . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'anon' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . 'user.json',0777);
				//Подключить профиль
				self::init($data,true);
			}
		}else{
			if(empty($type)){
				$data=self::getData();
				if($data['id'] == 'undefined' || !$data['id']){
					rmdir(__DIR__ . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'anon' . DIRECTORY_SEPARATOR . $_COOKIE['__XPRIVATE_AUTH']);
					setcookie('__XPRIVATE_AUTH', NULL);
					$_COOKIE['__XPRIVATE_AUTH'] = NULL;
					self::execute();
				}else{
					//Подключить профиль
					self::init($data, true);
				}
			}else{
				
			}
		}
		if(!$data['auth'] && !$_REQUEST['auth']){
			$pass=$data['pass'];
			//xprivate (конфигурация пользователя)
			$xp=sm::a(['title'=>'Личном кабинете','href'=>'#xprivate','modal'=>'xprivate']);
			$private=sm::img(['css'=>['width'=>'320px','pointer-events'=> 'none'],'src'=>x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'private')]);
			sm::modal([
				'open'		=>	1,
				'title'		=>	'Новый пользователь на слим спейсе! :)',
				'content'	=>	'Добро пожаловать на слим спейс</br>Это ваш пароль доступа для изменение в личный кабинет '. sm::badge(['txt'=>$pass]) ."</br>Пожалуйста запишите его на листочек и не теряйте :)</br>Также следует ради безопасности никому не говорить про ID и Пароль это возможно мошенники!</br>Познакомьтесь со своим $xp</br>$private"
			]);
		}
	}

    /**
	 * Возвращаем форму (Изменение профиля)
	 * @return form
	 */
	public function getSettingsPrivate($data){
		$action=x::getPathModules(__CLASS__ . DIRECTORY_SEPARATOR . 'execute' . DIRECTORY_SEPARATOR . 'private.php');
		//-->Имя
		$name=$data['private']['name'];
		$desc=$data['private']['desc'];
		//-->Имя создателя поста
		$name=sm::txt(['txt'=>'Имя или ник:']);
		$name=$name.sm::p(['content'=>sm::input(['name'=>'NAME','value'=>$data['private']['name'],'placeholder'=>'Имя или ник (32)'])]);
		//-->Гендер
		$gender=sm::txt(['txt'=>'Кто ты ?:']);
		switch($data['private']['gender']){
			case 'Мальчик':
				$gender=$gender.sm::p(['content'=>
						sm::input(['name'=>'gender','type'=>'radio','value'=>'Мальчик','checked'=>1]).' или '.
						sm::input(['name'=>'gender','type'=>'radio','value'=>'Девачка'])
					]);
			break;
			case 'Девачка':
				$gender=$gender.sm::p(['content'=>
						sm::input(['name'=>'gender','type'=>'radio','value'=>'Мальчик']).' или '.
						sm::input(['name'=>'gender','type'=>'radio','value'=>'Девачка','checked'=>1])
					]);
			break;
			default:
				$gender=$gender.sm::p(['content'=>
					sm::input(['name'=>'gender','type'=>'radio','value'=>'Мальчик']).' или '.
					sm::input(['name'=>'gender','type'=>'radio','value'=>'Девачка'])
				]);
			break;
		}
		//-->Описание
		$desc=sm::txt(['txt'=>'Описание:']);
		$desc=$desc.sm::p(['content'=>sm::textarea(['name'=>'DESC','max'=>2048,'value'=>$data['private']['desc'],'css'=>['width'=>'100%','resize'=>'vertical'],'placeholder'=>'Описание (2048)','rows'=>4])]);
		//-->logo
    	$ava=sm::img(['src'=>self::getCacheAva($data['id']),'css'=>['width'=>'128px','pointer-events'=>'none','border-radius'=>'100px']]);
		//-->Изменить аккаунт
		$change=sm::input(['type'=>'submit','value'=>'Изменить']);
		//-->Вернуться назад
		$xprivate=' '.sm::a(['href'=>'#xprivate','modal'=>'xprivate','title'=>'Вернуться']);
		//-->Файлы
		$file=sm::p(['content'=>sm::input(['name'=>'upload','type'=>'file','accept'=>'image/jpg,image/jpeg,image/png,image/gif,image/webp'])]);
    	//-->Дата начало
    	$min=date('Y')-100;
    	$max=$min+100;
    	$dateIn=sm::txt(['txt'=>'Дата начало '.sm::badge(['txt'=>date('Y')-$data['private']['dateIn'].' лет'])]);
		$dateIn=$dateIn.sm::p(['content'=>sm::input(['max'=>$max,'min'=>$min,'type'=>'number','name'=>'dateIn','value'=>$data['private']['dateIn'],'placeholder'=>'Дата начало'])]);
    	//-->Дата окончание
    	$max=$data['private']['dateIn'] + 100;
    	$dateOut=sm::txt(['txt'=>'Дата окончание:']);
		$dateOut=$dateOut.sm::p(['content'=>sm::input(['readonly'=>true,'type'=>'number','value'=>$max,'placeholder'=>'Дата окончание'])]);
    	//Метка SS
		if($data['root']){
			$SS=' '.sm::badge(['txt'=>'SS']);
		}
		$account=sm::modal(['id'=>'private','title'=>'Конфигурация личной информаций '.sm::badge(['txt'=>$data['mark']]).$SS,'content'=>sm::form(['method'=>'post','id'=>x::RedirectUpdate(),'action'=>$action,'enctype'=>'multipart/form-data','content'=>$ava.$file.$HTR.$name.$gender.$dateIn.$dateOut.$desc.$change.$xprivate])]);
    	return	sm::p(['content'=>sm::a(['title'=>sm::ico('cog').'Конфигурация личной информаций','href'=>"#$account",'modal'=>$account])]);
    }

	/**
     * Кэширование
     */
	public function cacheAva(){
    	$data=self::$data;
    	$id=substr($data['id'], 0, 12);
    	if(md5_file(__DIR__ . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'anon' . DIRECTORY_SEPARATOR . $data['id'] . DIRECTORY_SEPARATOR . 'ico' . DIRECTORY_SEPARATOR . 'ava') != md5_file('.' . DIRECTORY_SEPARATOR . 'ico' . DIRECTORY_SEPARATOR . 'ava')){
    		//small
    	    x::resizeImg(__DIR__ . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'anon' . DIRECTORY_SEPARATOR . $data['id'] . DIRECTORY_SEPARATOR . 'ico' . DIRECTORY_SEPARATOR . 'ava', 50, 50, __DIR__ . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'small' . DIRECTORY_SEPARATOR . $id);
    	    //ava
    	    x::resizeImg(__DIR__ . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'anon' . DIRECTORY_SEPARATOR . $data['id'] . DIRECTORY_SEPARATOR . 'ico' . DIRECTORY_SEPARATOR . 'ava', 128, 128, __DIR__ . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'profile' . DIRECTORY_SEPARATOR . $id);
    	}else{
    		unlink(__DIR__ . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . $id);
    	}
	}

    /**
     * Кэширование (Small)
     * @return string
     */
	public function getCacheSmallAva($id){
    	$data=self::getDataId($id);
    	if(!$data['id']){
    		return x::getPathModules(__CLASS__ . DIRECTORY_SEPARATOR . 'ico' . DIRECTORY_SEPARATOR . 'ava');
    	}
    	$id=substr($data['id'],0,12);
    	if(!is_file(__DIR__ . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'small' . DIRECTORY_SEPARATOR . $id)){
    		return x::getPathModules(__CLASS__ . DIRECTORY_SEPARATOR.'ico' . DIRECTORY_SEPARATOR . 'ava');
    	}else{
    		return x::getPathModules(__CLASS__ . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'small' . DIRECTORY_SEPARATOR . $id);
    	}
	}

    /**
     * Кэширование (Ava)
     * @return string
     */
	public function getCacheAva($id){
    	$data=self::getDataId($id);
    	$id=substr($data['id'],0,12);
		if(is_file(__DIR__ . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'profile' . DIRECTORY_SEPARATOR . $id)){
			return x::getPathModules(__CLASS__ . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'profile' . DIRECTORY_SEPARATOR . $id);
		}
		return x::getPathModules(__CLASS__ . DIRECTORY_SEPARATOR . 'ico' . DIRECTORY_SEPARATOR . 'ava');
	}
    /**
	 * Возвращаем форму (Профиля)
	 * ---------------------------
	 */
	public function getViewAccount($id){
	    if(self::$data['id']==$id){
	        return 'private';
	    }elseif(!empty($id)){
		    $data=self::getDataId($id);
		    //Дополнительные
		    if($data['id']!='undefined'){
		    	//rialto (Биржа)
				if(x::isModule('rialto',false)){
					$plugins.=rt::getWalletObject($data);
				}
			}
		    //-->Имя создателя поста
		    $name=sm::txt(['txt'=>'Имя или ник:']);
		    $name=$name.sm::p(['content'=>sm::input(['value'=>$data['private']['name'],'readonly'=>1])]);
		    //-->Гендер
			$gender=sm::txt(['txt'=>'Я:']);
			switch($data['private']['gender']){
				case 'Мальчик':
					$gender=$gender.sm::p(['content'=>sm::input(['enabled'=>0,'type'=>'radio','value'=>'Мальчик','checked'=>1])]);
				break;
				case 'Девачка':
					$gender=$gender.sm::p(['content'=>sm::input(['enabled'=>0,'type'=>'radio','value'=>'Девачка','checked'=>1])]);
				break;
				default:
					$gender=$gender.sm::p(['content'=>
						sm::input(['enabled'=>0,'type'=>'radio','value'=>'Мальчик']).' или '.
						sm::input(['enabled'=>0,'type'=>'radio','value'=>'Девачка'])
					]);
				break;
			}
		    //-->ava
		    $ava=sm::img(['src'=>self::getCacheAva($id),'css'=>['width'=>'128px','pointer-events'=>'none','border-radius'=>'100px']]);
			//-->Дата начало
			$min=date('Y') - 100;
			$max=$min + 100;
			$age=sm::badge(['txt'=>date('Y')-$data['private']['dateIn'].' лет']);
			$dateIn=sm::txt(['txt'=>"Дата начало $age"]);
			if(is_null($data['private']['dateIn'])){
				$dateIn=$dateIn.sm::p(['content'=>sm::input(['readonly'=>true,'type'=>'number','value'=>date('Y'),'placeholder'=>'Дата начало'])]);
			}else{
				$dateIn=$dateIn.sm::p(['content'=>sm::input(['readonly'=>true,'max'=>$max,'min'=>$min,'type'=>'number','name'=>'dateIn','value'=>$data['private']['dateIn'],'placeholder'=>'Дата начало'])]);
			}
			//-->Дата окончание
			if(!is_null($data['private']['dateIn'])){
				$min=$data['private']['dateIn'] - 100;
				$max=$data['private']['dateIn'] + 100;
				$dateOut=sm::txt(['txt'=>'Дата окончание:']);
				$dateOut=$dateOut.sm::p(['content'=>sm::input(['readonly'=>true,'type'=>'number','value'=>$max,'placeholder'=>'Дата окончание'])]);
		    }
			//-->Описание
		    $desc=sm::txt(['txt'=>'Описание:']);
		    $desc=$desc.sm::p(['content'=>sm::textarea(['value'=>$data['private']['desc'],'readonly'=>1,'css'=>['width'=>'100%','resize'=>'vertical'],'rows'=>4])]);
		    $id=substr($data['id'],0,12);
		    //Метка SS
			if($data['root']){
				$SS=' '.sm::badge(['txt'=>'SS']);
			}
		    return sm::modal(['id'=>"$id-private",'title'=>$data['private']['name'].' '.sm::badge(['txt'=>$data['mark']]).$SS,'content'=>$ava.$plugins.$name.$gender.$dateIn.$dateOut.$desc]);
		}
		return false;
	}

    /**
     * Возвращаем данные пользователя
     * @return array
     */
	protected function getData(){
     	$id=$_COOKIE['__XPRIVATE_AUTH'];
		if(!is_file(__DIR__ . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'anon' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . 'user.json')){
			return false;
     	}
		//init...
		return self::init(json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'anon' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . 'user.json'), true));
	}

    /**
     * Возвращаем по id данные пользователя
     * @return array
     */
	public function getDataId($id){
    	if($id){
     		if(is_file(__DIR__ . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'anon' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . 'user.json')){
     			//init...
     			return self::init(json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'anon' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . 'user.json'), true));
     		}else{
     			foreach(x::scandir(__DIR__ . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'anon') as $foo){
     		 		if(substr($foo,0,12) == $id){
     		 	 		//init...
     		 	 		return self::init(json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'anon' . DIRECTORY_SEPARATOR . $foo . DIRECTORY_SEPARATOR . 'user.json'), true));
     		 		}
     			}
     		}
    	}
return [
	'id'		=>	'undefined',
	'mark'		=>	'undefined',
	'private'	=>	[
						'name'	=>'Нейзвестный',
						'desc'	=>'Ничего не найдено',
						'dateIn'=>NULL
					]
];
	 }

    /**
     * Установить значение профиля
     * data-данные
     * all-Полная замена
     * @return array
     */
	public function setData(array $data,$all=false){
		if(!$all){
			$account=self::$data;
			$id=$account['id'];
			if($id!='undefined'){
				$data=array_replace_recursive($account,$data);
				file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'anon' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . 'user.json',json_encode($data));
			}
			self::$data=$data;
			return true;
		}else{
     	 	$id=$data['id'];
			if(is_dir(__DIR__ . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'anon' . DIRECTORY_SEPARATOR . $id)){
				file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'anon' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . 'user.json',json_encode($data));
			}
			//update
			self::$data=self::getData();
			return true;
		}
		return false;
	}

    /**
     * Установить значение профиля указанному
     * @return array
     */
	public function setDataId(array $data,$id,$all=false){
		if(!$all){
     		$account=self::getDataId($id);
			$id=$account['id'];
			if($id != 'undefined'){
				$data=array_replace_recursive($account,$data);
				file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'anon' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . 'user.json', json_encode($data));
			}
			//update
			if($id==self::$data['id']){
				self::$data=$data;
			}
			return true;
		} else{
			$id=$data['id'];
			if(is_dir(__DIR__ . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'anon' . DIRECTORY_SEPARATOR . $id)){
				file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'anon' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . 'user.json',json_encode($data));
			}
			//update
			if($id==self::$data['id']){
				self::$data=self::getData();
			}
			return true;
		}
    	return false;
	}

     /**
      * Возвращаем валидность маркеров
      * @return bool
      */
	public function checkMark($arr){
		$count=count(x::scandir(__DIR__ . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'anon'));
		$i=0;
		foreach($arr as $mark){
			//Аккаунты чек
			foreach(x::scandir(__DIR__ . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'anon') as $id){
				$i++;
				$data=json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'anon' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . 'user.json'), true);
     	 		if($data['mark']==$mark){
     	 	 		continue 2;
     	 		}
     	 		if($i==$count){
     	 	 		return false;
     	 		}
     		}
    	}
    	return true;
	}

     /**
      * Возвращаем ид пользователя по маркеру
      */
    public function getIdMarker($mark){
        foreach(x::scandir(__DIR__ . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'anon') as $id){
     	 	 $data=json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'anon' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . 'user.json'), true);
            if($data['mark']==$mark){
                return $data['id'];
            }
        }
        return false;
    }

     /**
      * Возвращаем ид пользователей
      * @return array
      */
     public function getIdsToArray(){
     	 $arr=[];
     	 foreach(x::scandir(__DIR__ . DIRECTORY_SEPARATOR .'account' . DIRECTORY_SEPARATOR . 'anon') as $id){
     	 	 $data=json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'account' . DIRECTORY_SEPARATOR . 'anon' . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR . 'user.json'), true);
     	 	 array_push($arr, $data['id']);
     	 }
     	 return $arr;
     }

	/**
	 * Возвращаем список пользователей просмотр нитей
	 * id - ид нити
	 */
	public function getUsersView($id){
		$count="LIMIT 1";
		$sql=x::getmysql();
		//view
		$view=unserialize(mysqli_fetch_array(mysqli_query($sql,"SELECT * FROM `$id` ORDER BY `id` ASC $count"))['view']);
		mysqli_close($sql);
	}
}
