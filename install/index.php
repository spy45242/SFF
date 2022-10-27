<?

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\Loader;
Loader::IncludeModule('highloadblock');
use Bitrix\Highloadblock as HL;

Class sff extends CModule
{
    public $MODULE_ID = 'sff';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;

	/**
	 * Название хайлоад-блока на разных языках
	 */
	private array $arLangs = [
		'ru' => 'Сео для фильтра',
		'en' => 'Seo for filter'
	];

	/**
	 * Пользовательские поля хайлоад-блока
	 */
	private array $arCartFields = [
		'UF_SFF_URL'=>[
			'ENTITY_ID' => $UFObject,
			'FIELD_NAME' => 'UF_SFF_URL',
			'USER_TYPE_ID' => 'string',
			'MANDATORY' => 'Y',
			"EDIT_FORM_LABEL" => ['ru'=>'url', 'en'=>'url'], 
			"LIST_COLUMN_LABEL" => ['ru'=>'url', 'en'=>'url'],
			"LIST_FILTER_LABEL" => ['ru'=>'url', 'en'=>'url'], 
			"ERROR_MESSAGE" => ['ru'=>'', 'en'=>''], 
			"HELP_MESSAGE" => ['ru'=>'', 'en'=>''],
		],
		'UF_SFF_KEYWORDS'=>[
			'ENTITY_ID' => $UFObject,
			'FIELD_NAME' => 'UF_SFF_KEYWORDS',
			'USER_TYPE_ID' => 'string',
			'MANDATORY' => 'N',
			"EDIT_FORM_LABEL" => ['ru'=>'keywords', 'en'=>'keywords'], 
			"LIST_COLUMN_LABEL" => ['ru'=>'keywords', 'en'=>'keywords'],
			"LIST_FILTER_LABEL" => ['ru'=>'keywords', 'en'=>'keywords'], 
			"ERROR_MESSAGE" => ['ru'=>'', 'en'=>''], 
			"HELP_MESSAGE" => ['ru'=>'', 'en'=>''],
		],
		'UF_SFF_DESCRIPTION'=>[
			'ENTITY_ID' => $UFObject,
			'FIELD_NAME' => 'UF_SFF_DESCRIPTION',
			'USER_TYPE_ID' => 'string',
			'MANDATORY' => 'N',
			"EDIT_FORM_LABEL" => ['ru'=>'description', 'en'=>'description'], 
			"LIST_COLUMN_LABEL" => ['ru'=>'description', 'en'=>'description'],
			"LIST_FILTER_LABEL" => ['ru'=>'description', 'en'=>'description'], 
			"ERROR_MESSAGE" => ['ru'=>'', 'en'=>''], 
			"HELP_MESSAGE" => ['ru'=>'', 'en'=>''],
		],
		'UF_SFF_TITLE'=>[
			'ENTITY_ID' => $UFObject,
			'FIELD_NAME' => 'UF_SFF_TITLE',
			'USER_TYPE_ID' => 'string',
			'MANDATORY' => 'N',
			"EDIT_FORM_LABEL" => ['ru'=>'title', 'en'=>'title'], 
			"LIST_COLUMN_LABEL" => ['ru'=>'title', 'en'=>'title'],
			"LIST_FILTER_LABEL" => ['ru'=>'title', 'en'=>'title'], 
			"ERROR_MESSAGE" => ['ru'=>'', 'en'=>''], 
			"HELP_MESSAGE" => ['ru'=>'', 'en'=>''],
		],
		'UF_SFF_HONE'=>[
			'ENTITY_ID' => $UFObject,
			'FIELD_NAME' => 'UF_SFF_HONE',
			'USER_TYPE_ID' => 'string',
			'MANDATORY' => 'N',
			"EDIT_FORM_LABEL" => ['ru'=>'h1', 'en'=>'h1'], 
			"LIST_COLUMN_LABEL" => ['ru'=>'h1', 'en'=>'h1'],
			"LIST_FILTER_LABEL" => ['ru'=>'h1', 'en'=>'h1'], 
			"ERROR_MESSAGE" => ['ru'=>'', 'en'=>''], 
			"HELP_MESSAGE" => ['ru'=>'', 'en'=>''],
		],
		'UF_SFF_ROBOTS'=>[
			'ENTITY_ID' => $UFObject,
			'FIELD_NAME' => 'UF_SFF_ROBOTS',
			'USER_TYPE_ID' => 'string',
			'MANDATORY' => 'N',
			"EDIT_FORM_LABEL" => ['ru'=>'robots', 'en'=>'robots'], 
			"LIST_COLUMN_LABEL" => ['ru'=>'robots', 'en'=>'robots'],
			"LIST_FILTER_LABEL" => ['ru'=>'robots', 'en'=>'robots'], 
			"ERROR_MESSAGE" => ['ru'=>'', 'en'=>''], 
			"HELP_MESSAGE" => ['ru'=>'', 'en'=>''],
		],
	];

	/**
	 * Название хайлоад-блока
	 */
	private string $hbName = 'SeoForFilter';

	/**
	 * Название таблицы хайлоад-блока
	 */
	private string $nbTableName = 'SEO_FOR_FILTER';

	/**
	 * Переменная для записи ошибок
	 */
	private array $errors = [];

	/**
	 * Параметры модуля
	 */
    public function sff() {
        $this->MODULE_NAME = 'SEO FOR FILTER';
        $this->MODULE_DESCRIPTION = 'Модуль SEO для страниц фильтрации';
        $this->MODULE_VERSION = '2.0.0';
        $this->MODULE_VERSION_DATE = '2022-10-26';
    }

	/**
	 * Устанавливаем модуль
	 */
    public function DoInstall() {
        RegisterModule($this->MODULE_ID);
		$this->installHB();
		$this->installEvents();
    }

	/**
	 * Удаляем модуль
	 */
    public function DoUninstall() {
        UnRegisterModule($this->MODULE_ID);
		$this->uninstallHB();
		$this->uninstallEvents();
    }


	/**
	 * Создаем хайлоад-блок
	 */
	private function createHB()
	{

		try {
			return HL\HighloadBlockTable::add([
				'NAME' => $this->hbName,
				'TABLE_NAME' => $this->nbTableName, 
			])->getId();
		} catch( Exception $e) {
			$this->errors[] = $result->getErrorMessages();
		}

		return false;
	}

	/**
	 * Создаем пользовательские свойства хайлоад-блока
	 */
	private function createHBFields($id){

		$UFObject = 'HLBLOCK_'.$id;
		$arSavedFieldsRes = [];

		foreach($this->arCartFields as $arCartField){
			$obUserField  = new CUserTypeEntity;
			$ID = $obUserField->Add($arCartField);
			$arSavedFieldsRes[] = $ID;
		}

	}
	
	/**
	 * Устанавливаем хайлоад-блок
	 */
	public function installHB(): bool
	{
		
		if ($id = $this->createHB()) {
			foreach($arLangs as $lang_key => $lang_val){
				HL\HighloadBlockLangTable::add([
					'ID' => $id,
					'LID' => $lang_key,
					'NAME' => $lang_val
				]);
			}

			$this->createHBFields($id);
		}

		return false;
	}
	
	/**
	 * Удаляем хайлоад-блок
	 */
	public function uninstallHB(){
		
		return true;
	}
	
	/**
	 * Регистрируем события
	 */
	public function installEvents(){
		
		EventManager::getInstance()->registerEventHandler(
            'main',
            'OnEndBufferContent',
            $this->MODULE_ID,
            'SFF\\Subs',
            'url_begin'
        );

		EventManager::getInstance()->registerEventHandler(
            'main',
            'OnEpilog',
            $this->MODULE_ID,
            'SFF\\Subs',
            'page_props'
        );
		
		return true;
	}
	
	/**
	 * Удаляем события
	 */
	public function uninstallEvents(){

		EventManager::getInstance()->unRegisterEventHandler(
            'main',
            'OnEndBufferContent',
            $this->MODULE_ID,
            'SFF\\Subs',
            'url_begin'
        );
		
		EventManager::getInstance()->unRegisterEventHandler(
            'main',
            'OnBeforeProlog',
            $this->MODULE_ID,
            'SFF\\Subs',
            'page_props'
        );
		
		return true;
	}
}

?>