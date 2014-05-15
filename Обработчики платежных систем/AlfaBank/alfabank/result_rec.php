<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?
$orderId = htmlspecialchars( $_GET['orderId'] );
$ORDER_ID = (int)$_GET['ORDER_ID'];
if ($arOrder = CSaleOrder::GetByID($ORDER_ID)) {
  $res = CSaleOrderProps::GetList(array(), array('CODE'=>CSalePaySystemAction::GetParamValue('ORDER_BANK'), 'PERSON_TYPE_ID'=>$arOrder['PERSON_TYPE_ID']), false, false, array());
  if ($ar = $res->GetNext()) {
    // запишем orderId
    $resProp = CSaleOrderPropsValue::GetList(array(), array('ORDER_ID' => $ORDER_ID, 'CODE'=>CSalePaySystemAction::GetParamValue('ORDER_BANK')), false, false, array());
    if ($arProp = $resProp->GetNext()) {
      CSaleOrderPropsValue::Update($arProp['ID'], array("VALUE"=>$orderId));
    } else {
      $arFields = array(
        "ORDER_ID" => $ORDER_ID,
        "ORDER_PROPS_ID" => $ar['ID'],
        "NAME" => $ar['NAME'],
        "CODE" => $ar['CODE'],
        "VALUE" => $orderId,
      );
      CSaleOrderPropsValue::Add($arFields);
    }
  }

  $url = CSalePaySystemAction::GetParamValue('URL_API').'getOrderStatus.do?';
  $json = file_get_contents( $url.'userName='.urlencode( CSalePaySystemAction::GetParamValue('LOGIN') ).'&password='.urlencode( CSalePaySystemAction::GetParamValue('PASS') ).'&orderId='.urlencode($orderId) );
  $obj = json_decode($json);
  if($obj->{'errorCode'} == 0 && $obj->{'OrderStatus'} == 2):
    CModule::IncludeModule('sale');
    // оплачено
    CSaleOrder::PayOrder($ORDER_ID, 'Y');
    if (CSalePaySystemAction::GetParamValue('STATUS_PAY')) {
      CSaleOrder::Update($ORDER_ID, array('STATUS_ID'=>CSalePaySystemAction::GetParamValue('STATUS_PAY')));
    }
    

  endif;
  LocalRedirect(CSalePaySystemAction::GetParamValue('THANKS').'?ORDER_ID='.$ORDER_ID);
} else {
  echo 'Заказ не найден!';
}
die;