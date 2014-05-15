<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
$ORDER_ID = CSalePaySystemAction::GetParamValue('ORDER_ID');
$url = CSalePaySystemAction::GetParamValue('URL_API').'register.do?';
$url .= 'userName='.urlencode( CSalePaySystemAction::GetParamValue('LOGIN') ).'&password='.urlencode( CSalePaySystemAction::GetParamValue('PASS') ).'&orderNumber=2'.$ORDER_ID.'&amount='.CSalePaySystemAction::GetParamValue('PRICE')*(100).'&returnUrl=http://'.$_SERVER['SERVER_NAME'].CSalePaySystemAction::GetParamValue('BACKURL').'?ORDER_ID='.$ORDER_ID;

$json = file_get_contents($url);
$obj = json_decode($json);

if($obj->{'errorCode'} > 0):
	echo 'При оформлении заказа возникла ошибка.<br>';
	echo $obj->{'errorMessage'};
else:
	LocalRedirect($obj->{'formUrl'});
endif;
?>