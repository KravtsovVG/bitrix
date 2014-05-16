<?
/*
ORDER_PRICE
ORDER_WEIGHT
PRICE_DELIVERY
PERSON_TYPE_ID
PAY_SYSTEM_ID
DELIVERY_ID
*/
public static function saleCart($param = array(), $update=false) {
  CModule::IncludeModule('sale');
  global $USER;
  $arOrderForDiscount = array(
    'SITE_ID' => SITE_ID,
    'USER_ID' => $USER->GetID(),
    'ORDER_PRICE' => $param['ORDER_PRICE'],
    'ORDER_WEIGHT' => $param['ORDER_WEIGHT'],
    'PRICE_DELIVERY' => $param['PRICE_DELIVERY'],
    'BASKET_ITEMS' => array(),
    "PERSON_TYPE_ID" => $param['PERSON_TYPE_ID'],
    "PAY_SYSTEM_ID" => $param['PAY_SYSTEM_ID'],
    "DELIVERY_ID" => $param['DELIVERY_ID'],
  );
  $dbBasketItems = CSaleBasket::GetList(
    array("NAME" => "ASC"),
    array(
      "FUSER_ID" => CSaleBasket::GetBasketUserID(),
      "LID" => SITE_ID,
      "ORDER_ID" => 'NULL'
    ),
    false,
    false,
    array("ID", "CALLBACK_FUNC", "MODULE", "PRODUCT_ID", "QUANTITY", "DELAY", "CAN_BUY", "PRICE", "WEIGHT", "NAME", "DISCOUNT_PRICE", "CURRENCY", "PRODUCT_PROVIDER_CLASS")
  );
  while ($arOneItem = $dbBasketItems->GetNext()) {
    $arOrderForDiscount['BASKET_ITEMS'][] = $arOneItem;
    $arOrderForDiscount['ORDER_WEIGHT'] += doubleval($arOneItem['WEIGHT']);
  }
  $arDiscountOptions = array();
  $arDiscountErrors = array();
  $ORDER_PRICE = 0;
  CSaleDiscount::DoProcessOrder($arOrderForDiscount, $arDiscountOptions, $arDiscountErrors);
  foreach ($arOrderForDiscount['BASKET_ITEMS'] as &$arOneItem) {
    $ORDER_PRICE += doubleval($arOneItem['PRICE'])*doubleval($arOneItem['QUANTITY']);
    $arBasketInfo = array(
      'IGNORE_CALLBACK_FUNC' => 'Y',
      'PRICE' => $arOneItem['PRICE'],
    );
    if (array_key_exists('DISCOUNT_PRICE', $arOneItem)) {
      $arBasketInfo['DISCOUNT_PRICE'] = $arOneItem['DISCOUNT_PRICE'];
    }
    // если нужно обновить поля корзины
    if ($update) {
      CSaleBasket::Update($arOneItem['ID'], $arBasketInfo);
    }
  }
  return $arOrderForDiscount['ORDER_PRICE'];
}