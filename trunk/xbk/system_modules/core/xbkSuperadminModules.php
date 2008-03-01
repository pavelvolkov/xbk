<?php

/**
 * xbkSuperadminModules
 *
 * Панель суперадмина - модули системы
 *
 * @version    1.0   2008-02-23
 * @package    xBk
 * @author     Pavel Bakanov
 * @license    LGPL
 * @link       http://bakanov.info
 */

class xbkSuperadminModules extends xbkSection
{
    /**
     * Ядро установлено
     *
     * @access    public
     * @var       boolean
     */
    public $coreInstalled = false;

    /**
     * Конструктор класса
     *
     * @access      public
     */
    public function __construct2 ()
    {

        global $CONFIG;

        // Панель суперадмина - общий класс
        $Superadmin =& $this->factory('xbkSuperadmin', $this);
        if (!$Superadmin->prepare()) return;

        // Заголовок
        $this->addTitle($this->_LANG['superadmin_modules_title']);

        // Срочное уведомление
        $teaser_html = '';

        $CoreInstaller =& $this->factory('xbkInstaller', 'core');

        $checkCore = $CoreInstaller->check();

        if ($checkCore['tables'] == xbkModelAnalyzer::CONTAINS_ALL)
        {        	$this->coreInstalled = true;        }

        // Предупреждение, если ядро не установлено
        if ($checkCore['tables'] != xbkModelAnalyzer::CONTAINS_ALL)
        {        	// Ссылка на секцию установки ядра        	$CoreInstallUri =& $this->factory('xbkUri');
        	$CoreInstallUri->gotoBrother('core');
	        // Уведомление
            $Teaser =& $this->factory('xbkTeaser');

            // Список предупреждений
            if ($checkCore['tables'] == xbkModelAnalyzer::CONTAINS_PART)
            {
                $Teaser->setContent(
                    str_replace(
                        '{LINK}',
                        $CoreInstallUri->build(),
                        $this->_LANG['superadmin_modules_core_contains_part']
                    )
                );
                $Teaser->setType('error');
            } else if ($checkCore['tables'] == xbkModelAnalyzer::CONTAINS_NONE)
            {
                $Teaser->setContent(
                    str_replace(
                        '{LINK}',
                        $CoreInstallUri->build(),
                        $this->_LANG['superadmin_modules_core_contains_none']
                    )
                );
                $Teaser->setType('warning');
            }
            $teaser_html = $Teaser->build();        }

        // Информация о модуле
        $module_info = '';
        if (isset($this->_GET['info']))
        {        	if (xbkFunctions::moduleExists($this->_GET['info']))
        	{            	$Module =& $this->factory('xbkModule', $this->_GET['info']);            	$module_info = $this->moduleInfo($Module);
        	}        }

        $modules = Array();
        $modules_html = Array();
        $module_list = xbkFunctions::getExistsModuleList();
        foreach ($module_list as $module_name)
        {        	$Module =& $this->factory('xbkModule', $module_name, false);        	array_push($modules, $this->moduleToArray($Module));        }

        // Содержимое
        $tmpl = $this->template();
        $tmpl->readTemplatesFromInput('superadmin_modules');
        $tmpl->addVar('content', 'teaser', $teaser_html);
        $tmpl->addVar('content', 'info', $module_info);
        $tmpl->addRows('modules', $modules);
        $content = $tmpl->getParsedTemplate('content');
        $this->setContent($Superadmin->wrap($content));

    }

    /**
     * Формирует массив с необходимой информацией из объекта модуля
     *
     * @access      public
     * @param       object xbkModule
     */
    public function moduleToArray (&$Module)
    {    	// Ссылка на подробную информацию о модуле
    	$InfoUri =& $this->factory('xbkUri');
    	$result = Array();
        $abstract = $Module->getAbstract();
        $result['name'] = $abstract['name'];
        $result['version'] = $abstract['version'];
        $result['xbk_version'] = $abstract['xbk_version'];
        $result['info_link'] = $InfoUri->build('info='.$abstract['name']);
        $result['action_link'] = $InfoUri->build('info='.$abstract['name']);
        $result['status_link'] = $InfoUri->build('info='.$abstract['name']);

        if ($Module->isSystem()) $result['type'] = $this->_LANG['superadmin_modules_type_system'];        else $result['type'] = $this->_LANG['superadmin_modules_type_user'];

        if ($abstract['info_class'] != null)
        {        	$result['name'] = $Module->getTitle();        }
        return $result;
    }

    /**
     * Формирует информацию о модуле
     *
     * @access      public
     * @param       object xbkModule
     */
    public function moduleInfo (&$Module)
    {    	if ($this->installHandling($Module)) return;
    	$rows = Array();
    	$info = $Module->getInfo();
    	$abstract = $Module->getAbstract();
    	if (isset($info['title']) ? $info['title'] != null : false)
    	{    		array_push($rows,
        		Array(
            		'name' => $this->_LANG['superadmin_module_title'],
            		'value' => $info['title']
        		)
    		);    	}
    	if (isset($info['description']) ? $info['description'] != null : false)
    	{
    		array_push($rows,
        		Array(
            		'name' => $this->_LANG['superadmin_module_description'],
            		'value' => $info['description']
        		)
    		);
    	}
    	if (isset($info['author']) ? $info['author'] != null : false)
    	{
    		array_push($rows,
        		Array(
            		'name' => $this->_LANG['superadmin_module_author'],
            		'value' => $info['author']
        		)
    		);
    	}
    	if (isset($info['license']) ? $info['license'] != null : false)
    	{
    		array_push($rows,
        		Array(
            		'name' => $this->_LANG['superadmin_module_license'],
            		'value' => $info['license']
        		)
    		);
    	}
    	if (isset($abstract['version']) ? $abstract['version'] != null : false)
    	{
    		array_push($rows,
        		Array(
            		'name' => $this->_LANG['superadmin_module_version'],
            		'value' => $abstract['version']
        		)
    		);
    	}
    	if (isset($abstract['xbk_version']) ? $abstract['xbk_version'] != null : false)
    	{
    		array_push($rows,
        		Array(
            		'name' => $this->_LANG['superadmin_module_xbk_version'],
            		'value' => $abstract['xbk_version']
        		)
    		);
    	}

    	// Структура
    	$status_check = xbkModelAnalyzer::checkTables($Module->getName());
    	if ($status_check == xbkModelAnalyzer::CONTAINS_ALL)
    	{
    		$structure_message = $this->_LANG['superadmin_module_structure_all'];
    	} else if ($status_check == xbkModelAnalyzer::CONTAINS_PART)
    	{
    		$structure_message = $this->_LANG['superadmin_module_structure_part'];
    	} else if ($status_check == xbkModelAnalyzer::CONTAINS_NONE) {
    		$structure_message = $this->_LANG['superadmin_module_structure_none'];
    	} else {
    		$structure_message = $this->_LANG['superadmin_module_structure_not_allowed'];
    	}
    	array_push($rows,
        		Array(
            		'name' => $this->_LANG['superadmin_module_structure'],
            		'value' => $structure_message
        		)
    	);

    	// Миграция
    	$migration_message = $this->_LANG['superadmin_module_migration_null'];
    	if ($this->coreInstalled)
    	{    		// Получаем данные о текущей миграции
    		if (isset($abstract['migration']))
    		{
        		$migration_required = $abstract['migration'];

        		// Анализатор текущего модуля
        		$Installer =& $this->factory('xbkInstaller', $Module->getName());

        		// Поиск регистрации текущего модуля
        		$table = $this->DB->getTable("xbkModule_Record");
            	$found = $table->findByDql("name = '".xbkFunctions::dqlEscapeString($Module->getName())."'");
            	if (count($found) > 0)
            	{            		//
            		$migration_current = $found[0]->migration_current;
            		if ($migration_current !=  null)
            		{
                		if ($migration_current == $migration_required)
                		{                			$migration_message = $this->_LANG['superadmin_module_migration_current'];                		} else if ($migration_current < $migration_required)
                		{                			$migration_message = $this->_LANG['superadmin_module_migration_obsolete'];                		} else if ($migration_current > $migration_required)
                		{
                			$migration_message = $this->_LANG['superadmin_module_migration_surpass'];
                		}
            		}            	}

        	}
    	}
     	array_push($rows,
    		Array(
        		'name' => $this->_LANG['superadmin_module_migration'],
        		'value' => $migration_message
    		)
		);

    	// Регистрация
    	$ModuleRegisterAnalyzer = $this->factory('xbkModuleRegisterAnalyzer', $Module->getName());
    	$rigister_check = $ModuleRegisterAnalyzer->check();
    	if ($rigister_check == xbkModuleRegisterAnalyzer::CONTAINS_ALL)
    	{
    		$register_message = $this->_LANG['superadmin_module_register_all'];
    	} else if ($rigister_check == xbkModuleRegisterAnalyzer::CONTAINS_PART)
    	{
    		$register_message = $this->_LANG['superadmin_module_register_part'];
    	} else if ($rigister_check == xbkModuleRegisterAnalyzer::CONTAINS_NONE) {
    		$register_message = $this->_LANG['superadmin_module_register_none'];
    	} else {
    		$register_message = $this->_LANG['superadmin_module_register_core_not_installed'];
    	}
    	array_push($rows,
        		Array(
            		'name' => $this->_LANG['superadmin_module_register'],
            		'value' => $register_message
        		)
    	);

    	$ActionLink =& $this->factory('xbkUri');
    	$ActionLink->addInheritedParameters('info');

    	// Действия
    	if ($status_check == xbkModelAnalyzer::CONTAINS_ALL)
    	{
    		$action = $this->_LANG['superadmin_module_action_uninstall'];
    		$action_link = $ActionLink->build('action=uninstall');
    	} else if ($status_check == xbkModelAnalyzer::CONTAINS_PART)
    	{
    		$action = $this->_LANG['superadmin_module_action_install'];
    		$action_link = $ActionLink->build('action=install');
    	} else {
    		$action = $this->_LANG['superadmin_module_action_install'];
    		$action_link = $ActionLink->build('action=install');
    	}
    	/*
    	array_push($rows,
        		Array(
            		'name' => $this->_LANG['superadmin_module_action'],
            		'value' => $action
        		)
    	);
    	*/
    	$actions = Array();

        if ($Module->getName() != 'core' && xbkModelAnalyzer::checkTables('core') == xbkModelAnalyzer::CONTAINS_ALL)
        {
        	if (
            	( $status_check == xbkModelAnalyzer::CONTAINS_NONE ||
            	$status_check == xbkModelAnalyzer::NOT_ALLOWED ) &&
            	$rigister_check == xbkModuleRegisterAnalyzer::CONTAINS_NONE
        	) {        		// Установка        		array_push($actions,
            		Array(
                		'text' => $this->_LANG['superadmin_module_action_install'],
                		'link' => $ActionLink->build('action=install')
            		)
        		);        	} else {        		// Деинсталляция
        		array_push($actions,
            		Array(
                		'text' => $this->_LANG['superadmin_module_action_uninstall'],
                		'link' => $ActionLink->build('action=uninstall')
            		)
        		);        	}

            // Регистрация
        	if ($rigister_check == xbkModuleRegisterAnalyzer::CONTAINS_ALL)
        	{
        		// Перерегистрировать
        		array_push($actions,
            		Array(
                		'text' => $this->_LANG['superadmin_module_action_register_reinstall'],
                		'link' => $ActionLink->build('action=register_reinstall')
            		)
        		);
              	// Обновить языковой интерфейс
            	array_push($actions,
            		Array(
                		'text' => $this->_LANG['superadmin_module_action_lang_refresh'],
                		'link' => $ActionLink->build('action=lang_refresh')
            		)
        		);
        	} else if ($rigister_check == xbkModuleRegisterAnalyzer::CONTAINS_PART)
        	{
        		array_push($actions,
            		Array(
                		'text' => $this->_LANG['superadmin_module_action_register_reinstall'],
                		'link' => $ActionLink->build('action=register_reinstall')
            		)
        		);
        	} else if ($rigister_check == xbkModuleRegisterAnalyzer::CONTAINS_NONE && $status_check == xbkModelAnalyzer::CONTAINS_ALL) {
        		array_push($actions,
            		Array(
                		'text' => $this->_LANG['superadmin_module_action_register_install'],
                		'link' => $ActionLink->build('action=register_install')
            		)
        		);
        	} else {
        	}

        	// Миграции
        	array_push($actions,
        		Array(
            		'text' => $this->_LANG['superadmin_module_action_migrations'],
            		'link' => $ActionLink->build('action=migration').'#migration'
        		)
    		);
            /*            // Структура
        	if ($status_check == xbkModelAnalyzer::CONTAINS_ALL)
        	{
        		array_push($actions,
            		Array(
                		'text' => $this->_LANG['superadmin_module_action_structure_uninstall'],
                		'link' => $ActionLink->build('action=structure_uninstall')
            		)
        		);
        	} else if ($status_check == xbkModelAnalyzer::CONTAINS_PART)
        	{
        		array_push($actions,
            		Array(
                		'text' => $this->_LANG['superadmin_module_action_structure_reinstall'],
                		'link' => $ActionLink->build('action=structure_reinstall')
            		)
        		);
        	} else if ($status_check == xbkModelAnalyzer::CONTAINS_NONE) {
        		array_push($actions,
            		Array(
                		'text' => $this->_LANG['superadmin_module_action_structure_install'],
                		'link' => $ActionLink->build('action=structure_install')
            		)
        		);
        	}

        	// Миграции
        	array_push($actions,
        		Array(
            		'text' => $this->_LANG['superadmin_module_action_migrations'],
            		'link' => $ActionLink->build('action=migration').'#migration'
        		)
    		);

            // Регистрация
        	if ($rigister_check == xbkModuleRegisterAnalyzer::CONTAINS_ALL)
        	{        		// Удалить регистрационную информацию
        		array_push($actions,
            		Array(
                		'text' => $this->_LANG['superadmin_module_action_register_uninstall'],
                		'link' => $ActionLink->build('action=register_uninstall')
            		)
        		);
        		// Перерегистрировать
        		array_push($actions,
            		Array(
                		'text' => $this->_LANG['superadmin_module_action_register_reinstall'],
                		'link' => $ActionLink->build('action=register_reinstall')
            		)
        		);
              	// Обновить языковой интерфейс
            	array_push($actions,
            		Array(
                		'text' => $this->_LANG['superadmin_module_action_lang_refresh'],
                		'link' => $ActionLink->build('action=lang_refresh')
            		)
        		);
        	} else if ($rigister_check == xbkModuleRegisterAnalyzer::CONTAINS_PART)
        	{
        		array_push($actions,
            		Array(
                		'text' => $this->_LANG['superadmin_module_action_register_reinstall'],
                		'link' => $ActionLink->build('action=register_reinstall')
            		)
        		);
        	} else if ($rigister_check == xbkModuleRegisterAnalyzer::CONTAINS_NONE) {
        		array_push($actions,
            		Array(
                		'text' => $this->_LANG['superadmin_module_action_register_install'],
                		'link' => $ActionLink->build('action=register_install')
            		)
        		);
        	} else {
        	}
        	*/

    	} else {
            // Действия над ядром
        	if ($Module->getName() == 'core' && xbkModelAnalyzer::checkTables('core') == xbkModelAnalyzer::CONTAINS_ALL)
        	{        		// Перерегистрировать
        		array_push($actions,
            		Array(
                		'text' => $this->_LANG['superadmin_module_action_register_reinstall'],
                		'link' => $ActionLink->build('action=register_reinstall')
            		)
        		);        		// Обновить языковой интерфейс
            	array_push($actions,
            		Array(
                		'text' => $this->_LANG['superadmin_module_action_lang_refresh'],
                		'link' => $ActionLink->build('action=lang_refresh')
            		)
        		);        	}

        	// Миграции
        	array_push($actions,
        		Array(
            		'text' => $this->_LANG['superadmin_module_action_migrations'],
            		'link' => $ActionLink->build('action=migration').'#migration'
        		)
    		);
    		// Перейти к ядру    		$CoreLink =& $this->factory('xbkUri');
    		$CoreLink->gotoSideSection('core');    		array_push($actions,
        		Array(
            		'text' => $this->_LANG['superadmin_module_action_core'],
            		'link' => $CoreLink->build()
        		)
    		);    	}

    	// Уведомление о действии
    	$teaser = '';
    	if (isset($this->_GET['desc']))
    	{    		if ($this->_GET['desc'] == 'install_ok')
    		{
        		$input = $this->_LANG['superadmin_module_action_install_ok'];
        		$Ts =& $this->factory('xbkTeaser', $input, 'ok');
                $teaser = $Ts->build();
            }
            if ($this->_GET['desc'] == 'uninstall_ok')
    		{
        		$input = $this->_LANG['superadmin_module_action_uninstall_ok'];
        		$Ts =& $this->factory('xbkTeaser', $input, 'ok');
                $teaser = $Ts->build();
            }    		if ($this->_GET['desc'] == 'structure_install_ok')
    		{        		$input = $this->_LANG['superadmin_module_action_structure_install_ok'];        		$Ts =& $this->factory('xbkTeaser', $input, 'ok');
                $teaser = $Ts->build();
            }
            if ($this->_GET['desc'] == 'structure_uninstall_ok')
    		{
        		$input = $this->_LANG['superadmin_module_action_structure_uninstall_ok'];
        		$Ts =& $this->factory('xbkTeaser', $input, 'ok');
                $teaser = $Ts->build();
            }
            if ($this->_GET['desc'] == 'register_install_ok')
    		{
        		$input = $this->_LANG['superadmin_module_action_register_install_ok'];
        		$Ts =& $this->factory('xbkTeaser', $input, 'ok');
                $teaser = $Ts->build();
            }
            if ($this->_GET['desc'] == 'register_reinstall_ok')
    		{
        		$input = $this->_LANG['superadmin_module_action_register_reinstall_ok'];
        		$Ts =& $this->factory('xbkTeaser', $input, 'ok');
                $teaser = $Ts->build();
            }
            if ($this->_GET['desc'] == 'register_uninstall_ok')
    		{
        		$input = $this->_LANG['superadmin_module_action_register_uninstall_ok'];
        		$Ts =& $this->factory('xbkTeaser', $input, 'ok');
                $teaser = $Ts->build();
            }
            if ($this->_GET['desc'] == 'lang_refresh_ok')
    		{
        		$input = $this->_LANG['superadmin_module_action_lang_refresh_ok'];
        		$Ts =& $this->factory('xbkTeaser', $input, 'ok');
                $teaser = $Ts->build();
            }
            if ($this->_GET['desc'] == 'migration_ok')
    		{
        		$input = $this->_LANG['superadmin_module_migrations_action_ok'];
        		$Ts =& $this->factory('xbkTeaser', $input, 'ok');
                $teaser = $Ts->build();
            }
            if ($this->_GET['desc'] == 'migration_error' && isset($this->_GET['exception_message']))
    		{
        		$input = nl2br($this->_LANG['superadmin_module_migrations_action_error']."\n".$this->_GET['exception_message']);
        		$Ts =& $this->factory('xbkTeaser', $input, 'error');
                $teaser = $Ts->build();
            }    	}

    	// Содержимое
        $tmpl = $this->template('superadmin_module_info');
        $tmpl->addVar('content', 'teaser', $teaser);
        $tmpl->addRows('attr', $rows);
        $tmpl->addRows('menu', $info['menu']);
        $tmpl->addRows('actions', $actions);
        $tmpl->addVar('content', 'migration', $this->migrationBlock($Module, $ActionLink));
        return $tmpl->getParsedTemplate('content');
    }

    /**
     * Форма работы с миграциями
     *
     * @access      public
     * @param       object xbkModule
     * @return      string
     */
    public function migrationBlock (&$Module, $ActionLink)
    {    	global $CONFIG;
    	if (isset($this->_GET['action']) ? ($this->_GET['action'] == 'migration') : false)
    	{    		//$models_path = xbkFunctions::getModulePath($Module->getName()).$CONFIG['path']['internal']['models'];    		//$models = Doctrine::loadModels($models_path);
    		// Менеджер миграций    		$MigrationManager = $this->factory('xbkMigrationManager', $Module);

    		// Текущая версия
    		$currentVersion = $Module->getCurrentMigrationVersion();

    		// Требуемая версия
            $requiredVersion = $Module->getRequiredMigrationVersion();

    		// Доступные номера версий структуры данных
    		$numbers = $MigrationManager->getMigrationNumbers();

    		if (!in_array(0, $numbers))
    		{    			// Добавляем нулевую миграцию
    			$numbers = array_merge(Array(0), $numbers);    		}

    		$Uri =& $ActionLink;
    		$Uri->addInheritedParameters('action');

    		$migration_rows = Array();
    		foreach ($numbers as $number)
    		{    			$row = Array();
    			$row['num'] = $number;
    			$row['link'] = $Uri->build('migration_action=performing&from='.$currentVersion.'&to='.$number);
    			$row['type'] = 'outside';
    			if ($currentVersion == $number) $row['type'] = 'current';
    			if ($requiredVersion == $number) $row['type'] = 'required';
    			if ($requiredVersion == $number && $currentVersion == $number) $row['type'] = 'normal';
    			$migration_rows[] = $row;    		}

    		$exceptionMessage = null;

        	// Действия
        	if (isset($this->_GET['migration_action']))
        	{        		if ($this->_GET['migration_action'] == 'renew')
        		{
            		// Миграция до текущей версии
                    $result = $MigrationManager->migrate(null, $exceptionMessage);
                    if ($result)
                    {
                        $this->setRedirect($Uri->build('desc=migration_ok'));
                        return;
                    } else {
                    	$this->setRedirect($Uri->build(Array('desc' => 'migration_error', 'exception_message' => $exceptionMessage)));
                        return;
                    }
                }
                if ($this->_GET['migration_action'] == 'performing' && isset($this->_GET['from'], $this->_GET['to']))
        		{        			$from = ($this->_GET['from'] != '') ? (int)$this->_GET['from'] : 0;
        			$to = ($this->_GET['to'] != '') ? (int)$this->_GET['to'] : 0;        			// Миграция с произвольной версии до произвольной версии
        			if (in_array($from, $numbers) && in_array($to, $numbers) && $from != $to)
        			{        				//echo $from.' '.$to.' ';
        				//return;        				$MigrationManager->setCurrentVersion($from);
        				$result = $MigrationManager->migrate($to, $exceptionMessage);
        				if ($result)
                        {
                            $this->setRedirect($Uri->build('desc=migration_ok'));
                            return;
                        } else {                        	$this->setRedirect($Uri->build(Array('desc' => 'migration_error', 'exception_message' => $exceptionMessage)));
                            return;                        }        			}
        		}
        	}

    		// Опорная ссылка
    		$tmpl = $this->template('superadmin_module_info_migration');
    		$tmpl->addVar('content', 'renew_link', $Uri->build('migration_action=renew').'#migration');
    		$tmpl->addRows('migrations', $migration_rows);
            return $tmpl->getParsedTemplate('content');    	} else return '';
    }

    /**
     * Обработка запроса по установке/деинсталляции
     *
     * @access      public
     * @param       object xbkModule
     * @return      boolean    результат
     */
    public function installHandling (&$Module)
    {
        if (isset($this->_GET['action']))
        {        	$Uri =& $this->factory('xbkUri');
        	$Uri->addInheritedParameters('info');
            $Installer =& $this->factory('xbkInstaller', $Module->getName());

        	if ($this->_GET['action'] == 'install')
        	{
                try {                	// Установка
                    $Installer->createDbStructure();
                    $Installer->unregister();
                    $Installer->register();
                    $this->setRedirect($Uri->build('desc=install_ok'));
                    return true;
                } catch (Exception $e) { echo 'Ex: '. $e->getMessage();
                	// Неудачная установка
                	$Installer->unregister();
                	$Installer->dropDbStructure();
                	return false;
                }
        	}

        	if ($this->_GET['action'] == 'uninstall')
        	{        		try {
            		// Деинсталляция
            		$Installer->unregister();
                    $Installer->dropDbStructure();
            		$this->setRedirect($Uri->build('desc=uninstall_ok'));
                    return true;
                } catch (Exception $e) {
                	// Неудачная деинсталляция
                	return false;
                }
        	}
        	if ($this->_GET['action'] == 'structure_install')
        	{        		// Создание структуры БД
                $Installer->createDbStructure();

        		$this->setRedirect($Uri->build('desc=structure_install_ok'));
                return true;        	}

        	if ($this->_GET['action'] == 'structure_uninstall')
        	{
        		// Удаление структуры БД
                $Installer->dropDbStructure();

        		$this->setRedirect($Uri->build('desc=structure_uninstall_ok'));
                return true;
        	}

        	if ($this->_GET['action'] == 'structure_reinstall')
        	{
        		// Удаление структуры БД
                $Installer->dropDbStructure();
                $Installer->createDbStructure();

        		$this->setRedirect($Uri->build('desc=structure_install_ok'));
                return true;
        	}

        	if ($this->_GET['action'] == 'register_install')
        	{
        		// Регистрация в реестре
        		try {
                    $Installer->register();
                    $this->setRedirect($Uri->build('desc=register_install_ok'));
                    return true;
                } catch (Exception $e) {                	// Неудачная регистрация
                	$Installer->unregister();
                	return false;                }
        	}

        	if ($this->_GET['action'] == 'register_reinstall')
        	{
        		// Перегистрация в реестре
        		try {
                    $Installer->updateRegister();
                    $this->setRedirect($Uri->build('desc=register_reinstall_ok'));
                    return true;
                } catch (Exception $e) {
                	// Неудачная перегистрация
                	$Installer->unregister();
                	$Installer->register();
                	return false;
                }
        	}

        	if ($this->_GET['action'] == 'register_uninstall')
        	{
        		// Удаление регистрации из реестра
                $Installer->unregister();

        		$this->setRedirect($Uri->build('desc=register_uninstall_ok'));
                return true;
        	}

        	if ($this->_GET['action'] == 'lang_refresh')
        	{
        		// Обновление языковых настроек
                $Installer->langUnregister();
                $Installer->langRegister();

        		$this->setRedirect($Uri->build('desc=lang_refresh_ok'));
                return true;
        	}
        }
    }

}

?>