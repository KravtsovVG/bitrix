<?
// отмена заказа
AddEventHandler('sale', 'OnSaleBeforeCancelOrder', Array('CSaleHandlers', 'OnSaleBeforeCancelOrderHandler'));
AddEventHandler('sale', 'OnSaleCancelOrder', Array('CSaleHandlers', 'OnSaleCancelOrderHandler'));
AddEventHandler('sale', 'OnSaleBeforePayOrder', Array('CSaleHandlers', 'OnSaleBeforeCancelOrderHandler'));
AddEventHandler('sale', 'OnSaleCancelPay', Array('CSaleHandlers', 'OnSaleCancelOrderHandler'));

class CSaleHandlers {
  private static $cancel = false;
  public static function OnSaleBeforeCancelOrderHandler($ID, $val) {
    if ($val == 'Y') {
      self::$cancel = true;
    }
  }
  public static function OnSaleCancelOrderHandler($ID, $val) {
    if ( $val == 'Y' && self::$cancel ) {
      $arOrder = CSaleOrder::GetByID($ID);
      $resPaySystemAction = CSalePaySystemAction::GetList(array(), array('PAY_SYSTEM_ID'=>$arOrder['PAY_SYSTEM_ID'], 'PERSON_TYPE_ID'=>$arOrder['PERSON_TYPE_ID']), false, false, array());
      $arPaySystemAction = $resPaySystemAction->GetNext();
      $urlCancel = $_SERVER['DOCUMENT_ROOT'].$arPaySystemAction['ACTION_FILE'].'/cancel.php';
      if (preg_match('/\/alfabank$/', $arPaySystemAction['ACTION_FILE']) && file_exists($urlCancel)) {
        include $urlCancel;
      }
    }
  }
}