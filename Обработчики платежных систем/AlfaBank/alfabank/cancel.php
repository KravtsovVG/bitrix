<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
// если был оплачен вернем бабки
if ($arOrder['PAYED'] == 'N') {
  $arParams = unserialize($arPaySystemAction['~PARAMS']);
  $resProp = CSaleOrderPropsValue::GetList(array(), array('ORDER_ID' => $arOrder['ID'], 'CODE'=>$arParams['ORDER_BANK']), false, false, array());
  if ($arProp = $resProp->GetNext()) {
    $url = CSalePaySystemAction::GetParamValue('URL_API').'refund.do?';
    $url .= 'amount='.( $arOrder['PRICE']*100 ).'&orderId='.$arProp['VALUE'].'&password='.urlencode($arParams['LOGIN']).'&userName='.urlencode($arParams['PASS']);
    $json = file_get_contents( $url );
    $obj = json_decode($json);
    if($obj->{'errorCode'} != 0 ) {
      echo $obj->{'errorMessage'};
    } else {
      CSaleOrder::PayOrder($arOrder['ID'], 'N');
      if ($arParams['STATUS_PAY']) {
        CSaleOrder::Update($ORDER_ID, array('STATUS_ID'=>$arParams['STATUS_PAY']));
      }
    }
  }
}