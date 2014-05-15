<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?><?
include(GetLangFileName(dirname(__FILE__)."/", "/bill.php"));

$psTitle = "Alfa Bank";
$psDescription = "";

$arPSCorrespondence = array(
	"URL_API" => array(
		"NAME" => "URL API",
		"DESCR" => "",
		"VALUE" => "https://test.paymentgate.ru/testpayment/rest/",
		"TYPE" => ""
		),
	"LOGIN" => array(
		"NAME" => "Логин API",
		"DESCR" => "",
		"VALUE" => "ttok.ru-api",
		"TYPE" => ""
		),
	"PASS" => array(
		"NAME" => "Пароль API",
		"DESCR" => "",
		"VALUE" => "ttok.ru",
		"TYPE" => ""
		),
	"ORDER_ID" => array(
		"NAME" => "Заказ",
		"DESCR" => "Указываем откуда взять заказ",
		"VALUE" => "ID",
		"TYPE" => "ORDER"
		),
	"PRICE" => array(
		"NAME" => "Цена",
		"DESCR" => "Указываем откуда взять цену",
		"VALUE" => "PRICE",
		"TYPE" => "ORDER"
		),
	"ORDER_BANK" => array(
		"NAME" => "Свойство заказа",
		"DESCR" => "Указываем имя свойства где храним банковское ID заказа",
		"VALUE" => "ORDER_ID_BANK",
		"TYPE" => "PROPERTY"
		),
	"STATUS_PAY" => array(
		"NAME" => "Статус заказа после оплаты",
		"DESCR" => "пустое поле - не меняем",
		"VALUE" => "",
		"TYPE" => ""
		),
	"STATUS_CANCEL" => array(
		"NAME" => "Статус заказа после отмены оплаты, отмены заказа",
		"DESCR" => "пустое поле - не меняем",
		"VALUE" => "",
		"TYPE" => ""
		),
	"BACKURL" => array(
		"NAME" => "URL страницы",
		"DESCR" => "Куда вернуть с платежной системы (без домена)",
		"VALUE" => "/",
		"TYPE" => ""
		),
	"THANKS" => array(
		"NAME" => "URL страницы 'Спасибо за покупку'",
		"DESCR" => "(без домена)",
		"VALUE" => "/",
		"TYPE" => ""
		),
	);
?>