<?php
use xlib as x;
use xprivate as xp;
use skinmanager as sm;
use xmessage as xm;
/**
 * Умный загрузчик файлов v1.0
 */
class fileUploads{

    public static $newThread=false;

    /**
     * Выполнение
     * id-Нить ид
     * opt-Опция (replace-Замена повторных файлов,md5X-Запрет повторных с md5-хеш)
     */
    public function execute($id,$opt=[]){
    	$files=[];
    	if(empty($_FILES['upload']['type'][0])){return false;}
		//Отправка файлов
		$logo=sm::p(['content'=>sm::img(['src'=>x::getPathModules('xmessage'.DIRECTORY_SEPARATOR.'ico'.DIRECTORY_SEPARATOR.'fail.webp'),'css'=>['width'=>'256px','pointer-events'=>'none']])]);
		$i=-1;
		$count=ini_get('max_file_uploads') - 1;
		if(count($_FILES['upload']['type'])<$count){
			foreach($_FILES as $file){
				//Размер
				$max=ini_get('upload_max_filesize');
				foreach($file['size'] as $size){
					if($size > $max * 1024 * 1024){
						$size=$size / 1024 / 1024 . 'M';
					    x::LoadWebUrl();
						sm::modal(['open'=>true,'title'=>'Отправка файлов','content'=>"$logo Невозможно загрузить с размером больше <b>$size</b>"]);
						die();
					}elseif($size<=0){
						x::LoadWebUrl();
						sm::modal(['open'=>true,'title'=>'Отправка файлов','content'=>"$logo Невозможно загрузить с размером меньше 0кб или больше '<b>$max</b>'"]);
						die();
					}
				}
				//Расширение
				foreach($file['type'] as $type){
					$i++;
					if(xm::is_Extension($type)||isset(self::$newThread)||xp::$data['root']){
						$name=$file['name'][$i];
						//Загрузка
						$path=xm::getUploadFile($_POST['dot'].$id);
						//checkName
						foreach(x::scandir($_SERVER['DOCUMENT_ROOT'].$path) as $f){
							//md5X-Запрет повторных с md5-хеш
							if(!$opt['md5X']){
								if(md5_file($file['tmp_name'][$i])==md5_file($_SERVER['DOCUMENT_ROOT'].$path.$f)){
									x::LoadWebUrl();//Загрузка локальной страницы прошлой
									sm::modal(['open'=>true,'title'=>'Отправка сообщение','content'=>$logo.'Повторные файлы нельзя отправить по md5-хэш! </br>уже имеется такой файл']);
									die();
								}
							}
							//replace - Замена новых файлов
							if($f==$name){
								if(!$opt['replace']){
									x::LoadWebUrl();
									sm::modal(['open'=>true,'title'=>'Отправка файлов','content'=>$$logo.'Повторные файлы нельзя отправить по имени! </br>уже имеется такой файл']);
									die();
								}else{
									$ext=explode('.',$name);
									$name=x::uuidv4().'.'.$ext[count($ext) - 1];
								}
							}
						}
						move_uploaded_file($file['tmp_name'][$i],$_SERVER['DOCUMENT_ROOT'].$path.$name);
						$files[$file['name'][$i]].=$path.$name;
					}else{
						x::LoadWebUrl();
						sm::modal(['open'=>true,'title'=>'Отправка файлов','content'=>"$logo Невозможно загрузить расширение с '<b>$type</b>'"]);
						die();
					}
				}
				//Write process...
				
			}
			return $files;
		}else{
			sm::modal(['open'=>true,'title'=>'Отправка файлов','content'=>"$logo Невозможно загрузить более '<b>$count</b>' файлов"]);
			die();
		}
    }
}
