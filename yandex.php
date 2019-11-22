<?php

/**
 * Яндекс модуль
 * 0.25v
 */
class yandex {
	
    /**
	 * Возвращаем версию
	 * ------------------
	 * @return string
	 */
	public function getVersion () {
		$skinmanager=	new	skinmanager();
		return ' (' . __CLASS__ . ' ' . $skinmanager->badge('0.25') . ')';
	}
	
    /**
     * Возвращает форму доната
     * $account - Номер кошелька
     * $text - Назначение перевода
     * $pay - стандартная сумма перевода
     * $width - Ширина формы
     * $height - Высота формы
     */
    public function donate(array $options = ['account', 'title', 'pay', 'width', 'height', 'comment', 'content']) {
        $optionsOLD = [
            'account' => 410018314785030 ,
            'title' => "Донат" ,
            'pay' => 10 ,
            'comment' => 'Хотелось бы дистанционного управления.',
            'width' => 420 ,
            'height' => 223 ,
            'content' => null
        ];
        $skinmanager = new skinmanager();
        $xlib = new xlib();
        $account = $options['account'];
        $title = $options['title'];
        $pay = $options['pay'];
        $comment = $options['comment'];
        $width = $options['width'];
        $height = $options['height'];
        $content = $options['content'];
        if ($account == null) {
            $account = $optionsOLD['account'];
        }
        if ($title == null) {
            $title = $optionsOLD['title'];
        }
        if ($pay == null) {
            $pay = $optionsOLD['pay'];
        }
        if ($comment == null) {
            $comment = $optionsOLD['comment'];
        }
        if ($width == null) {
            $width = $optionsOLD['width'];
        }
        if ($height == null) {
            $height = $optionsOLD['height'];
        }
        if ($content == null) {
            $content = $optionsOLD['content'];
        }
        //return "<iframe src='https://money.yandex.ru/quickpay/shop-widget?writer=seller&targets=$text&targets-hint=&default-sum=$pay&button-text=11&payment-type-choice=on&mobile-payment-type-choice=on&hint=&successURL=&quickpay=shop&account=$account' style=\"width:$width;\" height='$height' frameborder='0' allowtransparency='true' scrolling='yes'></iframe>$content";
        $account = $skinmanager->input(['name' => 'receiver', 'value' => $account, 'type' => 'hidden']);
        $quickform = $skinmanager->input(['name' => 'quickpay-form', 'value' => 'donate', 'type' => 'hidden']);
        $title = $skinmanager->input(['name' => 'targets', 'value' => $title, 'type' => 'hidden']);
        $sum = $skinmanager->p([
            'content' => $skinmanager->input(['name' => 'sum', 'width' => '100%', 'value' => $pay, 'type' => 'number', 'placeholder' => 300, 'min' => 10, 'max' => 1000000000, 'required' => true])
        ]);
        $ya = $skinmanager->p([
            'content' => $skinmanager->input(['name' => 'paymentType', 'type' => 'radio', 'value' => 'Яндекс.Деньгами'])
        ]);
        $bank = $skinmanager->p([
            'content' => $skinmanager->input(['name' => 'paymentType', 'type' => 'radio', 'value' => 'Банковской картой', 'checked' => true])
        ]);
        $send = $skinmanager->p([
            'align' => 'right',
            'content' => $skinmanager->input(['type' => 'submit', 'value' => 'Перевести'])
        ]);
        $description = $skinmanager->text(['text' => 'Внимание:помогая автору сайта вы повышаете себе карму!']);
        $img = $skinmanager->img(['src' => 'https://vignette.wikia.nocookie.net/fallout/images/5/53/38_Paradigm_of_Humanity.png/revision/latest?cb=20100325144844']);
        return "<form method=\"POST\" action=\"https://money.yandex.ru/quickpay/confirm.xml\">$account$quickform$title$sum$ya$bank$send$description$img</form>";
        //return "<form method='POST' action='https://money.yandex.ru/quickpay/confirm.xml'><input type='hidden' name='formcomment' value='Проект «Железный человек»: реактор холодного ядерного синтеза'><input type='hidden' name='short-dest' value='Проект «Железный человек»: реактор холодного ядерного синтеза'><input type='hidden' name='label' value='$order_id'><input type='hidden' name='quickpay-form' value='donate'><input type='hidden' name='targets' value='$title'><p style='text-align: left;'></p><input type='hidden' name='comment' value='$comment'><input type='hidden' name='need-fio' value='false'><input type='hidden' name='need-email' value='false'> <input type='hidden' name='need-phone' value='false'><input type='hidden' name='need-address' value='false'><p style='text-align: left;'><label><input type='radio' name='paymentType' value='PC'>Яндекс.Деньгами</label></p><p style='text-align: left;'><label><input type='radio' name='paymentType' value='AC' checked>Банковской картой</label></p><p style='text-align: right;'><input type='submit' value='Перевести'></p></form>";
    }
}
