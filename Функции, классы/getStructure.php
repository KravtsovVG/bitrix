<?
// возвращает цепочку категорий товара вплоть до базовой

// Живойкин Роман
function get_arr_category($_id, $this_section=false)
{
 $res = array();
 $sect_id = $_id;
 
 if (!$this_section)
 {
  $db_old_groups = CIBlockElement::GetElementGroups($_id, true);
  if( $ar_group = $db_old_groups->Fetch() )
  {
   $sect_id = $ar_group['ID'];
  }
  else
  {
   return false;
  }
 }
 
 $sect_id = $sect_id;
 while (true)
 {
  $res_ = CIBlockSection::GetByID($sect_id);
  if($ar_res = $res_->GetNext())
  {
   $res[] = array ('id'=> $ar_res['ID'] , 'name'=> $ar_res['NAME'], 'code'=>$ar_res['CODE']  );
   if (!empty($ar_res['IBLOCK_SECTION_ID']))
   {
    // next itr
    $sect_id = $ar_res['IBLOCK_SECTION_ID'];
    continue;
   }
   else
   { 
    break;
   }
  }
  else
  {
   break;
  }
 }
 return $res;
}

// noRoman (получить родительские разделы с пользовательскими свойствами )
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
  $res = CIBlockSection::GetList(array('LEFT_MARGIN'=>'ASC'), array(
    'IBLOCK_ID' => $arSect['IBLOCK_ID'],
    '<=LEFT_BORDER' => $arSect['LEFT_MARGIN'],
    '>=RIGHT_BORDER' => $arSect['RIGHT_MARGIN'],
    '<DEPTH_LEVEL' => $arSect['DEPTH_LEVEL'],
    '!UF_NOSHOW'=>false
    ), $arUf);
  $arStructure = array();
  while($ar = $res->GetNext()){
    $arStructure[] = $ar;
  }
  return $arStructure;
}
// noRoman (получить родительские разделы без пользовательскими свойствами )
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