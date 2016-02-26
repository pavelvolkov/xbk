# Внешний запрос и механизм ссылок #

Механизм ссылок предоставляет удобной функционал для построения внутренних ссылок и реализован в виде класса "xbkUri". Данный механим предпочтителен для обеспечения гибкости в проектировании связанных страниц и поддержания цельности проекта.

Ссылку url можно условно разделить на следующие компоненты:
  * **scheme** - протокол соединения;
  * **host** - имя хоста;
  * **port** - порт;
  * **user** - имя пользователя;
  * **pass** - пароль;
  * **path** - путь;
  * **query** - строка запроса;
  * **fragment** - фрагмент (якорь на странице).
Параметры **user** и **pass** характерны для ftp-соединения.

Разберём структуру ссылки на примере следующего адреса:
```
https://my.domain.com/path/to/site/path/to/section/local/task?var1=value1&var2=value2#form
```
Здесь протокол соединения - "https", хост - "my.domain.com", путь - "path/to/site/path/to/section/local/task", строка запроса - "var1=value1&var2=value2", фрагмент - "form".

Путь **path** в системе "xBk" делится на три части:
  * Путь до корневой папки сайта - он задаётся в главном конфигурационном массиве $CONFIG и автоматически добавляется в сгенерированную ссылку. Механизм ссылок всегда генерирует адреса от корня сайта. В нашем примере - "path/to/site".
  * Путь маршрутизатора (путь до секции) - определяет иерархический путь до искомой секции. В нашем примере - "path/to/section".
  * Внутренний путь секции - остальная часть пути, которая используется в качестве внешнего параметра в работе секции. В нашем примере - "local/task".

Если мы используем субконтроллер, то путь маршрутизатора будет пустым - весь внутренний путь будет восприниматься как путь секции.

Строка запроса в конечном итоге преобразуется в массив `$this->_GET`. При построении ссылки механизм ссылок может делать обратное преобразование из массива в строку запроса.

Для получения пути запроса и его частей в базовом классе "xbkContextObject" предоставлен ряд методов.

Получить текущий путь запроса в форме строки и в форме массива:
```
$request_path = $this->getRequestPath();
$request_path_array = $this->getRequestPathArray();
```
Первый метод вернёт значение "path/to/section/local/task", второй - массив:
```
Array
(
    [0] => path
    [1] => to
    [2] => section
    [3] => local
    [4] => task
)
```

Получить текущий путь маршрутизатора в форме строки и в форме массива:
```
$router_path = $this->getRouterPath();
$router_path_array = $this->getRouterPathArray();
```
Первый метод вернёт значение "path/to/section", второй - массив:
```
Array
(
    [0] => path
    [1] => to
    [2] => section
)
```

Получить текущий внутренний путь в форме строки и в форме массива:
```
$inner_path = $this->getInnerPath();
$inner_path_array = $this->getInnerPathArray();
```
Первый метод вернёт значение "local/task", второй - массив:
```
Array
(
    [0] => local
    [1] => task
)
```

Таким образом анализ внешнего запроса производится с помощью методов получения пути запроса и массива `$this->_GET`.

Для начала генераниции ссылки необходимо инстанцировать объект класса "xbkUri". Для этого можно использовать вспомогательные методы "uri()" и "thisUri()":
```
$Uri = $this->uri();
$ThisUri = $this->thisUri();
```
В обоих случаях возвращается объект класса "xbkUri", но с разными параметрами. В чём здесь разница, будет рассмотрено ниже.

Первичное построение:
```
$Uri = $this->uri();
$result1 = $Uri->build();
```
Переменная $result1 здесь равна "/path/to/site/path/to/section/local/task", т. е. полному пути запроса. Аргументы get-запроса из текущего адреса не наследуются, если не было на это команды. Чтобы наследовать get-переменные нужно воспользоваться следующей конструкцией:
```
$ThisUri = $this->thisUri();
$result2 = $ThisUri->build();
```
В данном случае переменная $result2 равна полному пути, включая строку запроса: "/path/to/site/path/to/section/local/task?var1=value1&var2=value2". Следующая конструкция вернёт то же самое:
```
$Uri = $this->uri();
$Uri->setThisUri();
$result2 = $Uri->build();
```
Здесь и далее методы класса "xbkUri", цель которых сообщить объекту какого-либо атрибут, всегда возвращают ссылку на текущий объект. Таким образом в простых ситуациях можно сокращать код:
```
$result2 = $this->uri()->setThisUri()->build();
```
Метод "goto()" используется для замены текущего пути запроса:
```
$Uri = $this->uri();
$result1 = $Uri->goto('path/to/other/section')->build();
$result2 = $Uri->goto(Array('path', 'to', 'other', 'section'))->build();
```
Оба варианта дают результат "/path/to/site/path/to/other/section".

Метод "gotoInner()" используется для замены внутреннего пути запроса в рамках текущей секции:
```
$Uri = $this->uri();
$result1 = $Uri->gotoInner('inner/path')->build();
$result2 = $Uri->gotoInner(Array('inner', 'path'))->build();
```
Оба варианта дают результат "/path/to/site/path/to/section/inner/path".

Метод "gotoParent()" осуществляет переход на уровень выше относительно текущего пути:
```
$Uri = $this->uri();
$result = $Uri->gotoParent()->build();
```
Данный пример возвращает "/path/to/site/path/to/section/local".

Метод "gotoBrother()" осуществляет переход на соседний уровень относительно текущего пути:
```
$Uri = $this->uri();
$result = $Uri->gotoBrother('task2')->build();
```
Данный пример возвращает "/path/to/site/path/to/section/local/task2".

Метод "gotoChild()" осуществляет переход на дочерний уровень относительно текущего пути:
```
$Uri = $this->uri();
$result1 = $Uri->gotoChild('child/path')->build();
$result2 = $Uri->gotoChild(Array('child', 'path'))->build();
```
Оба варианта дают результат "/path/to/site/path/to/section/local/task/child/path".

Сообщение аргументов в строку запроса осуществляется через метод "build()":
```
$Uri = $this->uri();
$result1 = $Uri->build('var3=value3&var4=value4');
$result2 = $Uri->build(Array('var3' => 'value3', 'var4' => 'value4'));
```
Оба варианта дают результат "`/path/to/site/path/to/section/local/task?var3=value3&var4=value4`".

В метод "build()" можно подставить любой структуры массив, который после перехода по ссылке будет преобразован в массив `$this->_GET` в исходном виде:
```
$array = Array(
	"simplekey" => "simplevalue",
	"subarray" => Array (
		"subkey1" => "subvalue1",
		"subkey2" => "subvalue2"
	)
);
$result = $this->uri()->build($array);
```
Результат: "`/path/to/site/path/to/section/local/task?simplekey=simplevalue&subarray[subkey1]=subvalue1&subarray[subkey2]=subvalue2`".

Наследование текущих get-аргументов осуществляется методом "inherit()", отмена наследования - методом "noInherit()". Оба метода принимают в качестве параметра массив имён get-аргументов. Метод "noInheritAll()" отменяет наследование всех аргументов. Нижеследующий пример наглядно демонстрирует работу методов наследования:
```
$Uri = $this->uri();
$Uri->inherit(Array('var1', 'var2'));
$result1 = $Uri->build();
$Uri->noInherit(Array('var1'));
$result2 = $Uri->build();
$Uri->noInheritAll();
$result3 = $Uri->build();
```
Первый вариант даёт результат "/path/to/site/path/to/section/local/task?var1=value1&var2=value2", второй - "/path/to/site/path/to/section/local/task?var2=value2", третий - "/path/to/site/path/to/section/local/task".

Разовую отмену наследования get-аргументов можно делать в методе "build()":
```
$Uri = $this->uri();
$Uri->inherit(Array('var1', 'var2'));
$result = $this->build('', Array('var1'));
```
Данный пример вернёт "/path/to/site/path/to/section/local/task?var2=value2". Второй параметр метода "build()" - массив имён исключаемых get-аргументов.