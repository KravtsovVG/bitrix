myCache
=======

Простой кэш (поддержка битрикс)

При использовании в Битрикс размещаем папку myCache в public_html/include/
В public_html/bitrix/php_interface/init.php подключаем:

	include $_SERVER['DOCUMENT_ROOT'].'/include/myCache/cache.php';

В CMS Битрикс отслеживается сброс кэша пользователем с соответствующими правами.
Если использование не в CMS Битрикс, то закоментируйте строки указанные в коде cache.php. Файлы myCache/*.js можно удалить.

Пример использования:

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