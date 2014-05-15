Alfa Bank
===
  1. Копируем папку alfabank в /bitrix/modules/sale/payment/
  2. В /bitrix/php_interface/init.php вставляем обработчик возврата средств

Настройка

Магазин - Платежные системы - Добавить платежную систему
Выбираем обработчик Alfa Bank.
URL страницы ( куда вернуть с платежной системы (без домена) ) - туда мы вставляем код:
```php
<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

list($ORDER_ID, $PAY_SYSTEM_ID, $PERSON_TYPE_ID) = explode('_', htmlspecialchars( $_GET['ORDER_ID'] ) );
$APPLICATION->IncludeComponent("bitrix:sale.order.payment.receive", "", array(
    "PAY_SYSTEM_ID" => (int)$PAY_SYSTEM_ID,
    "PERSON_TYPE_ID" => (int)$PERSON_TYPE_ID,
  )
);

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>
```

Этот код проверит результат оплаты и если он положителен проставит статусы.

URL страницы 'Спасибо за покупку' - на страницу с благодарностями. В GET ORDER_ID