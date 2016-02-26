# Объектные модели данных #

Работа с базой данных абстрагирована через расширение PDO и библиотеку "ORM Doctrine": http://doctrine-project.org

Модели данных - структура таблиц, с полями, ключами и взаимосвязями - представляются отдельными классами и хранятся в подпапке "models" внутри папки модуля. Инсталляция модуля начинается с генерации необходимых таблиц, соответствующих представленной схеме.
Пример описания структуры таблицы "{prefix}core\_modules":
```
class xbkModule_Record extends Doctrine_Record
{

    public function setUp()
    {
        $this->hasMany('xbkSection_Record as Section', array('local' => 'id', 'foreign' => 'module_id'));
        $this->hasMany('xbkTemplateModule_Record as TemplateModule', array('local' => 'id', 'foreign' => 'module_id'));
        $this->hasMany('xbkLang_Record as Lang', array('local' => 'id', 'foreign' => 'module_id'));
        $this->hasMany('xbkPrivilege_Record as Privilege', array('local' => 'id', 'foreign' => 'module_id'));
        $this->hasMany('xbkExtensionPoint_Record as ExtensionPoint', array('local' => 'id', 'foreign' => 'module_id'));
        $this->hasMany('xbkExtension_Record as Extension', array('local' => 'id', 'foreign' => 'module_id'));
        $this->hasMany('xbkBlockType_Record as BlockType', array('local' => 'id', 'foreign' => 'module_id'));
    }

    public function setTableDefinition()
    {
    	global $CONFIG;

    	$this->setTableName($CONFIG['db']['table_prefix'].'core_modules');

        $this->hasColumn('project', 'string', 50, array('notnull' => true, 'unique' => true));
        $this->hasColumn('system', 'boolean');
        $this->hasColumn('version', 'string', 20);
        $this->hasColumn('xbk_version', 'string', 20);
        $this->hasColumn('dependencies', 'array', 10000);
        $this->hasColumn('class', 'string', 100);
        $this->hasColumn('weight', 'float');
        $this->hasColumn('config', 'array', 10000);
        $this->hasColumn('migration_required', 'integer', 3);
        $this->hasColumn('migration_current', 'integer', 3);
        $this->hasColumn('active', 'boolean', null, array('default' => false));
    }

}

```

Базовым классом модели данных является класс "`Doctrine_Record`". Имя класса следует задавать с использованием суффикса "Record", чтобы в процессе работы с его объектом можно было легко отличать от объектов иного назначения.

Описание свойств и полей таблицы задаётся внутри метода "setTableDefinition()". Метод "setTableName()" задаёт имя таблицы. Для системных модулей, а также модулей многоразового использования, обязательно следует добавлять к имени таблицы префикс, взятый из глобального конфигурационного массива. Каждым вызовом методом "hasColumn()" задаётся поле таблицы с указанием имени, типа, длины и доп. атрибутов. Внутри метода "setUp()" объявляются ключи таблицы. В данном примере посредством вызова метода "hasMany()" задаются внешние ключи таблицы. Второй пример показывает, как другая таблица устанавливает связь с текущей посредством указания внешнего ключа:
```
class xbkSection_Record extends Doctrine_Record
{

    public function setUp()
    {
    	global $CONFIG;
    	$this->index($CONFIG['db']['table_prefix'].'core_module_id_002',
        	array('fields' => Array('module_id'))
    	);
    	$this->index($CONFIG['db']['table_prefix'].'core_sections_parent_id',
        	array('fields' => Array('parent_id'))
    	);

    	$this->hasOne('xbkModule_Record as Module', array('local' => 'module_id', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
        $this->hasOne('xbkSection_Record as Parent', array('local' => 'parent_id', 'foreign' => 'id', 'onDelete' => 'CASCADE'));
        $this->hasMany('xbkSection_Record as Subsection', array('local' => 'id', 'foreign' => 'parent_id'));
    }

    public function setTableDefinition()
    {
        global $CONFIG;

        $this->setTableName($CONFIG['db']['table_prefix'].'core_sections');

        $this->hasColumn('module_id', 'integer', null, array('notnull' => true));
        $this->hasColumn('parent_id', 'integer', null, array('notnull' => false));
        $this->hasColumn('name', 'string', 50, array('notnull' => true));
        $this->hasColumn('type', 'string', 20, array('default' => 'node', 'notnull' => true));
        $this->hasColumn('enforce_ssl', 'boolean');
        $this->hasColumn('enforce_non_ssl', 'boolean');
        $this->hasColumn('class', 'string', 100, array('notnull' => true));
    }

}
```

Задание внешних взаимосвязей устанавливается с помощью методов "hasOne()" и "hasMany()". В нашем случае таблицы связаны по принципу "один-много", т. е. на одну запись в таблице "{prefix}core\_modules" приходится несколько из "{prefix}core\_sections". Во время удаления строки из "{prefix}core\_modules" ссылающиеся строки также удаляются - это принцип каскадного удаления, который задан в описании ключа "'onDelete' => 'CASCADE'".

Простые ключи-индексы с указанием имени ключа и массива полей задаются с помощью метода "index()", через который указываются имя ключа и массив полей. Если мы задаём внешний ключ, следует также указывать индекс. Формат задания индекса для внешнего ключа ссылающейся таблицы следующий: имя таблицы, на которую ссылаемся, нижний слеш, имя поля с которым мы связываемся, нижний слеш, трёхзначный номер ключа с ведущими нулями. Номер ключа здесь необходим так как все внешние ключи базы данных должны носить уникальные имена.

Для таблицы во втором примере задаётся также внутренняя древовидная связь по принципу "родитель-потомок". Если мы задаём связь в рамках одной таблицы, то имя внешнего ключа может заканчиваться на "parent\_id".

Вся дальнейшая работа с данными осуществляется как с обычными объектами, а также при формировании DQL-запросов. Подробное описание работы с моделями данных можно найти на сайте ORM Doctrine:
  * <a href='http://www.doctrine-project.org/documentation/manual/1_1/en/defining-models'>Введение в модельную архитектуру</a>
  * <a href='http://www.doctrine-project.org/documentation/manual/1_1/en/working-with-models'>Работа с моделями</a>
  * <a href='http://www.doctrine-project.org/documentation/manual/1_1/en/dql-doctrine-query-language'>Sql-диалект DQL (Doctrine Query Language)</a>