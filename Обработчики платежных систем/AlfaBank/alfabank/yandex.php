<?
class Timer
{
    var $startTime;
    var $endTime;
	
    function start()
    {
        $this->startTime = gettimeofday();
    }
	
	function __construct()
	{
		$this->start();
	}	
	
    function stop()
    {
       $this->endTime = gettimeofday();
    }
    function elapsed()
    {
        return (($this->endTime["sec"] - $this->startTime["sec"]) * 1000000 + ($this->endTime["usec"] - $this->startTime["usec"])) / 1000000;
    }
	function show()
	{
		echo "<hr>Time <b>".$this->elapsed()."</b> sek.<hr>";
	}
}



//--------------------------------------------------------------------------------------------------

$t1 = new Timer();



require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

function cutString($string, $maxlen) {
     $len = (mb_strlen($string) > $maxlen)
         ? mb_strripos(mb_substr($string, 0, $maxlen), ' ')
         : $maxlen
     ;
     $cutStr = mb_substr($string, 0, $len);
     return (mb_strlen($string) > $maxlen)
         ? '' . $cutStr . '...'
         : '' . $cutStr . ''
     ;
 }
 
 function api_get_img_patch($id_img, $SITE_URL)
 {
  $src = CFile::GetPath($id_img);
  if (!empty($src))
  {
   return $SITE_URL.$src;
  }
  else
  {
   return "";
  } 
 }

CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");

$IBLOCK_ID = $my_cnf->cat_id;
$PRICE_ID = $my_cnf->price_id;
$SITE_URL = "http://www.cplaza.ru";
$SITE_NAME="Компьютер Плаза";

$time_now = date("Y-m-d H:i");


/* $MixedList= CIBlockSection::GetMixedList(
	$arOrder=Array("SORT"=>"ASC"),
	$arFilter=Array(
		'IBLOCK_ID'=>6,
		'PROPERTY_ForYandex_value'=>'Y',
		'ACTIVE' => 'Y',
	),
	$bIncCnt = false,
	$arSelectedFields = false
); */


// получаем категории 
$categoryList = CIBlockSection::GetList(
 $arOrder = Array("SORT"=>"ASC"),
 $arFilter = Array(
	'IBLOCK_ID'=>$IBLOCK_ID,	
 ),
 $bIncCnt = false
);
$categoryArr = array();
while($ar_result = $categoryList->GetNext())
{
    //new dbug ($ar_result);
	$categoryArr[$ar_result['ID']] = array(
		'id'				=>$ar_result['ID'],
		'parent_id'			=>$ar_result['IBLOCK_SECTION_ID'],
		'name'				=>$ar_result['NAME'],
		'_count_products'	=>0,
	);
}



// получаем товары 
$CIBlockElement = CIBlockElement::GetList(
 $arOrder = Array("SORT"=>"ASC"),
 $arFilter = Array(
	"IBLOCK_ID" =>$IBLOCK_ID,
	"ACTIVE" => "Y",
	"PROPERTY_ForYandex_VALUE" => 'Y'
 ),
 $arGroupBy = false,
 $arNavStartParams = false,
 $arSelectFields = Array("*",  "CATALOG_GROUP_$PRICE_ID", "CATALOG_GROUP_4", 'PROPERTY_ALWAYS_AVAILABLE', 'CATALOG_QUANTITY', 'PREVIEW_PICTURE', 'DETAIL_PICTURE','PROPERTY_YML_2PREORDER')
);

$productArr = array();
while($ar_result = $CIBlockElement->GetNext())
{
    //new dbug ($ar_result);
	
	if ($ar_result['PROPERTY_ALWAYS_AVAILABLE_VALUE'] == "Y" || $ar_result['CATALOG_QUANTITY'] > 0 || $ar_result['PROPERTY_YML_2PREORDER_VALUE'] )
	{
		if ($ar_result["CATALOG_CURRENCY_$PRICE_ID"] != 'RUB')
		{
			continue;
		}
		
		$from = array('"', '&', '>', '<', '\'');
		$to = array('&quot;', '&amp;', '&gt;', '&lt;', '&apos;');
		$NAME = str_replace($from, $to, $ar_result['NAME']);


		//$description = $ar_result['PREVIEW_TEXT'];
		$description = $ar_result['~DETAIL_TEXT'];
		//$description = cutText($description, 450);
		$description = cutString($description, 450);
		$description = str_replace($from, $to, $description);

		$available = 'true';
		if ( !$ar_result['PROPERTY_ALWAYS_AVAILABLE_VALUE'] == "Y" && !$ar_result['CATALOG_QUANTITY'] > 0 && $ar_result['PROPERTY_YML_2PREORDER_VALUE'] ){
			$available = 'false';
		}

		$productArr[] = array(
			'id'				=>$ar_result['ID'],
			'categoryId'		=>$ar_result['IBLOCK_SECTION_ID'],
			'name'				=>$NAME,
			'description'		=>$description,
			'price'				=>$ar_result["CATALOG_PRICE_$PRICE_ID"],
			'price_sebestoim'	=>$ar_result["CATALOG_PRICE_4"],
			'url'				=>$SITE_URL.$ar_result["DETAIL_PAGE_URL"].'?utm_source=market.yandex.ru&amp;utm_medium=cpc&amp;utm_campaign=11453_adhands&amp;utm_term='.$ar_result['ID'],
			//'url'				=>$SITE_URL.$ar_result["DETAIL_PAGE_URL"],
			
			'available'			=>$available,
			'currencyId'		=>'RUB',
			'picture'			=>api_get_img_patch($ar_result['DETAIL_PICTURE'],$SITE_URL),
		);
		
		//>>> пополняем в категорию количеством товаров
		$categoryArr[$ar_result['IBLOCK_SECTION_ID']]['_count_products'] = $categoryArr[$ar_result['IBLOCK_SECTION_ID']]['_count_products'] + 1;
		//<<<
						
	}
	else
	{
		continue;
	}
	
	
	//new dbug ($ar_result);
}

// Удаляем пустые категории
foreach ($categoryArr as $k => $v)
{
	if ($v['_count_products'] > 0)
	{}
	else
	{
		unset ($categoryArr[$k]);
	}
}


//new dbug ($categoryArr);
//new dbug ($productArr);

//exit();

//--------------------------------------------------------------------------------------------------

//$t1->stop();
//$t1->show();
header("Content-Type: text/xml; charset=utf-8");
echo '<?xml version="1.0" encoding="UTF-8"?>';
$tme_now = date("Y-m-d H:i");
?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="<?=$tme_now;?>">
	<shop>
		<name>Компьютер Плаза</name>
		<company><?=$SITE_NAME;?></company>
		<url><?=$SITE_URL;?>/</url>
		<currencies>
			<currency id="RUB" rate="1"/>
		</currencies>
		<categories>
		<?
		/*
			<category id="886">
				Скидки в сентябре
			</category>
		*/
		?>
		<? foreach ($categoryArr as $k => $v): ?>
			<category id="<?=$k;?>">
				<?=$v['name']?>
			</category>
		<? endforeach; ?>
		</categories>
		<offers>
			<?
			/*
			<offer id="16135" available="true">
				<url>
				http://cplaza.ru/catalog/16135.html?r1=yandext&r2=
				</url>
				<price>
				5430
				</price>
				<currencyId>
				RUB
				</currencyId>
				<categoryId>
				859
				</categoryId>
				<picture>
				http://cplaza.ru/upload/iblock/808/80870ce63eb9e99ce41e492882a77ae7.jpeg
				</picture>
				<name>
				Amazon Kindle 3 Wi-Fi Graphite - Электронная книга с E-Ink дисплеем
				</name>
				<description>
				Amazon Kindle 3 Wi-Fi Graphite - Электронная книга с E-Ink дисплеем
				</description>
			</offer> 
			*/
			?>
			<? foreach ($productArr as $k => $v): ?>
			<? if (empty($v['url'])){continue;} ?>
			<offer id="<?=$v['id']?>" available="<?=$v['available']?>">
				<url>
					<?=$v['url']?>
				</url>
				<price>
					<?=$v['price']?>
				</price>
				<currencyId>
					<?=$v['currencyId']?>
				</currencyId>
				<categoryId>
					<?=$v['categoryId']?>
				</categoryId>
				<picture>
					<?=$v['picture']?>
				</picture>
				<name>
					<?=$v['name']?>
				</name>
				<description>
					<?=$v['description']?>
				</description>
				<?
					$credit = '';
					if ($v['price'] > 3000)
					{
						$credit = ', Кредит';
					}
				?>
				<sales_notes>Наличные, Безнал., Банковские карты<?=$credit?></sales_notes>
				<param name="pricelabs_purchase_price"><?=$v['price_sebestoim']?></param>
			</offer>
			<? endforeach; ?>
			
		</offers>
	</shop>
</yml_catalog>		