Внимание!!! В init.php должен подключаться модуль: ``` CModule::IncludeModule('iblock'); ```

**FUNC::paginator($page=1,$sizePage=10,$total)** - выводит пагинацию в виде:

```html
  <div class="counter">
    <a href="/url/">Назад</a>
    <a href="/url/">1</a>
    <span>2</span>
    <a href="?page=3">3</a>
    <a href="?page=4">4</a>
    <a href="?page=5">5</a>
    <a href="?page=5">Дальше</a>
  </div>
```

 - $page=1 - текущая страница
 - $sizePage=10 - элементов на странице
 - $total - всего элементов

**FUNC::getEndWord($count,$text0='товаров',$text1='товар',$text2='товара')** - возвращает слово с нужным окончанием

 - $count - кол-во (в данном случае товаров)
 - 0 товаров
 - 1 товар
 - 2 товара

**FUNC::getPropOrderVal($order_id, $prop_id, [$or_prop_id=false])** - возвращает значение свойства заказа

 - $order_id - ID заказа
 - $prop_id - ID свойства
 - $or_prop_id - ID альтернативного свойства

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
```

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

**FUNC::getNews($iblock_id,$page,$count)** - получить массив новостей с пагинацией (необходим myCache)

- $iblock_id - ID инфоблока
- $page - страница
- $count - кол-во новостей на странице

```php
array(
  'items'=>array(
    ...,
    array(
      'id'=>...,
      'name'=>...,
      'name_'=>...(для вывода в alt, title. В имени " заменены на ')...,
      'img'=>...(картинка детальная)...,
      'img_small'=>...(картинка анонса)...,
      'anons'=>...(текст анонса)...,
      'url'=>...(согласно DETAIL_PAGE_URL)...,
      'date'=>...(в коде в формате 13 февраля 2009, поменяйте на необходимый)...,
    ),
    ...,
  ),
  'paginator'=>...Строка. Пагинация согласно шаблона .default компонента system.pagenavigation...
);
```

---

**FUNC::is_mobile()** - проверка на мобильный браузер

---

**Translit::UrlTranslit($string)** - транслитерация для url

---