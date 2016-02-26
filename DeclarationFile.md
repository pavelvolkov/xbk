# Декларационный файл module.xml #

Схема интеграции модуля в системе описывается в специальном декларационном файле module.xml.
Рассмотрим структуру файла на следующем примере:

```
<?xml version="1.0" encoding="utf-8" ?>
<declaration xbkVersion="0.11">
    <meta>
        <project>my_module</project>
        <version>2.1</version>
        <xbkVersion>0.11</xbkVersion>
        <migration>7</migration>
        <class>MyModule_Meta</class>
        <weight>21</weight>
    </meta>
    <dependencies>
	<module project="user" version="2.0" cohesion="medium" />
        <module project="form" version="2.0" cohesion="medium" />
    </dependencies>
    <sections>
        <section type="page" name="my_login" enforceSSL="true" class="MyModule_LoginSection">
        <section type="node" name="main" enforceNonSSL="true" class="MyModule_MainSection">
            <section type="node" name="sub1" class="MyModule_Sub1Section" />
            <section type="node" name="sub2" class="MyModule_Sub2Section" />
        </section>
        <section type="page" name="query" enforceSSL="true" class="MyModule_QuerySection" />
        <section type="image" name="image" class="MyModule_ImageSection" />
    </sections>
    <extensions>
        <extension name="my_cron" class="OtherModule_MyExtension" extensionPoint="cron" extensionPointModule="core" weight="4" />
        <extension name="my_test" class="OtherModule_MyTest" extensionPoint="tester" extensionPointModule="tester" weight="23" />
    </extensions>
    <extensionPoints>
        <extensionPoint name="my_extension_point" class="MyModule_MyExtensionPoint" />
    </extensionPoints>
</declaration>
```

Атрибут "xbkVersion" корневого тега "declaration" указывает на версию системы, в контексте которой понимается формат данного xml-файла - этим осуществляется обратная совместимость со старыми версиями xBk. Группа тегов "meta" описывает установочную информацию:
  * **project** - имя текущего модуля, должно совпадать с именем папки;
  * **version** - версия модуля;
  * **xbkVersion** - версия системы с момента последнего тестирования модуля.
  * **migration** - номер требуемой миграции;
  * **class** - класс мета-информации модуля;
  * **weight** - вес, определяющий позицию в списе модулей;
Блок тегов "dependencies" является опциональным и содержит описательную информацию о сторонних модулях, с которыми взаимодействует данный модуль. Ядро можно не указывать. Здесь следует перечислить модули с указанием проектного имени, версии и силы сцепления "cohesion".

Остальные группы тегов описаны в специальных разделах. Стоит сказать об атрибуте "class", который используется в разных тегах - он везде указывает на класс, исполняющий функцию, характерную для данного тега.

Булевые операторы в декларационном файле указываются строковым эквивалентом: здесь значение "истина" обозначаются как "true", "yes", "on", "1". "Ложь" - соответственно, как "false", "no", "off", "0".