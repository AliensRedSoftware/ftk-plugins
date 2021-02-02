<?php

/**
 * Яндекс модуль
 * 0.30v
 */
use xlib as x;
use skinmanager as sm;
use jquery as jq;
class atei{
    /**
	 * Возвращаем версию
	 * ------------------
	 * @return string
	 */
	public function getVersion () {
		return' ('.__CLASS__.' '.sm::badge(['txt'=>'0.35']).')';
	}
    /**
     * Возвращает форму доната
     */
    public function getDonate(){
        $ya=self::getDonateYandex();
        //-->img
        $img=sm::p(['content'=>sm::img(['src'=>x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR."ico/donate"),'css'=>['width'=>'128px','pointer-events'=>'none']])]);
        //-->donate
	    $ya=sm::p(['content'=>sm::a(['title'=>sm::ico('heart').' '.'Яндекс.Деньги','href'=>"#$ya",'modal'=>$ya])]);
	    //-->Возвращение
		$return=sm::p(['content'=>sm::a(['title'=>sm::ico('heart').' '.'Вернуться в храм атей','href'=>"#atei",'modal'=>'atei'])]);
        return sm::modal(['id'=>'donate','title'=>sm::ico('heart').' '.'Способы искупление вины'.self::getVersion(),'content'=>$ya.$return.$img]);
    }
    /**
     * Возвращает форму доната
     * account-Номер кошелька
     * text-Назначение перевода
     * pay-стандартная сумма перевода
     * width-Ширина формы
     * height-Высота формы
     */
    public function getDonateYandex(array $options=['account','title','pay','comment','content']){
        $optionsOLD=[
            'account'=>410018314785030,
            'title'=>"Донат",
            'pay'=>10,
            'comment'=>'Хотелось бы дистанционного управления.',
            'content'=>null
        ];
        $account=$options['account'];
        $title=$options['title'];
        $pay=$options['pay'];
        $comment=$options['comment'];
        $content=$options['content'];
        if($account==null){
            $account=$optionsOLD['account'];
        }
        if($title==null){
            $title=$optionsOLD['title'];
        }
        if($pay==null){
            $pay=$optionsOLD['pay'];
        }
        if($comment==null){
            $comment=$optionsOLD['comment'];
        }
        if($width==null){
            $width=$optionsOLD['width'];
        }
        if($height==null){
            $height=$optionsOLD['height'];
        }
        if($content==null){
            $content=$optionsOLD['content'];
        }
        //-->img
        $img=sm::p(['content'=>sm::img(['src'=>x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR."ico/donate"),'css'=>['width'=>'128px','pointer-events'=>'none']])]);
        //if(x::isJs()){
            $content="<iframe src=\"https://promo-money.ru/quickpay/shop-widget?targets=&targets-hint=&default-sum=300&button-text=13&payment-type-choice=on&mobile-payment-type-choice=on&hint=&successURL=&quickpay=shop&account=$account\" width='100%' frameborder=\"0\" allowtransparency=\"true\" scrolling=\"no\"></iframe>$img";
        //}else{
            $account=sm::input(['name'=>'receiver','value'=>$account,'type'=>'hidden']);
            $quickform=sm::input(['name'=>'quickpay-form','value'=>'donate','type'=>'hidden']);
            $title=sm::input(['name'=>'targets','value'=>$title,'type'=>'hidden']);
        $sum=sm::p(['content'=>sm::input(['name'=>'sum','width'=>'100%','value'=>$pay,'type'=>'number','placeholder'=>300,'min'=>10,'max'=>1000000000,'required'=>true])]);
            $ya=sm::p(['content'=>sm::input(['name'=>'paymentType','type'=>'radio','value'=>'Яндекс.Деньгами'])]);
            $bank=sm::p(['content'=>sm::input(['name'=>'paymentType','type'=>'radio','value'=>'Банковской картой','checked'=>true])]);
            $send=sm::p(['content'=>sm::input(['type'=>'submit','value'=>'Искупить вину'])]);
            $desc=sm::text(['text'=>'Внимание:пожертвование на слим спейсе вы повышаете себе карму!']);
            $content="<form method=\"POST\" action=\"https://money.yandex.ru/quickpay/confirm.xml\">$account$quickform$title$sum$ya$bank$send$desc$img</form>";
        //}
        //-->donate
		$donate=sm::p(['content'=>sm::a(['title'=>sm::ico('heart').' '.'Выбрать другой способ искупление вины','href'=>"#donate",'modal'=>'donate'])]);
		//-->Храм атей
		$atei=sm::p(['content'=>sm::a(['title'=>sm::ico('heart').' '.'Вернуться в храм атей','href'=>"#atei",'modal'=>'atei'])]);
		$content.=$donate;
		$content.=$atei;
        return sm::modal(['title'=>sm::ico('heart').' '.'Искупление вины'.self::getVersion(),'content'=>$content]);
    }
    /**
     * Возвращаем Конфигурация храма
     */
     public function getSettings(){
        return sm::modal(['id'=>'atei','title'=>sm::ico('heart').' '.'Храм атей'.self::getVersion(),'content'=>self::menuObject()]);
     }
    /**
     * Возвращаем меню храма в виде объекта
     * @return object
     */
     public function menuObject(){
        $donate=self::getDonate();
        $rule=self::getRule();
        //-->donate
	    $donate=sm::p(['content'=>sm::a(['title'=>sm::ico('heart').' '.'Искупить вину','href'=>"#$donate",'modal'=>$donate])]);
	    //-->rule
		$rule=sm::p(['content'=>sm::a(['title'=>sm::ico('heart').' '.'Общие правила','href'=>"#$rule",'modal'=>$rule])]);
		//-->logo
    	$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR."ico/logo"),'css'=>['width'=>'128px','pointer-events'=>'none']])]);
		return $donate.$rule.$logo;
     }
    /**
     * Правила сайта
     * @return string
     */
    public function getRule(){
        $atei=sm::a(['title'=>sm::ico('heart').' '.'Храм атей','href'=>"#atei",'modal'=>'atei']);
        $return=sm::a(['title'=>'Вернуться','href'=>"#atei",'modal'=>'atei']);
       	$rule="
Общие правила поведения на сайте:
</br>
1.Начнем с того, что на сайте общаются сотни людей, разных религий и взглядов, и все они являются полноправными посетителями нашего сайта, поэтому если мы хотим чтобы это сообщество людей функционировало нам и необходимы правила. Мы настоятельно рекомендуем прочитать настоящие правила, это займет у вас всего минут пять, но сбережет нам и вам время и поможет сделать сайт более интересным и организованным.
</br>
2.Начнем с того, что на нашем сайте нужно вести себя уважительно ко всем посетителям сайта. Не надо оскорблений по отношению к участникам, это всегда лишнее. Если есть претензии - обращайтесь к Админам или Модераторам (воспользуйтесь личными сообщениями). Оскорбление других посетителей считается у нас одним из самых тяжких нарушений и строго наказывается администрацией. У нас строго запрещен расизм, религиозные и политические высказывания. Заранее благодарим вас за понимание и за желание сделать наш сайт более вежливым и дружелюбным.
</br>
3.На сайте строго запрещено:</br>
- сообщения, не относящиеся к содержанию статьи или к контексту обсуждения</br>
- оскорбление и угрозы в адрес посетителей нашего сайта</br>
- в комментариях запрещаются выражения, содержащие ненормативную лексику, унижающие человеческое достоинство, разжигающие межнациональную рознь</br>
- спам, а также реклама любых товаров и услуг, иных ресурсов, СМИ или событий, не относящихся к контексту обсуждения статьи
</br>
4.Давайте будем уважать друг друга и наш сайт, на который Вы и другие читатели приходят пообщаться и высказать свои мысли. Администрация сайта оставляет за собой право удалять комментарии или часть комментариев, если они не соответствуют данным требованиям.
</br>
5.При нарушении правил вам может быть дано предупреждение. В некоторых случаях может быть дан бан без предупреждений.</br> По вопросам снятия бана писать администратору.
</br>
6.Оскорбление администраторов или модераторов также караются баном - уважайте чужой труд.</br>
7.По возможности посещать и искупать свою карму в $atei";
    		//-->logo
    		$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules(__CLASS__.DIRECTORY_SEPARATOR."ico/rule"),'css'=>['width'=>'128px','pointer-events'=>'none']])]);
    		//-->rule
       		$rule=sm::text(['text'=>$rule]);
       		$rule=sm::modal(['title'=>'Общие правила нашего сайта','content'=>$rule.$logo.$return]);
       		return $rule;
       }
}
