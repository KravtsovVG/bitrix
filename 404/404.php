<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 
global $APPLICATION;
$APPLICATION->RestartBuffer();
CHTTP::SetStatus("404 Not Found");
$flag404=true;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Страница не найдена");
?>
<p></p>
<p></p>
<table id="error404">
  <tr>
    <td>
      <div>
        <span class="title">Сожалеем. Страница не найдена</span><br>
        <span class="txt">Вы можете воспользоваться поиском или меню, чтобы найти<br>нужную Вам страницу, либо ознакомиться с нашим <a href="/prajs_list/">прайс-листом</a>.<br>Начать с <a href="/">Главной страницы.</a></span>
      </div>
    </td>
  </tr>
</table>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>