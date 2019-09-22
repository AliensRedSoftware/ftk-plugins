<?php

/**
 * Работа с ini
 * v1.0
 */
class ini {

    protected $file;

    function __construct($file) {
        $this->file = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $file . '.ini';
    }

    function __toString() {
        return $this->file;
    }

    /**
     * Записать ini файл
     */
    public function put(array $options){
        $get = file_get_contents($this->file);
        $tmp = $get;
        foreach($options as $section => $values) {
            if ($this->isEmpty($section) == false){
                $tmp .= "[$section]";
            }
            foreach($values as $key => $val){
                if(is_array($val)){
                    foreach($val as $k => $v){
                        $tmp .= "{$key}[$k] = \"$v\"\n";
                    }
                }
                else {
                    if ($this->is_key($section, $key)) {
                        $tmp = $this->isd($section, $key);
                        $tmp .= "$key = \"$val\"\n";
                    } else {
                        $tmp .= "$key = \"$val\"\n";
                    }
                }
            }
            $tmp .= "\n";
        }
        file_put_contents($this->file, $tmp);
    }

    /**
     * Записать ini файл
     */
    public function set ($section, $key, $value){
        $sectionList = $this->getSections();
        if (count($sectionList) == 0) {
            $tmp .= "[$section]\n";
            $tmp .= "$key" . ' = ' . "\"$value\"" . "\n";
            $tmp .= "\n";
        } else {
            foreach ($sectionList as $selectedSection) { 
                $getKeys = $this->getKeys($selectedSection);
                $tmp .= "[$selectedSection]" . "\n";
   
                foreach ($getKeys as $selectedKey) {
                    $val = $this->get($selectedSection, $selectedKey);
                    if ($val != $value && $selectedKey == $key && $selectedSection == $section) {
                        $tmp .= "$selectedKey" . ' = ' . "\"$value\"" . "\n";
                    } else {
                        $tmp .= "$selectedKey" . ' = ' . "\"$val\"" . "\n";
                    }
                }
                if ($this->is_section($section) == false) {
                    $tmp .= "\n[$section]\n";
                    $tmp .= "$key" . ' = ' . "\"$value\"" . "\n";
                } else {
                    if (!$this->is_key($selectedSection, $key)) {
                        if ($section == $selectedSection) {
                            $tmp .= "$key" . ' = ' . "\"$value\"" . "\n";
                        }
                    }
                }
                $tmp .= "\n";
            }
        }
        file_put_contents($this->file, ltrim($tmp));
    }

    /**
     * Возвращает все ключи которые есть в секций без значений
     */
    public function getKeys($section) {
        $list = $this->getSection($section);
        $ls = explode("\n", $list);
        array_shift($ls);
        $tmp = [];
        foreach($ls as $value) {
            $key = explode(' ',$value);
            array_push($tmp, $key[0]);
        }
        return $tmp;
    }

    /**
     * Возвращает все кол-во ключей которые есть в секций без значений
     */
    public function getCountKeys($section) {
        return count($this->getKeys($section));
    }

    /**
     * Возвращает есть ли такой ключ у секций
     */
    public function is_key($section, $key) {
        $abc = $this->getKeys($section); //дцп
        foreach ($abc as $value) {
            if ($key == $value) {
                return true;
            }
        }
    }

    /**
     * Удаление ключа в секций
     */
    public function removeKey ($section, $key) {
        $sectionList = $this->getSections();
        foreach ($sectionList as $selectedSection) { 
            $getKeys = $this->getKeys($selectedSection);
            if (count($getKeys) == 1 && $selectedSection == $section && $getKeys[0] == $key) {

            } else {
                $tmp .= "[$selectedSection]" . "\n";
            }
            foreach ($getKeys as $selectedKey) {
                if ($selectedKey == $key && $selectedSection == $section) {
                    
                } else {
                    $val = $this->get($selectedSection, $selectedKey);
                    $tmp .= "$selectedKey" . ' = ' . "\"$val\"" . "\n";
                }
            }
            $tmp .= "\n";
        }
        file_put_contents($this->file, ltrim($tmp));
    }

    /**
     * добавляет пустую секцию
     */
    public function addSection ($section) {
        $sectionList = $this->getSections();
        if (count($sectionList) == 0) {
            $tmp = "[$section]\n\n";
            file_put_contents($this->file, $tmp);
            die();
        }
        foreach ($sectionList as $selectedSection) { 
            $getKeys = $this->getKeys($selectedSection);
            $tmp .= "[$selectedSection]" . "\n";
            foreach ($getKeys as $selectedKey) {
                $val = $this->get($selectedSection, $selectedKey);
                $tmp .= "$selectedKey" . ' = ' . "\"$val\"" . "\n";
            }
            $tmp .= "\n";
        }
        $tmp .= "\n[$section]\n";
        file_put_contents($this->file, ltrim($tmp));
    }

    /**
     * Удаление ключа у секций
     */
    public function isd($section, $key) {
        $sectionList = $this->getSections();
        foreach ($sectionList as $selectedSection) {
            $tmp .= "[$selectedSection]" . "\n";
            $getKeys = $this->getKeys($selectedSection);
            foreach ($getKeys as $selectedKey) {
                if ($selectedKey == $key && $selectedSection == $section) {

                } else {
                    $val = $this->get($selectedSection, $selectedKey);
                    $tmp .= "$selectedKey" . ' = ' . "\"$val\"" . "\n";
                }
            }
        }
        return ltrim($tmp);
    }
    /**
     * Возвращает значение
     */
    public function get($section, $key) {
        return parse_ini_file($this->file,true)[$section][$key];
    }

    public function li() {
        return file_get_contents('./theme/ls');
    }

    /**
     * Возвращает лист секций
     */
    public function getSection($section) {
        $xlib = new xlib();
        $file = file_get_contents($this->file);
        $get = explode("\n", $file);
        $tmp = null;
        foreach ($get as $val) {
            if ($xlib->startWith('[', $val)) {
                $a = str_replace('[', '', $val);
                $b = str_replace(']', '', $a);
                $selected = $b; //b тип бред
            }
            $str = explode(' ', $val);
            $key = $str[0];
            if ($selected == $section && parse_ini_file($this->file,true)[$section][$key] == true) {
                $tmp .= $val . "\n";
            }
        }
        if (trim($tmp) != null) {
            return "[$section]" . "\n" . trim($tmp);
        } else {
            return "[$section]";
        }
    }

    /**
     * Возвращает все секций в ввиде массива
     */
    public function getSections() {
        $xlib = new xlib();
        $file = file_get_contents($this->file);
        $get = explode("\n", $file);
        $sections = [];
        foreach ($get as $val) {
            if ($xlib->startWith('[', $val)) {
                $a = str_replace('[', '', $val);
                $b = str_replace(']', '', $a);
                array_push($sections, trim($b));
            }
        }
        return $sections;
    }

    /**
     * Возвращает кол-во секций
     */
    public function getCountSections() {
        return count($this->getSections());
    }

    /**
     * Возвращает пустая ли секция или нет
     */
    public function isEmpty($section) {
        if(parse_ini_file($this->file,true)[$section] == null) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Возвращает секцию есть ли она или нет
     */
    public function is_section($section) {
        if(is_array(parse_ini_file($this->file,true)[$section])) {
            return true;
        } else {
            return false;
        }
    }
}
