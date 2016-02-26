Шаблонизатор на основе php представлен в виде класса xbkPhptemplate, объект которого вызывается посредством метода "phptemplate()", объявленный в базовом классе "xbkContextObject". Объект принимает в себя переменные и транслирует их в файл шаблона, который находится в папке "skin/html" текущего модуля. Имя файла шаблона должно заканчиваться на ".tpl.php". Пример использования:
```
$tmpl = $this->phptemplate('login_form');
$tmpl->addVar('title', 'Enter');
$tmpl->addVar('description', 'Please, enter you registration data below.');
$html = $tmpl->build();
```
Принимаемый аргумент метода "phptemplate" является именем файла шаблона без указания расширения. Метод "addVar()" объекта шаблона добавляет переменную в шаблон: первый аргумент - имя переменной, второй - значение. Метод "build()" возвращает обработанный шаблон.
Пример шаблона "login\_form.tpl.php":
```
<h1><?php echo $title ?></h1>
<div class="description"><?php echo $description ?></div>
<form method="post" action="">
<div class="label">Login: </div>
<div class="field"><input name="login" value="" /></div>
<div class="label">Password: </div>
<div class="field"><input type="password" name="pass" value="" /></div>
<div class="submit"><input type="submit" name="submit" value="Submit form" /></div>
</form>
```
Результат выполнения мы получим в переменную $html:
```
<h1>Enter</h1>
<div class="description">Please, enter you registration data below.</div>
<form method="post" action="">
<div class="label">Login: </div>
<div class="field"><input name="login" value="" /></div>
<div class="label">Password: </div>
<div class="field"><input type="password" name="pass" value="" /></div>
<div class="submit"><input type="submit" name="submit" value="Submit form" /></div>
</form>
```
В теле шаблона доступен ряд предобъявленных переменных:

  * **$path\_to\_root** - путь до корня сайта,
  * **$path\_to\_module** - путь до текущего модуля,
  * **$path\_to\_skin** - путь до папки "skin" текущего модуля,
  * **$path\_to\_img** - путь до папки с графикой текущего модуля,
  * **$path\_to\_css** - путь до папки со стилями текущего модуля,
  * **$path\_to\_js** - путь до папки с ява-скриптами текущего модуля.

Следующие методы удобно использовать в теле шаблона:
```
// Добавить файл CSS в шапку
$this->addCss($name);
// Добавить файл JS в шапку
$this->addJs($name);
// Добавить JS-библиотеку в шапку
$this->addJsLib($name);
```
В теле шаблона также доступно окружение текущего модуля и все методы класса "xbkContextObject", поэтому не обязательно передавать в шаблон массив языковых переменных - для этого достаточно их получить в самом шаблоне через "getLang($scope)", либо используя массив "`$this->_LANG`".

Шаблонизации подлежат также файлы таблиц стилей и ява-скрипты. Для запуска обработчика шаблона необходимо назначить расширение файлу ".css.php" для css-файла, или ".js.php" для js-файла. Таким образом в теле шаблона доступны предобъявленные переменные и методы "xbkContextObject". Очень удобно использовать такую шаблонизацию для подстановки путей и языковых переменных.