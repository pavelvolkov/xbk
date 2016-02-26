# Секция #

Секция - это динамический раздел сайта, вызываемый главным контроллером на основании внешнего запроса. Секция объявляется в декларацонном файле "module.xml" и представляет собой подкласс "xbkSection".

Фрагмент файла "module.xml":
```
    <sections>
        <section type="page" name="my_login" enforceSSL="true" class="MyModule_LoginSection">
        <section type="node" name="main" enforceNonSSL="true" class="MyModule_MainSection">
            <section type="node" name="sub1" class="MyModule_Sub1Section" />
            <section type="node" name="sub2" class="MyModule_Sub2Section" />
        </section>
        <section type="page" name="query" enforceSSL="true" class="MyModule_QuerySection" />
        <section type="image" name="image" class="MyModule_ImageSection" />
    </sections>
```
Блок тегов "sections" содержит описания секций данного модуля. Каждой секции соответствуют имя (name), тип (type), класс (class) и некоторые атрибуты.

Поддерживаются следующие типы секций:
  * **node** - состоит из содержания, заголовка, мета-тегов и т. д. Заключается в общий макет, доступ к которому не имеет. Макетом для этого типа секции служит шаблон системного модуля "Макет".
  * **page** - представляет собой самостоятельную веб-страницу произвольного шаблона.
  * **document** - любой другой текстовый документ типа "text/xml", "text/css" и др...
  * **image** - бинарный файл графического изображения поддерживаемого формата (jpeg, gif, png, bmp).
  * **file** - файл, выводимый на скачивание.
  * **blank** - произвольный тип, не содержащий изначальных http-заголовков.

Имя секции соответствует пути запроса, по которому она вызывается. Если у нас объявлена секция с именем "main", то она будет вызвана по адресу http://mysite.com/main. Также точно эта секция будет вызвана по адресу, содержащем дочерний путь, при условии что по этому адресу не объявлено дочерней подсекции: http://mysite.com/main/sub.

Дочерние подсекции объявляются дочерними тегами - в нашем примере это секции "sub1" и "sub2". Дочерняя секция вызывается по дочернему пути: http://mysite.com/main/sub1 и http://mysite.com/main/sub2. Дочерняя секция наследует свойства родительской секции, если они не были переобъявлены.

Дополнительные атрибуты секции:
  * **enforceSSL** - принудительная переадресация на SSL-протокол;
  * **enforceNonSSL** - принудительная переадресация c SSL-протокола обратно;

Класс указывается в атрибуте "class". Работа с классом секции производится следующим образом:
```
class MyModule_LoginSection extends xbkSection
{

    /**
     * Запуск на выполнение
     *
     * @access      public
     */
    public function execute ()
    {
    	$this->setTitle($this->_LANG['login_title']);

        $tmpl = $this->phptemplate('login');
        $tmpl->addVar('action', $this->uri()->build());

        $this->setContent($tmpl->build());
    }

}

?>
```

Основными методами здесь являются "setTitle()" и "setContent()", устанавливающие, соответсвенно, заголовок и содержимое. Такая схема работы справедлива для секций типа "node" и "page".

Методы, объявленные в классе "xbkSection", которые следует использовать:
```
public function setType ($type);
public function getType ();
```
Можно динамически менять тип секции методом "setType()" и получать текущий тип методом "getType()".

Следующие методы устанавливают и получают атрибут "enforceSSL":
```
public function setEnforceSSL ($enforceSSL);
public function isEnforceSSL ();
```
Аналогично для "enforceNonSSL":
```
public function setEnforceNonSSL ($enforceNonSSL);
public function isEnforceNonSSL ();
```
Устанавливает содержимое и возвращает установленное содержимое:
```
public function setContent ($content);
public function getContent ();
```
Устанавливает http-заголовок и получает http-заголовки:
```
public function setHeader ($header);
public function getHeaders ();
```
Устанавливает и возвращает тип изображения (jpeg, gif, png) для секции типа "image":
```
public function setImageType ($type);
public function getImageType ();
```
Устанавливает и возвращает тип документа для секции типа "document":
```
public function setDocumentType ($type);
public function getDocumentType ();
```
Устанавливает и возвращает имя файла для секции типа "file":
```
public function setFilename ($filename);
public function getFilename ();
```
Устанавливает секцию 404:
```
public function set404 ();
```