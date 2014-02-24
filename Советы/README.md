1.
==

Для простого обращения к инфоблокам и типам цен проще задать класс с константами. В случае изменения легко поправить в единственном классе значение и не надо править во всем коде. Например (файл с настройками cfg.php):
```php
class CFG{
  const IBLOCK_ID_CATALOG=1; // ID инфоблока "каталог"
  const ROZ_PRICE_ID=1; // ID розничной цены
  const OPT_PRICE_ID=2; // ID оптовой цены
  const FIZFACE_ID=2; // ID физического лица
  const URFACE_ID=2; // ID юридического лица

  public static $arValue=array();

  public static function init(){
    // ... здесь можно инициализировать переменные ...
    CFG::$arValue=$tmp;
  }
}
CFG::init();
```

2.
==

Файлы надо удалять через массив с параметром "del"=>"Y".
Удаление свойства типа "Файл":
```php
CIBlockElement::SetPropertyValuesEx(ELEMENT_ID, IBLOCK_ID, array(PROPERTY_ID => Array ("VALUE" => array("del" => "Y"))));
```

```
http://dev.1c-bitrix.ru/api_help/iblock/classes/ciblockelement/setpropertyvalues.php
```