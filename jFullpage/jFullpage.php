<?php
use xlib as x;
use jquery as jq;
use skinmanager as sm;
class jFullpage{
    function execute(){
        x::add_css(['fullpage.min.css'],'./css');
        x::add_js(['fullpage.min.js'],'./js');
    }
    function footerExecute(){
        $opt=$GLOBALS['FULLPAGE_OPT'];
        jq::AddLoad("new fullpage('#fullpage',{ $opt });");
    }
    /**
     * Установить настройки
     */
    function opt($opt=[]){
        foreach($opt as $val){
            $GLOBALS['FULLPAGE_OPT'].=$val.',';
        }
    }
    /**
     * Возвращает сетку fullpage
     */
    function layout($item=[],$opt=['navigation:true','parallax:true','sectionsColor:["#F06292"]']){
        if(!$item){
            $item=self::item('Привет 1',1);
            $item=self::item('Привет 2',0,$item);
        }else{
            if(count($item)>1){
                foreach($item as $title=>$content){
                    if($title){
                        $tooltip.="'$title',";
                    }else{
                        $tooltip.="'',";
                    }
                    $item=self::item($content,0,$item);
                }
                $tooltip=substr($tooltip,0,-1);
                self::opt(["navigationTooltips:[$tooltip]"]);
            }else{
                foreach($item as $title=>$content){
                    self::opt(["navigationTooltips:['$title']"]);
                    $item=self::item($content,1);
                }
            }
        }
        //Эффекты
        self::opt($opt);
        $content=x::div(['id'=>'fullpage','class'=>'fullpage-wrapper','content'=>$item['item']]);
        return $content;
    }
    /**
     * Ячейки
     * content-Контент
     * active-Состояние
     * itemNew-Номер ячейке
     */
    function item($content='Привет',$active=false,$itemNew=0){
        if($active){
            $active='fp-completely active';
        }else{
            $active=NULL;
        }
        if($itemNew){
            $count=$itemNew['count']+1;
            $item=$itemNew['item'];
        }else{
            $count=1;
        }
        return ['count'=>$count,'item'=>$item.x::div(['id'=>'section-'.$count,'class'=>"section fp-table fp-section $active",'content'=>$content])];
    }
    /**
     * Пример готовый страницы середина
     */
    function gCenter($contents=['a1'],$css=[]){
        $i=0;
        foreach($contents as $cnx){
            $content.=x::div(['css'=>['display'=>'flex','align-items'=>'center','position'=>'relative'],'content'=>x::div(['css'=>$css[$i],'content'=>$cnx])]);
            $i++;
        }
        $desc=$content;
		$table=x::div(['content'=>$desc,'css'=>['color'=>'white','display'=>'flex',
'height'=>'100%','justify-content'=>'center']]);
        return $table;
    }
    /**
     * Возвращаем маленький текст
     */
     public function TxtSmall($txt='Уютные кинозалы от 2х до 6 человек, новинки кино, напитки и еда'){
         return sm::p(['content'=>"$txt",'css'=>['margin'=>0]]);
     }
    /**
     * Возвращаем Большой текст
     */
     public function TxtBig($txt='Частный кинотеатр'){
         return "<h2 style=\"margin:0;color:white;\">$txt</h2>";
     }
     /**
      * Возвращаем коробка с описание
      * ico-Иконка
      * title-Загаловок
      * desc-Описание
      * color-Цвет рамки
      * smooth-Сглаживание
      */
      function box($opt){
        $ico=$opt['ico'];
        $title=$opt['title'];
        $desc=$opt['desc'];
        $color=$opt['color'];
        $smooth=$opt['smooth'];
        if(x::startWith('.',$ico)){
            $ico=substr($ico,2);
            if($smooth){
                $ico=sm::img(['src'=>x::getPathModules(__CLASS__."/$ico"),'css'=>['pointer-events'=>'none','border-radius'=>'30px 30px 0px 0px']]);
            }else{
                $ico=sm::img(['src'=>x::getPathModules(__CLASS__."/$ico"),'css'=>['pointer-events'=>'none']]);
            }
        }else{
            if($ico){
                $ico=sm::img(['src'=>x::getPathModules("../ico/$ico"),'css'=>['pointer-events'=>'none']]);
            }else{
                $ico='';
            }
        }
        $title=$ico."<h3 style=\"margin:0;text-align:center;\">$title</h3>";
        $desc=x::div(['content'=>$desc,'css'=>['padding'=>'5px']]);
        $css=['margin'=>'10px','background'=>"$color",'padding'=>'0px','border-radius'=>'30px'];
        if(!$color){
            unset($css['background']);
        }
        if(!$smooth){
            unset($css['border-radius']);
        }
        $content=sm::border(['content'=>$title.$desc,'css'=>$css]);
        return $content;
      }
}
