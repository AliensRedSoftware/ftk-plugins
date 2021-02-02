<?php
/**
 * Личный кабинет
 * ---------------------------------------------
 * ver 1.45
 */
use xlib as x;
use skinmanager as sm;
use xmessage as xm;
use rialto as rt;
use jquery as jq;
class xprivate{

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
	 * --------------------
	 * @return string
	 */
	public function getSettings(){
		//Подключение аккаунта
		$data=self::getData();
		//Выполнение
		$auth=x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'execute'.DIRECTORY_SEPARATOR.'auth.php');
		$newPass=x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'execute'.DIRECTORY_SEPARATOR.'newPass.php');
		$newAuth=x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'execute'.DIRECTORY_SEPARATOR.'newAuth.php');
		$delete=x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'execute'.DIRECTORY_SEPARATOR.'delete.php');
		//Данные
		$id=$data['id'];
		//-->login
    	$login=sm::p(['content'=>sm::input(['name'=>'__XPRIVATE_AUTH','placeholder'=>'ID','value'=>$id,'size'=>32])]);
		//-->pass
    	$pass=sm::p(['content'=>sm::input(['name'=>'__XPRIVATE_PASS','value'=>$_COOKIE['__XPRIVATE_PASS'],'placeholder'=>'Пароль доступа','type'=>'password','size'=>32])]);
    	//-->logo
    	$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR."ico/logo"),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
    	//-->desc
    	$desc=sm::p(['content'=>sm::text(['text'=>'Внимание:Для изменение профиля пожалуйста включите пароль доступа'])]);
		//-->Выполнить
		$submit=sm::input(['type'=>'submit','value'=>'Изменить']);
		//-->Изменить аккаунт
		$change=sm::input(['type'=>'submit','value'=>'Сбросить ID']);
		$newPassword=sm::input(['type'=>'submit','value'=>'Генерировать новый пароль']);
		$unlink=sm::input(['type'=>'submit','value'=>'Выбросить']);
		//-->Конфигурация Правил
		$private=self::getSettingsPrivate($data);
		$pmp=self::pmp($data);
		//Другие модули
		foreach(x::getModules() as $module){
			switch($module){
				case 'xmessage':
					$item=[];
					foreach(xm::getThreadsToArray() as $id=>$thread){
						$info=xm::getInfoThreadToArray()[$id];
						$title=$info['title'];
						if($info['superuser']==self::getData()['id']){
							//Создание нитей
							xm::getDelThreadForm($id);
							if(!x::is_uuidv4($title)){
								array_push($item,"$title:$thread");
							}else{
								array_push($item,$thread);
							}
						}
					}
					$effect=[];
					$effect+=['Перейти'=>[]];
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
						$item
					])]);
					sm::modal(['id'=>'xmessage-cfg-threads','title'=>'Конфигурация нитей '.sm::badge(['txt'=>count($item)]),'content'=>
							sm::form([
								'action'=>x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'execute'.DIRECTORY_SEPARATOR.'xmessage'.DIRECTORY_SEPARATOR.'effectCfgThread.php'),
								'id'=>x::RedirectUpdate(),
								'method'=>'post',
								'content'=>$effect.$ListThreads.sm::input(['type'=>'submit','value'=>'Bыполнить']).' '.sm::a(['title'=>'Вернуться','href'=>'#xmessage-cfg','modal'=>'xmessage-cfg'])
							])
					]);
					$cfg=sm::modal(['id'=>'xmessage-cfg','title'=>'Конфигурация xmessage','content'=>
						sm::p(['content'=>sm::a(['title'=>'Конфигурация нитей','href'=>'#xmessage-cfg-threads','modal'=>'xmessage-cfg-threads'])]).
						sm::p(['content'=>sm::a(['title'=>'Вернуться','href'=>'#xprivate','modal'=>'xprivate'])])
					]);
					$xmsg=sm::p(['content'=>sm::a(['title'=>'Конфигурация xmessage','href'=>"#$cfg",'modal'=>$cfg])]);
				break;
				case 'rialto':
					$cfg=rt::getCfgRialto();
					$rialto=sm::p(['content'=>sm::a(['title'=>'Конфигурация rialto','href'=>"#$cfg",'modal'=>$cfg])]);
				break;
			}
		}
		return sm::modal(['id'=>'xprivate','title'=>'xprivate Личный кабинет','content'=>sm::form(['id'=>x::RedirectUpdate(),'action'=>$auth,'method'=>'post','content'=>$private.$pmp.$rialto.$xmsg.$login.$pass.$submit]).sm::form(['action'=>$newAuth,'id'=>x::RedirectUpdate(),'method'=>'post','content'=>$change]).sm::form(['action'=>$newPass,'id'=>x::RedirectUpdate(),'method'=>'post','content'=>$newPassword]).sm::form(['action'=>$delete,'id'=>x::RedirectUpdate(),'method'=>'post','content'=>$unlink]).$logo.$desc]);
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
		foreach(scandir(__DIR__.DIRECTORY_SEPARATOR.'account'.DIRECTORY_SEPARATOR.'anon'.DIRECTORY_SEPARATOR) as $id){
			if($id!='.'&&$id!='..'){
				$data=self::getDataId($id);
				$y=date('Y');
				$m=date('m');
				if($y==$data['active']['y']){
					if($m!=$data['active']['m']){
						array_map('unlink',array_filter((array)array_merge(glob(__DIR__.DIRECTORY_SEPARATOR.'account'.DIRECTORY_SEPARATOR.'anon'.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.'*'))));
						array_map('rmdir',array_filter((array)array_merge(glob(__DIR__.DIRECTORY_SEPARATOR.'account'.DIRECTORY_SEPARATOR.'anon'.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.'*'))));
						rmdir(__DIR__.DIRECTORY_SEPARATOR.'account'.DIRECTORY_SEPARATOR.'anon'.DIRECTORY_SEPARATOR.$id);
						//cache autoclear
						$id=substr($id,0,12);
						unlink(__DIR__.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'small'.DIRECTORY_SEPARATOR.$id);
						unlink(__DIR__.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'profile'.DIRECTORY_SEPARATOR.$id);
					}
				}
			}
		}
	}
	/**
	 * Инициализация
	 * data-Воозбновить аккаунт
	 */
	public function init($data){
		$id=$data['id'];
		//Обновление времени
		if($id!='undefined'||$id){
			if($data['active']['y']!=date('Y')){
				$data['attempt']=3;
			}elseif($data['active']['m']!=date('m')){
				$data['attempt']=3;
			}elseif($data['active']['d']!=date('d')){
				$data['attempt']=3;
			}
			$data['active']=['y'=>date('Y'),'m'=>date('m'),'d'=>date('d')];
			file_put_contents(__DIR__."/account/anon/$id/user.json",json_encode($data));
		}
	}
	/**
	 * Выполнить
	 */
	function execute(){
		//autoclear
		self::autoclear();
		//cfg
		$type=false;
		if(!$_COOKIE['__XPRIVATE_AUTH']){
			if(empty($type)){
				$id=str_replace('-',NULL,x::uuidv4());
				//auth id
				if($id){
					setcookie('__XPRIVATE_AUTH',$id,0,'/');
					$_COOKIE['__XPRIVATE_AUTH']=$id;
				}
				$pass=x::uuidv4();
				//Создание пользователя анонимного
				mkdir(__DIR__."/account/anon/$id");
				chmod(__DIR__."/account/anon/$id",0777);
				//ava
				mkdir(__DIR__."/account/anon/$id/ico");
				chmod(__DIR__."/account/anon/$id/ico",0777);
				//информация
$data=[
	'id'		=>$id,
	'pass'		=>$pass,
	'auth'		=>false,
	'name'		=>'Нейзвестный',
	'dateIn'	=>date('Y')-50,
    'desc'		=>'Ничего не найдено',
    'attempt'	=>3,
    'date'		=>['y'=>date('Y'),'m'=>date('m'),'d'=>date('d')],
    'active'	=>['y'=>date('Y'),'m'=>date('m'),'d'=>date('d')]
];
				file_put_contents(__DIR__.DIRECTORY_SEPARATOR.'account'.DIRECTORY_SEPARATOR.'anon'.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.'user.json',json_encode($data));
				chmod(__DIR__.DIRECTORY_SEPARATOR.'account'.DIRECTORY_SEPARATOR.'anon'.DIRECTORY_SEPARATOR.$id.DIRECTORY_SEPARATOR.'user.json',0777);

			}
		}else{
			if(empty($type)){
				$data=self::getData();
				if($data['id']=='undefined'||!$data['id']){
					rmdir(__DIR__.DIRECTORY_SEPARATOR.'account'.DIRECTORY_SEPARATOR.'anon'.DIRECTORY_SEPARATOR.$_COOKIE['__XPRIVATE_AUTH']);
					setcookie('__XPRIVATE_AUTH',NULL);
					$_COOKIE['__XPRIVATE_AUTH']=NULL;
					self::execute();
				}else{
					//Подключить профиль
					self::init($data);
				}
			}else{
				
			}
		}
		if(!$data['auth']&&!$_REQUEST['auth']){
			$pass=$data['pass'];
			//xprivate (конфигурация пользователя)
			$xp=sm::a(['title'=>'Личном кабинете','href'=>'#xprivate','modal'=>'xprivate']);
			$private=sm::img(['css'=>['width'=>'320px','pointer-events'=> 'none'],'src'=>x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'private')]);
			sm::modal([
				'open'		=>	1,
				'title'		=>	'Новый пользователь на слим спейсе! :)',
				'content'	=> 'Добро пожаловать на слим спейс</br>Это ваш пароль доступа для изменение в личный кабинет '. sm::badge(['txt'=>$pass]) ."</br>Пожалуйста запишите его на листочек и не теряйте :)</br>Также следует ради безопасности никому не говорить про ID и Пароль это возможно мошенники!</br>Познакомьтесь со своим $xp</br>$private"
			]);
		}

	}
    /**
	 * Возвращаем форму (Изменение профиля)
	 * ---------------------------
	 */
	public function getSettingsPrivate($data){
		$action=x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'execute'.DIRECTORY_SEPARATOR.'private.php');
		//-->Имя
		$name=$data['name'];
		$desc=$data['desc'];
		//-->Имя создателя поста
		$name=sm::text(['text'=>'Имя или ник:']);
		$name=$name.sm::p(['content'=>sm::input(['name'=>'NAME','value'=>$data['name'],'placeholder'=>'Имя или ник (32)'])]);
		//-->Описание
		$desc=sm::text(['text'=>'Описание:']);
		$desc=$desc.sm::p(['content'=>sm::textarea(['name'=>'DESC','value'=>$data['desc'],'css'=>['width'=>'100%','resize'=>'vertical'],'placeholder'=>'Описание (2048)','rows'=>4])]);
		//-->logo
    	$ava=sm::img(['src'=>self::getCacheAva($data['id']),'css'=>['width'=>'128px','pointer-events'=>'none','border-radius'=>'100px']]);
		//-->Изменить аккаунт
		$change=sm::input(['type'=>'submit','value'=>'Изменить']);
		//-->Вернуться назад
		$xprivate=' '.sm::a(['href'=>'#xprivate','modal'=>'xprivate','title'=>'Вернуться']);
		//-->Файлы
		$file=sm::p(['content'=>sm::input(['name'=>'upload','type'=>'file','accept'=>'image/jpg,image/jpeg,image/png,image/gif'])]);
    	//-->Дата начало
    	$min=date('Y')-100;
    	$max=$min+100;
    	$dateIn=sm::text(['text'=>'Дата начало '.sm::badge(['txt'=>date('Y')-$data['dateIn'].' лет']).':']);
		$dateIn=$dateIn.sm::p(['content'=>sm::input(['max'=>$max,'min'=>$min,'type'=>'number','name'=>'dateIn','value'=>$data['dateIn'],'placeholder'=>'Дата начало'])]);
    	//-->Дата окончание
    	$max=$data['dateIn']+100;
    	$dateOut=sm::text(['text'=>'Дата окончание:']);
		$dateOut=$dateOut.sm::p(['content'=>sm::input(['enabled'=>false,'type'=>'number','value'=>$max,'placeholder'=>'Дата окончание'])]);
    	$account=sm::modal(['id'=>'private','title'=>'Конфигурация личной информаций','content'=>sm::form(['method'=>'post','id'=>x::RedirectUpdate(),'action'=>$action,'enctype'=>'multipart/form-data','content'=>$ava.$file.$HTR.$name.$dateIn.$dateOut.$desc.$change.$xprivate])]);
		return	sm::p(['content'=>sm::a(['title'=>sm::ico('cog').'Конфигурация личной информаций','href'=>"#$account",'modal'=>$account])]);
    }
    /**
     * Кэширование
     */
     public function cacheAva(){
    	$data=self::getData();
    	$icomd5=md5_file('./ico/ava');
    	$id=substr($data['id'],0,12);
    	if(md5_file(__DIR__.'/account/anon/'.$data['id'].'/ico/ava')!=$icomd5){
    		//small
    	    x::resizeImg(__DIR__.'/account/anon/'.$data['id'].'/ico/ava',50,50,__DIR__."/cache/small/$id");
    	    //ava
    	    x::resizeImg(__DIR__.'/account/anon/'.$data['id'].'/ico/ava',128,128,__DIR__."/cache/profile/$id");
    	}else{
    		unlink(__DIR__."/cache/$id");
    	}
     }
    /**
     * Кэширование
     */
     public function getCacheSmallAva($id){
    	$data=self::getDataId($id);
    	if(!$data['id']){
    		return x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'./ico/ava');
    	}
    	$id=substr($data['id'],0,12);
    	if(!is_file(__DIR__."/cache/small/$id")){
    		return x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'./ico/ava');
    	}else{
    		return x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR."./cache/small/$id");
    	}
     }
    /**
     * Кэширование
     */
     public function getCacheAva($id){
    	$data=self::getDataId($id);
    	$id=substr($data['id'],0,12);
		if(is_file(__DIR__."/cache/profile/$id")){
			return x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR."./cache/profile/$id");
		}
		return x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'./ico/ava');
     }
    /**
	 * Возвращаем форму (Профиля)
	 * ---------------------------
	 */
	public function getViewAccount($id){
	    if(self::getData()['id']==$id){
	        return 'private';
	    }else{
		    $data=self::getDataId($id);
		    //Дополнительные
		    foreach(x::getModules() as $module){
		    	switch($module){
					case 'rialto':
						$plugins.=rt::getWalletObject($data);
					break;
		    	}
		    }
		    //-->Имя создателя поста
		    $name=sm::text(['text'=>'Имя или ник:']);
		    $name=$name.sm::p(['content'=>sm::input(['value'=>$data['name'],'readonly'=>1])]);
		    //-->ava
		    $ava=sm::img(['src'=>self::getCacheAva($id),'css'=>['width'=>'128px','pointer-events'=>'none','border-radius'=>'100px']]);
			//-->Дата начало
			$min=date('Y')-100;
			$max=$min+100;
			$age=sm::badge(['txt'=>date('Y')-$data['dateIn'].' лет']);
			$dateIn=sm::text(['text'=>"Дата начало $age:"]);
					$dateIn=$dateIn.sm::p(['content'=>sm::input(['enabled'=>false,'max'=>$max,'min'=>$min,'type'=>'number','name'=>'dateIn','value'=>$data['dateIn'],'placeholder'=>'Дата начало'])]);
			//-->Дата окончание
			$min=$data['dateIn']-100;
			$max=$data['dateIn']+100;
			$dateOut=sm::text(['text'=>'Дата окончание:']);
			$dateOut=$dateOut.sm::p(['content'=>sm::input(['enabled'=>false,'max'=>$max,'min'=>$min,'type'=>'number','value'=>$max,'placeholder'=>'Дата окончание'])]);
		    //-->Описание
		    $desc=sm::text(['text'=>'Описание:']);
		    $desc=$desc.sm::p(['content'=>sm::textarea(['value'=>$data['desc'],'readonly'=>1,'css'=>['width'=>'100%','resize'=>'vertical'],'rows'=>4])]);
		    $id=substr($data['id'],0,12);
		    return sm::modal(['id'=>"$id-private",'title'=>$data['name']." $age",'content'=>$ava.$plugins.$name.$dateIn.$dateOut.$desc]);
		}
	}
    /**
     * Возвращаем по id данные пользователя
     * @return array
     */
     public function getData(){
     	$id=$_COOKIE['__XPRIVATE_AUTH'];
		if(!is_file(__DIR__."/account/anon/$id/user.json")){
			return false;
     	}
     	return json_decode(file_get_contents(__DIR__."/account/anon/$id/user.json"),true);
		
     }
    /**
     * Возвращаем по id данные пользователя
     * @return array
     */
     public function getDataId($id){
     	if($id){
     		if(is_file(__DIR__."/account/anon/$id/user.json")){
     			return json_decode(file_get_contents(__DIR__."/account/anon/$id/user.json"),true);
		 	}else{
		 		foreach(scandir(__DIR__."/account/anon/") as $a){
		 			if($a!='.'&&$a!='..'){
		 				$i=substr($a,0,12);
		 				if($i==$id){
		 					return json_decode(file_get_contents(__DIR__."/account/anon/$a/user.json"),true);
		 				}
		 			}
		 		}
		 	}
     	}
return [
	'id'	=>'undefined',
	'name'	=>'Нейзвестный',
	'HTR'	=>0,
	'pass'	=>0,
	'desc'	=>'Ничего не найдено',
	'rules'	=>NULL,
	'date'	=>['y'=>0,'m'=>0,'d'=>0],
	'active'=>['y'=>0,'m'=>0,'d'=>0]
];
     }
    /**
     * Установить значение профиля
     * @return array
     */
     public function setData(array $data){
     	$account=self::getData();
     	$id=$account['id'];
		$data=array_replace($account,$data);
     	file_put_contents(__DIR__."/account/anon/$id/user.json",json_encode($data));
     }
    /**
     * Установить значение профиля указанному
     * @return array
     */
     public function setDataId(array $data,$id){
     	$account=self::getDataId($id);
     	$id=$account['id'];
		$data=array_replace($account,$data);
     	file_put_contents(__DIR__."/account/anon/$id/user.json",json_encode($data));
     }
     /**
      * Добавить правила
      * name-Имя
      * 
      */
	public function addRule($name='private',$opt=[]){
		self::setData(['HTR'=>333]);
    }


	/**
	 * Возвращаем чтр с бирже
	 */
	public function getHTR(){
		return '0.5';
	}
}
