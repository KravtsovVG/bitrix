bitrix
======
Все для работы с bitrix

htaccess
========

Ставим слэш на конце:

```
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^([^.]+)(?<!/)$ /$1/ [R=301,L]
```

Содержание
==========
