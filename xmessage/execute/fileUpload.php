<?php
use xlib as x;
use skinmanager as sm;
use xmessage as xm;
class fileUpload{
    /**
     * Выполнение
     * id-Нить ид
     * opt-Опция (replace-Замена новых файлов,md5X-Запрет повторных с md5-хеш)
     */
    public function execute($id,$opt=[]){
    	$files=[];
    	if(empty($_FILES['upload']['type'][0])){return false;}
		//Отправка файлов
		$i=-1;
		if(count($_FILES['upload']['type'])<20){
			foreach($_FILES as $file){
				//Размер
				foreach($file['size'] as $size){
					if($size>2000048){
					    x::LoadWebUrl();
						sm::modal(['open'=>true,'title'=>'Отправка файлов','content'=>"Невозможно загрузить с размером больше ".$size]);
						die();
					}elseif($size==0){
						x::LoadWebUrl();
						sm::modal(['open'=>true,'title'=>'Отправка файлов','content'=>'Невозможно загрузить с размером меньше 0кб или больше 2мб']);
						die();
					}
				}
				//Расширение
				foreach($file['type'] as $type){
					$i++;
					if($type=='image/jpg'||$type=='image/jpeg'||$type=='image/png'||$type=='image/gif'){
						$name=$file["name"][$i];
						//Загрузка
						$path=xm::getUploadFile($_POST['dot'].$id);
						//checkName
						foreach(scandir($_SERVER['DOCUMENT_ROOT'].$path) as $f){
							if($f!='.'&&$f!='..'){
								//md5X-Запрет повторных с md5-хеш
								if(!$opt['md5X']){
									if(md5_file($file['tmp_name'][$i])==md5_file($_SERVER['DOCUMENT_ROOT'].$path.$f)){
										x::LoadWebUrl();//Загрузка локальной страницы прошлой
										sm::modal(['open'=>true,'title'=>'Отправка сообщение','content'=>"Повторные файлы нельзя отправить по md5-хэш! </br>уже имеется такой файл ->$f"]);
										die();
									}
								}
								//replace - Замена новых файлов
								if($f==$name){
									if(!$opt['replace']){
										x::LoadWebUrl();
										sm::modal(['open'=>true,'title'=>'Отправка файлов','content'=>"Повторные файлы нельзя отправить по имени! </br>уже имеется такой файл ->$name"]);
										die();
									}else{
										$ext=explode('.',$name);
										$name=x::uuidv4().'.'.$ext[count($ext) - 1];
									}
								}
							}
						}
						move_uploaded_file($file['tmp_name'][$i],$_SERVER['DOCUMENT_ROOT'].$path.$name);
						$files[$file['name'][$i]].=$path.$name;
					}else{
						x::LoadWebUrl();
						sm::modal(['open'=>true,'title'=>'Отправка файлов','content'=>"Невозможно загрузить расширение с $type"]);
						die();
					}
				}
			}
			return $files;
		}else{
			sm::modal(['open'=>true,'title'=>'Отправка файлов','content'=>"Невозможно загрузить более 19 файлов"]);
			die();
		}
    }
}
