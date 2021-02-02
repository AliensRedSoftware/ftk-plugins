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
                $('.text-'+getTextPreview()).removeClass('text-'+getTextPreview()).toggleClass('text-dark');
                $('.bg-'+getBgPreview()).removeClass('bg-'+getBgPreview()).toggleClass('bg-light');
                $('.btn-'+getBtnPreview()).removeClass('btn-'+getBtnPreview()).toggleClass('btn-dark');
                $('.badge-'+getBadgePreview()).removeClass('badge-'+getBadgePreview()).toggleClass('badge-dark');
                //cookie
                Cookies.set("__SKINMANAGER-bootstrap4-THEME", "Светло-белая");
            break;
            case 'Светло-синия':
                //utility
                document.querySelector("html").style.backgroundColor='#cce5ff';
                document.querySelector("body").style.backgroundColor='#cce5ff';
                //components
                $('.text-'+getTextPreview()).removeClass('text-'+getTextPreview()).toggleClass('text-light');
                $('.bg-'+getBgPreview()).removeClass('bg-'+getBgPreview()).toggleClass('bg-primary');
                $('.btn-'+getBtnPreview()).removeClass('btn-'+getBtnPreview()).toggleClass('btn-light');
                $('.badge-'+getBadgePreview()).removeClass('badge-'+getBadgePreview()).toggleClass('badge-light');
                //cookie
                Cookies.set("__SKINMANAGER-bootstrap4-THEME", "Светло-синия");
            break;
            case 'Светло-зеленная':
                //utility
                document.querySelector("html").style.backgroundColor='#d2ffbe';
                document.querySelector("body").style.backgroundColor='#d2ffbe';
                //components
                $('.text-'+getTextPreview()).removeClass('text-'+getTextPreview()).toggleClass('text-light');
                $('.bg-'+getBgPreview()).removeClass('bg-'+getBgPreview()).toggleClass('bg-success');
                $('.btn-'+getBtnPreview()).removeClass('btn-'+getBtnPreview()).toggleClass('btn-light');
                $('.badge-'+getBadgePreview()).removeClass('badge-'+getBadgePreview()).toggleClass('badge-light');
                //cookie
                Cookies.set("__SKINMANAGER-bootstrap4-THEME", "Светло-зеленная");
            break;
            case 'Светло-голубая':
                //utility
                document.querySelector("html").style.backgroundColor='#b1f4ff';
                document.querySelector("body").style.backgroundColor='#b1f4ff';
                //components
                $('.text-'+getTextPreview()).removeClass('text-'+getTextPreview()).toggleClass('text-light');
                $('.bg-'+getBgPreview()).removeClass('bg-'+getBgPreview()).toggleClass('bg-info');
                $('.btn-'+getBtnPreview()).removeClass('btn-'+getBtnPreview()).toggleClass('btn-light');
                $('.badge-'+getBadgePreview()).removeClass('badge-'+getBadgePreview()).toggleClass('badge-light');
                //cookie
                Cookies.set("__SKINMANAGER-bootstrap4-THEME", "Светло-голубая");
            break;
            case 'Светло-желтая':
                //utility
                document.querySelector("html").style.backgroundColor='#ffffc8';
                document.querySelector("body").style.backgroundColor='#ffffc8';
                //components
                $('.text-'+getTextPreview()).removeClass('text-'+getTextPreview()).toggleClass('text-dark');
                $('.bg-'+getBgPreview()).removeClass('bg-'+getBgPreview()).toggleClass('bg-warning');
                $('.btn-'+getBtnPreview()).removeClass('btn-'+getBtnPreview()).toggleClass('btn-dark');
                $('.badge-'+getBadgePreview()).removeClass('badge-'+getBadgePreview()).toggleClass('badge-dark');
                //cookie
                Cookies.set("__SKINMANAGER-bootstrap4-THEME", "Светло-желтая");
            break;
            case 'Светло-красная':
                 //utility
                document.querySelector("html").style.backgroundColor='#ffc8c8';
                document.querySelector("body").style.backgroundColor='#ffc8c8';
                //components
                $('.text-'+getTextPreview()).removeClass('text-'+getTextPreview()).toggleClass('text-light');
                $('.bg-'+getBgPreview()).removeClass('bg-'+getBgPreview()).toggleClass('bg-danger');
                $('.btn-'+getBtnPreview()).removeClass('btn-'+getBtnPreview()).toggleClass('btn-light');
                $('.badge-'+getBadgePreview()).removeClass('badge-'+getBadgePreview()).toggleClass('badge-light');
                //cookie
                Cookies.set("__SKINMANAGER-bootstrap4-THEME", "Светло-красная");
            break;
            case 'Тёмная':
                //utility
                document.querySelector("html").style.backgroundColor='#333333';
                document.querySelector("body").style.backgroundColor='#333333';
                //components
                $('.text-'+getTextPreview()).removeClass('text-'+getTextPreview()).toggleClass('text-light');
                $('.bg-'+getBgPreview()).removeClass('bg-'+getBgPreview()).toggleClass('bg-dark');
                $('.btn-'+getBtnPreview()).removeClass('btn-'+getBtnPreview()).toggleClass('btn-dark');
                $('.badge-'+getBadgePreview()).removeClass('badge-'+getBadgePreview()).toggleClass('badge-light');
                //cookie
                Cookies.set("__SKINMANAGER-bootstrap4-THEME", "Тёмная");
            break;
            case 'gentoo':
                //utility
                document.querySelector("html").style.backgroundColor='#dddaec';
                document.querySelector("body").style.backgroundColor='#dddaec';
                //components
                $('.text-'+getTextPreview()).removeClass('text-'+getTextPreview()).toggleClass('text-gentoo');
                $('.bg-'+getBgPreview()).removeClass('bg-'+getBgPreview()).toggleClass('bg-gentoo');
                $('.btn-'+getBtnPreview()).removeClass('btn-'+getBtnPreview()).toggleClass('btn-gentoo');
                $('.badge-'+getBadgePreview()).removeClass('badge-'+getBadgePreview()).toggleClass('badge-gentoo');
                //cookie
                Cookies.set("__SKINMANAGER-bootstrap4-THEME", "gentoo");
            break;
            default:
                //utility
                document.querySelector("html").style.backgroundColor='#fff';
                document.querySelector("body").style.backgroundColor='#fff';
                //components
                $('.text-'+getTextPreview()).removeClass('text-'+getTextPreview()).toggleClass('text-dark');
                $('.bg-'+getBgPreview()).removeClass('bg-'+getBgPreview()).toggleClass('bg-light');
                $('.btn-'+getBtnPreview()).removeClass('btn-'+getBtnPreview()).toggleClass('btn-dark');
                $('.badge-'+getBadgePreview()).removeClass('badge-'+getBadgePreview()).toggleClass('badge-dark');
                //cookie
                Cookies.set("__SKINMANAGER-bootstrap4-THEME", "Светло-белая");
            break;
        }
    });
    if(getNameThemeBootstrap()!=false){
        var selected=getNameThemeBootstrap();
        $('#theme').val(selected);
    }
    $('#theme').change();//Изменить тему
});
/**
 * Возвращает тему выбранную bootstrap
 */
function getThemeBootstrap(){
    if (Cookies.get('__SKINMANAGER-bootstrap4-THEME')!='undefined'){
        return Cookies.get('__SKINMANAGER-bootstrap4-THEME');
    } else {
        return false;
    }
}
/**
 * Возвращает имя темы bootstrap
 */
function getNameThemeBootstrap(){
    switch(getThemeBootstrap()){
	    case 'Светло-белая':
	        document.querySelector("html").style.backgroundColor='#fff';
	        document.querySelector("body").style.backgroundColor='#fff';
            return 'Светло-белая';
        break;
        case 'Светло-синия':
            document.querySelector("html").style.backgroundColor='#fff';
            document.querySelector("body").style.backgroundColor='#fff';
            return 'Светло-синия';
        break;
        case 'Светло-зеленная':
            document.querySelector("html").style.backgroundColor='#fff';
            document.querySelector("body").style.backgroundColor='#fff';
            return 'Светло-зеленная';
        break;
        case 'Светло-голубая':
            document.querySelector("html").style.backgroundColor='#fff';
            document.querySelector("body").style.backgroundColor='#fff';
            return 'Светло-голубая';
        break;
        case 'Светло-желтая':
            document.querySelector("html").style.backgroundColor='#fff';
            document.querySelector("body").style.backgroundColor='#fff';
            return 'Светло-желтая';
        break;
        case 'Светло-красная':
            document.querySelector("html").style.backgroundColor='#fff';
            document.querySelector("body").style.backgroundColor='#fff';
            return 'Светло-красная';
        break;
        case 'Тёмная':
            document.querySelector("html").style.backgroundColor='#333333';
            document.querySelector("body").style.backgroundColor='#333333';
            return 'Тёмная';
        break;
        case 'gentoo':
            document.querySelector("html").style.backgroundColor='#dddaec';
            document.querySelector("body").style.backgroundColor='#dddaec';
            return 'gentoo';
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
function getBgPreview(){
    var arr=['light','dark','primary','success','info','warning','danger','gentoo'];
    var b=[];
    var i=0;
    arr.forEach(e=>{
        i++;
        var elements=document.querySelectorAll('.bg-'+e);
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
    var arr=['light','dark','gentoo'];
    var b=[];
    var i=0;
    arr.forEach(e=>{
        i++;
        var elements=document.querySelectorAll('.btn-'+e);
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
    var arr=['light','dark','gentoo'];
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
