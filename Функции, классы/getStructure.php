<?
// получить родительские разделы с пользовательскими свойствами
function getStructure($id, $section=false, $arUf=array()) {
  $id = (int)$id;
  if (!$id) { return false; }
  if ($section) {
    $res = CIBlockSection::GetByID($id);
  } else {
    $res = CIBlockElement::GetByID($id);
    if ( $ar = $res->GetNext() ){
      $res = CIBlockSection::GetByID($ar['IBLOCK_SECTION_ID']);
    } else { return false; }
  }
  // херню подсунули
  if ( !$res->SelectedRowsCount() ) { return false; }
  $arSect = $res->GetNext();
  $arStructure = array();
  $res = CIBlockSection::GetList(array('LEFT_MARGIN'=>'ASC'), array(
    'IBLOCK_ID' => $arSect['IBLOCK_ID'],
    '<=LEFT_BORDER' => $arSect['LEFT_MARGIN'],
    '>=RIGHT_BORDER' => $arSect['RIGHT_MARGIN'],
    '<DEPTH_LEVEL' => $arSect['DEPTH_LEVEL'],
    ), false, $arUf);
  
  while($ar = $res->GetNext()){
    $arStructure[] = $ar;
  }
  $arStructure[] = $arSect;
  return $arStructure;
}
// получить родительские разделы без пользовательских свойств
function getStructure($id, $section=false) {
  $id = (int)$id;
  if (!$id) { return false; }
  if (!$section) {
    $res = CIBlockElement::GetByID($id);
    if ( $ar = $res->GetNext() ){
      $id = $ar['IBLOCK_SECTION_ID'];
    } else { return false; }
  }
  $res = CIBlockSection::GetNavChain(false, $id);
  $arStructure = array();
  while($ar = $res->GetNext()){
    $arStructure[] = $ar;
  }
  return $arStructure;
}