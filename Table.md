# Таблица #

Класс "xbkTable" предоставляет функционал для формирования html-таблиц в едином формате. Инстанцирование класса производится обычным образом:

```
$this->loadClass('xbkTable', 'core', true);
$Table = $this->factory('xbkTable');
```

Конструктор класса может принимать два параметра: объект шаблона таблицы и массив опций:
```
$this->loadClass('xbkTable', 'core', true);
$tmpl = $this->phptemplate('custom_table_template');
$options = Array(
'empty_text' => 'No data',
'width' = '500',
'style' => 'background-color: yellow',
);
$Table = $this->factory('xbkTable', $tmpl, $options);
```

Объект шаблона таблицы `$tmpl` перекрывает основной шаблон по-умолчанию "table.tpl.php". В массиве `$options` можно указать следующие атрибуты:
  * **empty\_text** - текст, отображаемый в пустой таблице;
  * **width** - ширина таблицы для тега "width";
  * **style** - стиль таблицы;