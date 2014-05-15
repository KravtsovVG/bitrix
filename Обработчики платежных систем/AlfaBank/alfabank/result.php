<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
CModule::IncludeModule('sale');
$ORDER_ID = $GLOBALS['ORDER_ID'];
$resProp = CSaleOrderPropsValue::GetList(array(), array('ORDER_ID' => $ORDER_ID, 'CODE'=>CSalePaySystemAction::GetParamValue('ORDER_BANK')), false, false, array());
if ($arProp = $resProp->GetNext()) {
  $orderId = $arProp['VALUE'];
  $url = CSalePaySystemAction::GetParamValue('URL_API').'getOrderStatus.do?';
  $json = file_get_contents( $url.'userName='.urlencode( CSalePaySystemAction::GetParamValue('LOGIN') ).'&password='.urlencode( CSalePaySystemAction::GetParamValue('PASS') ).'&orderId='.urlencode($orderId) );
  $obj = json_decode($json);
  if($obj->{'errorCode'} == 0 && $obj->{'OrderStatus'} == 2) {
    // оплачено
    CSaleOrder::PayOrder($ORDER_ID, 'Y');
    if (CSalePaySystemAction::GetParamValue('STATUS_PAY')) {
      CSaleOrder::Update($ORDER_ID, array('STATUS_ID'=>CSalePaySystemAction::GetParamValue('STATUS_PAY')));
    }
    return true;
  }
}
return false;