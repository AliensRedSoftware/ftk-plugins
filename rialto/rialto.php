<?php
/**
 * Работа с биржей
 */
use xlib as x;
use xprivate as xp;
use skinmanager as sm;
class rialto{

	/**
	 * Возвращаем конфигурация бирже
	 * @return string
	 */
	public function getCfgRialto(){
		$data=xp::$data;
		foreach(self::getWallets() as $wallet){
			$about=self::getAbout($wallet);
			$change=self::getChangeWallet($wallet);
		    $cfg=sm::a(['title'=>'Изменить','modal'=>$change,'href'=>"#$change"]);
			$HTR=sm::txt(['txt'=>'Баланс кошелька:'.sm::badge(['txt'=>$wallet['name']])]);
			$wallets.=$HTR.sm::p(['content'=>sm::input(['value'=>$data['rialto'][$wallet['name']]['value'],'type'=>'number','step'=>'0.001','readonly'=>1])." $cfg $about"]);
		}
		$logo=sm::p(['content'=>sm::img(['css'=>['width'=>'200px','pointer-events'=>'none','border-radius'=>'100px'],'src'=>x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'logo.png')])]);
		$return=sm::a(['title'=>'Вернуться','href'=>'#xprivate','modal'=>'xprivate']);
		return sm::modal(['id'=>'cfgRialto','title'=>'Конфигурация rialto','content'=>$logo.$wallets.$return]);
	}
    /**
	 * Возвращаем об кошелке инфу
	 * wallet-Кошелек
	 */
    public function getAbout($wallet){
		$desc=sm::txt(['txt'=>$wallet['desc']]);
		$return=sm::p(['content'=>sm::a(['title'=>'Вернуться','modal'=>'cfgRialto','href'=>'#cfgRialto'])]);
		sm::modal(['id'=>'aboutRialto','title'=>'ЧИВО '.sm::badge(['txt'=>$wallet['name']]),'content'=>$desc.$return]);
		return sm::a(['title'=>'ЧИВО','modal'=>'aboutRialto','href'=>'#aboutRialto']);
    }
	/**
	 * Возвращаем форму для изменение конфигурация баланса
	 * wallet-Кошелек
	 */
	public function getChangeWallet($wallet){
		//Формы
		$buy=self::getBuy($wallet);
		//Конфигурация
		$badge=sm::badge(['txt'=>$wallet['name']]);
		$OUT=sm::p(['content'=>sm::a(['title'=>'Вывести'])]);
		$history=sm::p(['content'=>sm::a(['title'=>'История'])]);
		$return=sm::p(['content'=>sm::a(['title'=>'Вернуться','modal'=>'cfgRialto','href'=>'#cfgRialto'])]);
		return sm::modal(['id'=>'cfg'.$wallet['name'],'title'=>"Конфигурация кошелька $badge",'content'=>$buy.$OUT.$history.$return]);
	}
	/**
	 * Возвращаем форму для пополнение баланса кошелька
	 * wallet-Кошелек
	 */
	public function getBuy($wallet){
		$name=$wallet['name'];
		$i=0;
		//-->Банковская карта
		if($wallet['buyCard']){
			$i++;
			//-->Банковская карта
			$card=self::getBuyCard($wallet);
		}
		$return=sm::p(['content'=>sm::a(['title'=>'Вернуться','modal'=>"cfg$name",'href'=>"#cfg$name"])]);
		$modal=sm::modal(['id'=>"$name-Buy",'title'=>'Изменение личного баланса','content'=>$card.$return]);
		if($i>0){
			return sm::p(['content'=>sm::a(['title'=>"Пополнить $badge",'modal'=>$modal,'href'=>"#$modal"])]);
		}
	}
	/**
	 * Возвращаем форму для пополнение баланса
	 * wallet-Кошелек
	 */
	public function getBuyCard($wallet){
		$name=sm::badge(['txt'=>$wallet['name']]);
		$action=x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'execute'.DIRECTORY_SEPARATOR.'buyCard.php');
		$desc=sm::txt(['txt'=>'Внимание: пополнение кошелька возможно только 3 раза в 1 день']);
		$logo=sm::img(['src'=>x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR."ico/card"),'css'=>['width'=>'320px','pointer-events'=>'none']]);
		$security=x::div(['content'=>'100% безопасная оплата с ']).sm::img(['src'=>x::getPathModules(__CLASS__.'/ico/security'),'css'=>['width'=>'200px',
'pointer-events'=>'none']]);
		//Номер карты
		$c1=sm::input(['placeholder'=>'0000','type'=>'tel','name'=>'c1','size'=>4,'max'=>4,'min'=>4,'pattern'=>'[0-9]{4}','value'=>$_POST['c1'],'required'=>true]);
		$c2='-'.sm::input(['placeholder'=>'0000','type'=>'tel','name'=>'c2','size'=>4,'max'=>4,'min'=>4,'pattern'=>'[0-9]{4}','value'=>$_POST['c2'],'required'=>true]);
		$c3='-'.sm::input(['placeholder'=>'0000','type'=>'tel','name'=>'c3','size'=>4,'max'=>4,'min'=>4,'pattern'=>'[0-9]{4}','value'=>$_POST['c3'],'required'=>true]);
		$c4='-'.sm::input(['placeholder'=>'0000','type'=>'tel','name'=>'c4','size'=>4,'max'=>4,'min'=>4,'pattern'=>'[0-9]{4}','value'=>$_POST['c4'],'required'=>true]);
		$c5='-'.sm::input(['placeholder'=>'000','type'=>'tel','name'=>'c5','size'=>3,'max'=>3,'min'=>3,'value'=>$_POST['c5'],'pattern'=>'[0-9]{3}']);
		$card=sm::p(['content'=>'Номер карты:','css'=>['margin'=>'0','text-align'=>'left']]).$c1.$c2.$c3.$c4.$c5;
		$cvv=sm::p(['content'=>'CVV/CVC:','css'=>['margin'=>'0','text-align'=>'left']]).sm::input(['placeholder'=>'000','type'=>'password','value'=>$_POST['cvv'],'name'=>'cvv','size'=>3,'max'=>3,'min'=>3,'pattern'=>'[0-9]{3}','required'=>true]);
		//Месяц
		$month=[];
		$month+=['01'=>[]];
		$month+=['02'=>[]];
		$month+=['03'=>[]];
		$month+=['04'=>[]];
		$month+=['05'=>[]];
		$month+=['06'=>[]];
		$month+=['07'=>[]];
		$month+=['08'=>[]];
		$month+=['09'=>[]];
		$month+=['10'=>[]];
		$month+=['11'=>[]];
		$month+=['12'=>[]];
		if(!$_POST['month']){
			$_POST['month']=array_keys($month)[rand(0,count($month)-1)];
		}
		$month=sm::combobox(['name'=>'month','selected'=>$_POST['month'],$month]);
		//ГОД
		$year=[];
		for($i=-1;$i<=10;$i++){
			$year+=[substr(date('Y')+$i,2)=>[]];
		}
		if(!$_POST['year']){
			$_POST['year']=array_keys($year)[rand(0,count($year)-1)];
		}
		$year=sm::combobox(['name'=>'year','selected'=>$_POST['year'],$year]);
		$valid=sm::p(['content'=>'Срок действия:','css'=>['margin'=>'0','text-align'=>'left']]).$month.'/'.$year;
		//-->Выбранный кошелек
		$SelectedWallet=sm::p(['content'=>"Сумма дополнение $name"]).sm::input(['step'=>$wallet['step'],'min'=>$wallet['minCard'],'max'=>$wallet['maxCard'],'name'=>$wallet['name'],'value'=>$wallet['minCard'],'type'=>'number']).sm::input(['name'=>'wallet','type'=>'hidden','value'=>$wallet['name']]);
		//-->Выполнение
		$submit=sm::p(['content'=>sm::input(['type'=>'submit'])]);
		//-->Защита сессий
		$key=x::generateSession();
		//-->Форма
		$buy=sm::panel(['title'=>"Новое пополнение кошелька $name",'content'=>sm::form(['method'=>'post','id'=>x::RedirectUpdate(),'action'=>$action,'content'=>$key.$card.$cvv.$valid.$SelectedWallet.$security.$submit.$desc])]);
		//-->Вернуться
		$return=sm::p(['content'=>sm::a(['title'=>'Вернуться','modal'=>$wallet['name'].'-Buy','href'=>'#'.$wallet['name'].'-Buy'])]);
		//-->Модальная форма
		$modal=sm::modal(['id'=>$wallet['name'].'-BuyCard','title'=>'Изменение личного баланса при помощи банковской картой','content'=>$logo.$buy.$return]);
		return sm::p(['content'=>sm::a(['title'=>'Банковская карта','modal'=>$modal,'href'=>"#$modal"])]);
	}
	/**
     * Возвратить объект валюты ввиде объекта
     * --
     * data-данные пользователя
     * @return object
     */
	public function getWalletObject($data){
     	foreach(self::getWallets() as $wallet){
     		$change=self::getCfgWalletObject($data,$wallet);//Конфигурация баланса пользователя
     		$cfg=sm::a(['title'=>'Изменить','modal'=>$change,'href'=>"#$change"]);
			$balance=sm::txt(['txt'=>'Баланс кошелька '.sm::badge(['txt'=>$wallet['name']])]);
			$wallets.=$balance.sm::p(['content'=>sm::input(['value'=>$data['rialto'][$wallet['name']]['value'],'type'=>'number','step'=>$wallet['step'],'readonly'=>true])." $cfg"]);
		}
     	return $wallets;
	}
	/**
	 * Возвращаем форму для конфигурация друга баланса
	 * data-Аккаунт
	 * wallet-Кошелек
	 */
	public function getCfgWalletObject($data,$wallet){
		$name=$data['private']['name'];
		$id=substr($data['id'],0,12);
		$balance=sm::txt(['txt'=>'Ваш баланс кошелька '.sm::badge(['txt'=>$wallet['name']])]);
		$balance=$balance.sm::p(['content'=>sm::input(['value'=>$data['rialto'][$wallet['name']]['value'],'type'=>'number','step'=>$wallet['step'],'readonly'=>1])]);
		$SendWallet=self::getSendWalletObject($data,$wallet);
		$BUY=sm::p(['content'=>sm::a(['title'=>'Пополнить '.sm::badge(['txt'=>$wallet['name']])])]);
		$OUT=sm::p(['content'=>sm::a(['title'=>'Вывести '.sm::badge(['txt'=>$wallet['name']])])]);
		$history=sm::p(['content'=>sm::a(['title'=>'История'])]);
		$return=sm::p(['content'=>sm::a(['title'=>'Вернуться','modal'=>"$id-private",'href'=>"#$id-private"])]);
		return sm::modal(['id'=>"$id-cfg".$wallet['name'],'title'=>"Конфигурация баланса пользователя '$name'",'content'=>$balance.$SendWallet.$BUY.$OUT.$SND.$history.$return]);
	}
	/**
	 * Возвращаем форму для передачи валюты другому пользователю
	 * data-Аккаунт
	 * wallet-Кошелек
	 */
	public function getSendWalletObject($data,$wallet){
		$action=x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'execute'.DIRECTORY_SEPARATOR.'sendWallet.php');
		$myData=xp::$data;
		$name=$data['private']['name'];
		$id=substr($data['id'],0,12);
		$idUser=sm::input(['type'=>'hidden','name'=>'id','value'=>$id]);
		$MYHTR=sm::txt(['txt'=>'Баланс кошелька '.sm::badge(['txt'=>'Ваш'])]);
		$MYHTR=$MYHTR.sm::p(['content'=>sm::input(['value'=>$myData['rialto'][$wallet['name']]['value'],'type'=>'number','step'=>'0.001','readonly'=>1])]);
		$logo=sm::p(['content'=>sm::img(['css'=>['width'=>'128px',
'pointer-events'=>'none'],'src'=>x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'repeat')])]);
		$HTR=sm::txt(['txt'=>'Баланс кошелька '.sm::badge(['txt'=>'Друга'])]);
		$HTR=$HTR.sm::p(['content'=>sm::input(['name'=>'balance','type'=>'number','value'=>$wallet['step'],'min'=>$wallet['step'],'max'=>$myData['rialto'][$wallet['name']]['value'],'step'=>$wallet['step']])]);
		$history=sm::p(['content'=>sm::a(['title'=>'История'])]);
		$submit=sm::input(['value'=>'Перевести','type'=>'submit']);
		$return=sm::a(['title'=>'Вернуться','modal'=>"$id-cfg".$wallet['name'],'href'=>"#$id-cfg".$wallet['name']]);
		//Выбранный кошелек
		$selectedWallet=sm::input(['name'=>'wallet','type'=>'hidden','value'=>$wallet['name']]);
		//Форма
		$modal=sm::modal(['id'=>"$id-sendBuy".$wallet['name'],'title'=>"Перевод баланса пользователя '$name'",'content'=>sm::form(['id'=>x::RedirectUpdate(),'content'=>$selectedWallet.$idUser.$MYHTR.$logo.$HTR.$SND.$history.$submit.' '.$return,'method'=>'post','action'=>$action])]);
		return sm::p(['content'=>sm::a(['title'=>'Перевести '.sm::badge(['txt'=>$wallet['name']]),'modal'=>$modal,'href'=>"#$modal"])]);
	}
    /**
     * Возвратить валюты ввиде массива
     * @return array
     */
    public function getWallets(){
    	$arr=[];
    	foreach(x::scandir(__DIR__.DIRECTORY_SEPARATOR.'wallet') as $wallet){
			require_once __DIR__.DIRECTORY_SEPARATOR.'wallet'.DIRECTORY_SEPARATOR.$wallet.DIRECTORY_SEPARATOR."$wallet.php";
			$cfg=call_user_func(array($wallet,'execute'));
			$arr[$wallet]=[
//Информация об виртуальной валюты
				'name'      =>  $cfg['name'],
				'desc'      =>  $cfg['desc'],
				'step'   	=>  $cfg['step'],
//Покупка через пластиковую карту
				'buyCard'   =>  $cfg['buyCard'],
				'minCard'   =>  $cfg['minCard'],
				'maxCard'   =>  $cfg['maxCard'],
			];
    	}
    	return $arr;
	}
	/**
     * Возвращает имена валют ввиде массива
     * @return array
     */
    static function getNameWallets(){
    	$arr=[];
    	foreach(x::scandir(__DIR__.DIRECTORY_SEPARATOR.'wallet') as $wallet){
    		array_push($arr,$wallet);
    	}
    	return $arr;
	}
}
