# Мета-класс модуля #

Мета-класс модуля предоставляет расширенную информацию, а также выполняет ряд действий общего характера, необходимые в работе данного модуля. Мета-класс указыватся в файле "module.xml" и не является обязательным.
Фрагмент файла "module.xml" на примере модуля ядра:
```
    <meta>
        <project>core</project>
        <version>1.10</version>
        <xbkVersion>0.11</xbkVersion>
        <migration>10</migration>
        <class>xbkCoreMeta</class>
        <weight>1</weight>
    </meta>
```
Мета-класс указан теге "class" группы тегов "meta" и должен быть унаследован от абстарктного класса "xbkModuleMeta". В базовом классе объявлен ряд методов, которые можно переобъявить в дочернем - они следующие:
```
    /**
     * Заголовок
     *
     * @access	  public
     * @return	  string или null
     */
    public function getTitle ()
    {
    	return null;
    }

    /**
     * Описание
     *
     * @access	  public
     * @return	  string или null
     */
    public function getDescription ()
    {
    	return null;
    }

    /**
     * Автор
     *
     * @access	  public
     * @return	  string или null
     */
    public function getAuthor ()
    {
    	return null;
    }

    /**
     * Лицензия
     *
     * @access	  public
     * @return	  string или null
     */
    public function getLicense ()
    {
    	return null;
    }

    /**
     * Конфигурации по-умолчанию
     *
     * @access	  public
     * @return	  array
     */
    public function getConfigDefault ()
    {
    	return Array();
    }

    /**
     * Дополнительное меню, отображаемое в информации о данном модуле
     *
     * @access	  public
     * @return	  object xbkMenu
     */
    public function getMenu ()
    {
    	return null;
    }

    /**
     * Метод, запускаемый в момент инсталляции модуля.
     * Последовательность установки модуля:
     * 1. Создание таблиц БД на основе моделей данных
     * 2. Запуск метода install()
     *
     * @access	  public
     */
    public function install ()
    {

    }

    /**
     * Метод, запускаемый в момент деинсталляции модуля.
     * Последовательность деинсталляции модуля:
     * 1. Запуск метода uninstall()
     * 2. Упразднение таблиц БД на основе моделей данных
     *
     * @access	  public
     */
    public function uninstall ()
    {

    }

    /**
     * Метод, запускаемый в момент инициализации
     *
     * @access	  public
     */
    public function initialize ()
    {

    }
```
Первые четыре метода используются главным образом для отображения доп. информации на текущем языке в списке модулей. Метод "getConfigDeafault()" определяет конфигурации модуля по-умолчанию. Метод "getMenu()" возвращает объект класса "xbkMenu", которое используется в качестве быстрых разделов данного модуля в списке модулей. Методы "install()" и "uninstall()" запускаются в момент инсталляции и деинсталляции модуля - удобны для создания/удаления папок в файловой структуре сайта и внесения первичной информации в БД. Метод "initialize()" запускается при каждом вызове модуля.