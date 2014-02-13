myCache
=======

Простой кэш (поддержка битрикс)

При использовании в Битрикс размещаем папку myCache в public_html/include/
В public_html/bitrix/php_interface/init.php подключаем:
```php
	include $_SERVER['DOCUMENT_ROOT'].'/include/myCache/cache.php';
```
В CMS Битрикс отслеживается сброс кэша пользователем с соответствующими правами.
Если использование не в CMS Битрикс, то закоментируйте строки указанные в коде cache.php. Файлы myCache/*.js можно удалить.

Пример использования:
```php
	$cache=new myCache('nameCache');// используем некое уникальное имя для кэша
	if($cache->check()){// по-умолчанию жизнь кэша 1 час. Можно увеличить: $cache->check(7200) - 2 часа
		...
		$buffer.='string';
		...
		echo $cache->write($buffer);
	}else{
		echo $cache->read();
	}

	// пример использования кэша для массивов
	$cache=new myCache('nameCacheArr');// используем некое уникальное имя для кэша
	if($cache->check()){// по-умолчанию жизнь кэша 1 час. Можно увеличить: $cache->check(7200) - 2 часа
		...
		$arBuffer[]='data';
		...
		$cache->write(json_encode($arBuffer));
	}else{
		$arBuffer=json_decode($cache->read(),true);
	}
	...
	foreach($arBuffer as $item) ...
```

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