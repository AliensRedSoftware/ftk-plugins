$(document).ready(function(){
    /**
     * Лист изменение темы bootstrap
     */
    $('#theme').change(function(){
        var theme=$('#theme').val();
        $('#theme-label').html(theme);
        switch(theme){
            case 'Светло-белая':
                //utility
                document.querySelector("html").style.backgroundColor='#fff';
                document.querySelector("body").style.backgroundColor='#fff';
                //components
                $('.uk-card-'+getPanelPreview()).removeClass('uk-card-'+getPanelPreview()).toggleClass('uk-card-default');
                $('.uk-button-'+getBtnPreview()).removeClass('uk-button-'+getBtnPreview()).toggleClass('uk-button-default');
                $('.uk-dropdown-'+getDropDownPreview()).removeClass('uk-dropdown-'+getDropDownPreview()).toggleClass('uk-dropdown-default');
                //cookie
                Cookies.set("__SKINMANAGER-uikit3-THEME","Светло-белая");
            break;
            case 'Светло-синия':
                //utility
                document.querySelector("html").style.backgroundColor='#fff';
                document.querySelector("body").style.backgroundColor='#fff';
                //components
                $('.uk-card-'+getPanelPreview()).removeClass('uk-card-'+getPanelPreview()).toggleClass('uk-card-primary');
                $('.uk-button-'+getBtnPreview()).removeClass('uk-button-'+getBtnPreview()).toggleClass('uk-button-primary');
                $('.uk-dropdown-'+getDropDownPreview()).removeClass('uk-dropdown-'+getDropDownPreview()).toggleClass('uk-dropdown-primary');
                //cookie
                Cookies.set("__SKINMANAGER-uikit3-THEME", "Светло-синия");
            break;
            case 'Светло-красная':
                //utility
                document.querySelector("html").style.backgroundColor='#fff';
                document.querySelector("body").style.backgroundColor='#fff';
                //components
                $('.uk-card-'+getPanelPreview()).removeClass('uk-card-'+getPanelPreview()).toggleClass('uk-card-default');
                $('.uk-button-'+getBtnPreview()).removeClass('uk-button-'+getBtnPreview()).toggleClass('uk-button-danger');
                $('.uk-dropdown-'+getDropDownPreview()).removeClass('uk-dropdown-'+getDropDownPreview()).toggleClass('uk-dropdown-danger');
                //cookie
                Cookies.set("__SKINMANAGER-uikit3-THEME", "Светло-красная");
            break;
            case 'Россия':
                //utility
                document.querySelector("html").style.backgroundColor='#fff';
                document.querySelector("body").style.backgroundColor='#fff';
                //components
                $('.uk-card-'+getPanelPreview()).removeClass('uk-card-'+getPanelPreview()).toggleClass('uk-card-primary');
                $('.uk-button-'+getBtnPreview()).removeClass('uk-button-'+getBtnPreview()).toggleClass('uk-button-danger');
                $('.uk-dropdown-'+getDropDownPreview()).removeClass('uk-dropdown-'+getDropDownPreview()).toggleClass('uk-dropdown-primary');
                //cookie
                Cookies.set("__SKINMANAGER-uikit3-THEME", "Россия");
            break;
            case 'Тёмная':
                //utility
                document.querySelector("html").style.backgroundColor='#333333';
                document.querySelector("body").style.backgroundColor='#333333';
                //components
                $('.uk-card-'+getPanelPreview()).removeClass('uk-card-'+getPanelPreview()).toggleClass('uk-card-secondary');
                $('.uk-button-'+getBtnPreview()).removeClass('uk-button-'+getBtnPreview()).toggleClass('uk-button-secondary');
                $('.uk-dropdown-'+getDropDownPreview()).removeClass('uk-dropdown-'+getDropDownPreview()).toggleClass('uk-dropdown-secondary');
                //cookie
                Cookies.set("__SKINMANAGER-uikit3-THEME", "Тёмная");
            break;
            default:
                //utility
                document.querySelector("html").style.backgroundColor='#fff';
                document.querySelector("body").style.backgroundColor='#fff';
                //components
                $('.panel-'+getPanelPreview()).removeClass('panel-'+getPanelPreview()).toggleClass('panel-default');
                $('.btn-'+getBtnPreview()).removeClass('btn-'+getBtnPreview()).toggleClass('btn-default');
                //cookie
                Cookies.set("__SKINMANAGER-uikit3-THEME", "Светло-белая");
            break;
        }
    });
    if(getNameTheme()!=false){
        var selected=getNameTheme();
        $('#theme').val(selected);
    }
    $('#theme').change();//Изменить тему
});
/**
 * Возвращает тему выбранную
 */
function getTheme(){
    if (Cookies.get('__SKINMANAGER_uikit3-THEME')!='undefined'){
        return Cookies.get('__SKINMANAGER-uikit3-THEME');
    } else {
        return false;
    }
}
/**
 * Возвращает имя темы bootstrap
 */
function getNameTheme(){
    switch(getTheme()){
        case 'Светло-синия':
            document.querySelector("html").style.backgroundColor='#dddaec';
            document.querySelector("body").style.backgroundColor='#dddaec';
            return 'Светло-синия';
        break;
        case 'Тёмная':
            document.querySelector("html").style.backgroundColor='#333333';
            document.querySelector("body").style.backgroundColor='#333333';
            return 'Тёмная';
        break;
        case 'Светло-белая':
            document.querySelector("html").style.backgroundColor='#fff';
            document.querySelector("body").style.backgroundColor='#fff';
            return 'Светло-белая';
        break;
        case 'Светло-красная':
            document.querySelector("html").style.backgroundColor='#fff';
            document.querySelector("body").style.backgroundColor='#fff';
            return 'Светло-красная';
        break;
        case 'Россия':
            document.querySelector("html").style.backgroundColor='#fff';
            document.querySelector("body").style.backgroundColor='#fff';
            return 'Россия';
        break;
        default:
            return false;
        break;
    }
}

/**
 * Возвращает прошлую тему текста
 */
function getTextPreview(){
    let arr=['light','dark','gentoo'];
    var b=[];
    var i=0;
    arr.forEach(function(e){
        i++;
        var elements=document.querySelectorAll('.text-'+e);
        if(elements.length<=0){
            return;
        }
        b.push(e);
        if(arr.length==i){
            arr=b;
        }
    });
    return b[0];
}

/**
 * Возвращает прошлую тему панели
 */
function getPanelPreview(){
    var arr=['default','primary','secondary'];
    var b=[];
    var i=0;
    arr.forEach(e=>{
        i++;
        var elements=document.querySelectorAll('.uk-card-'+e);
        if(elements.length<=0){
            return;
        }
        b.push(e);
        if(arr.length==i){
            arr=b;
        }
    });
    return b[0];
}
/**
 * Возвращает прошлую тему кнопок
 */
function getBtnPreview(){
    var arr=['default','primary','secondary','danger'];
    var b=[];
    var i=0;
    arr.forEach(e=>{
        i++;
        var elements=document.querySelectorAll('.uk-button-'+e);
        if(elements.length<=0){
            return;
        }
        b.push(e);
        if(arr.length==i){
            arr=b;
        }
    });
    return b[0];
}
/**
 * Возвращает прошлую тему кнопок
 */
function getDropDownPreview(){
    var arr=['default','primary','secondary','danger'];
    var b=[];
    var i=0;
    arr.forEach(e=>{
        i++;
        var elements=document.querySelectorAll('.uk-dropdown-'+e);
        if(elements.length<=0){
            return;
        }
        b.push(e);
        if(arr.length==i){
            arr=b;
        }
    });
    return b[0];
}
/**
 * Возвращает прошлую тему метки
 */
function getBadgePreview(){
    var arr=['default','gentoo'];
    var b=[];
    var i=0;
    arr.forEach(e=>{
        i++;
        var elements=document.querySelectorAll('.badge-'+e);
        if(elements.length<=0){
            return;
        }
        b.push(e);
        if(arr.length==i){
            arr=b;
        }
    });
    return b[0];
}
