Содержание
==========

**myCache** - Простой кэш (поддержка битрикс)

---
**Общий класс с полезностями** - Общий класс с полезностями :)

---
**getStructure.php** - возвращает цепочку категорий товара вплоть до базовой

---
**saleCart.php** - правила работы с корзиной

---

Bitrix cache
============

Начиная с 12 версии кэш Битрикса практически догнал наш кэш по производительности. Можно уже использовать.

```php
  $obCache = new CPHPCache();
  $cacheID = 'arrData'; // используем некое уникальное имя для кэша
  $cacheLifetime = 3600*24; // сутки
  if ( $obCache->InitCache($cacheLifetime, $cacheID) ) {
    $vars = $obCache->GetVars();
    $arrData = $vars['arrData'];
  } else {
    $arrData = ... ;
    ...
    $obCache->EndDataCache(array('arrData' => $arrData));
  }
```