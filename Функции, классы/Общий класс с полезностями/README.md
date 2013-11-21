**FUNC::getResizeImg($idImg,[[[$width=220],$height=220],$width_size=false])** - ресайз фото
 - $idImg - ID фото
 - $width - максимальная ширина
 - $height - максимальная высота
 - $width_size - FALSE - вернуть url, TRUE вернуть массив c url фото, с получившейся шириной, высотой и тп

---
**FUNC::getCountProductsBasket** - получить общее кол-во товара в корзине

---

**FUNC::getSubSections($iblockID,[[[$sect_id=0],$active='Y'],$arSort=array('SORT'=>'ASC'),$arUF=array()])** - получить массив разделов (в коде массив array(...,array('id'=>...,'name'=>...,'url'=>...,'img'=>...),...) )
 - $iblockID - ID инфоблока (обязательно)
 - $sect_id - в каком разделе (по-умолчанию в корне)
 - $active - активность (по-умолчанию только активные)
 - $arSort - сортировка (по-умолчанию array('SORT'=>'ASC'))
 - $arUF - массив пользовательских полей

---
**FUNC::getBasket** - получить содержимое корзины. В коде массив
```php
  array(
    'arBasket'=>array(
      ...,
      array(
        'id'=>...(ID товара)...,
        'name'=>...,
        'url'=>...,
        'img'=>...,
        'desc'=>...(PREVIEW_TEXT товара)...,
        'price'=>...(форматированная цена товара х ххх.хх)...,
        'num_price'=>...(неформатированная цена)...,
        'count'=>...,
        'sum'=>...(сумма)...,
        'idBasket'=>...(ID товара в корзине)...,
      ),
      ...,
    ),
    'totalProduct'=>...(общее кол-во товара)...,
    'totalSumm'=>...(общая сумма)...
  );
```php

---
**FUNC::checkProductToCart($id)** - проверить есть ли товар в корзине. Возвращает true|false

---
**FUNC::getNewCodeSection($iblockID,$name)** - создать уникальный символьный код для разделов
 - $iblockID - ID информационного блока
 - $name - имя

---
**FUNC::getNewCodeElement($iblockID,$name)** - создать уникальный символьный код для элементов
 - $iblockID - ID информационного блока
 - $name - имя

---
**FUNC::is_mobile()** - проверка на мобильный браузер

---
**Translit::UrlTranslit($string)** - транслитерация для url

---