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

// noRoman
function getStructure($id=0, $section=false, $stopID=0) {
  $id = (int)$id;
  if (!$id) { return false; }
  if ($section) { $res = CIBlockSection::GetByID($id); } else { $res = CIBlockElement::GetByID($id); }
  // херню подсунули
  if ( !$res->SelectedRowsCount() ) { return false; }
  $ar = $res->GetNext();
  $arStructure = array();
  while ( $ar['IBLOCK_SECTION_ID'] && $ar['IBLOCK_SECTION_ID']!=$stopID ) {
    $res = CIBlockSection::GetByID($ar['IBLOCK_SECTION_ID']);
    $ar = $res->GetNext();
    $arStructure[] = $ar;
  }
  return $arStructure;
}