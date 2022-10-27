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

	private $arLangs = [
		'ru' => 'Сео для фильтра',
		'en' => 'Seo for filter'
	];

	private string $hbName = 'SeoForFilter';

	private string $nbTableName = 'SEO_FOR_FILTER';

	private array $errors;

    public function sff() {
        $this->MODULE_NAME = 'SEO FOR FILTER';
        $this->MODULE_DESCRIPTION = 'Модуль SEO для страниц фильтрации';
        $this->MODULE_VERSION = '2.0.0';
        $this->MODULE_VERSION_DATE = '2022-10-26';
    }

    public function DoInstall() {
        RegisterModule($this->MODULE_ID);
		$this->installHB();
		$this->installEvents();
    }

    public function DoUninstall() {
        UnRegisterModule($this->MODULE_ID);
		$this->uninstallHB();
		$this->uninstallEvents();
    }

	}

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

	private function createHBFields($id){

		$UFObject = 'HLBLOCK_'.$id;
		$arSavedFieldsRes = [];

		$arCartFields = [
			'UF_SFF_URL'=>[
				'ENTITY_ID' => $UFObject,
				'FIELD_NAME' => 'UF_SFF_URL',
				'USER_TYPE_ID' => 'string',
				'MANDATORY' => 'Y',
				"EDIT_FORM_LABEL" => ['ru'=>'Ссылка', 'en'=>'URL'], 
				"LIST_COLUMN_LABEL" => ['ru'=>'Ссылка', 'en'=>'URL'],
				"LIST_FILTER_LABEL" => ['ru'=>'Ссылка', 'en'=>'URL'], 
				"ERROR_MESSAGE" => ['ru'=>'', 'en'=>''], 
				"HELP_MESSAGE" => ['ru'=>'', 'en'=>''],
			],
			'UF_SFF_KEYWORDS'=>[
				'ENTITY_ID' => $UFObject,
				'FIELD_NAME' => 'UF_SFF_KEYWORDS',
				'USER_TYPE_ID' => 'string',
				'MANDATORY' => 'N',
				"EDIT_FORM_LABEL" => ['ru'=>'ИД Блюда', 'en'=>'Dish ID'], 
				"LIST_COLUMN_LABEL" => ['ru'=>'ИД Блюда', 'en'=>'Dish ID'],
				"LIST_FILTER_LABEL" => ['ru'=>'ИД Блюда', 'en'=>'Dish ID'], 
				"ERROR_MESSAGE" => ['ru'=>'', 'en'=>''], 
				"HELP_MESSAGE" => ['ru'=>'', 'en'=>''],
			],
			'UF_SFF_DESCRIPTION'=>[
				'ENTITY_ID' => $UFObject,
				'FIELD_NAME' => 'UF_SFF_DESCRIPTION',
				'USER_TYPE_ID' => 'string',
				'MANDATORY' => 'N',
				"EDIT_FORM_LABEL" => ['ru'=>'Цена', 'en'=>'Price'], 
				"LIST_COLUMN_LABEL" => ['ru'=>'Цена', 'en'=>'Price'],
				"LIST_FILTER_LABEL" => ['ru'=>'Цена', 'en'=>'Price'], 
				"ERROR_MESSAGE" => ['ru'=>'', 'en'=>''], 
				"HELP_MESSAGE" => ['ru'=>'', 'en'=>''],
			],
			'UF_SFF_TITLE'=>[
				'ENTITY_ID' => $UFObject,
				'FIELD_NAME' => 'UF_SFF_TITLE',
				'USER_TYPE_ID' => 'string',
				'MANDATORY' => 'N',
				"EDIT_FORM_LABEL" => ['ru'=>'Цена', 'en'=>'Price'], 
				"LIST_COLUMN_LABEL" => ['ru'=>'Цена', 'en'=>'Price'],
				"LIST_FILTER_LABEL" => ['ru'=>'Цена', 'en'=>'Price'], 
				"ERROR_MESSAGE" => ['ru'=>'', 'en'=>''], 
				"HELP_MESSAGE" => ['ru'=>'', 'en'=>''],
			],
			'UF_SFF_HONE'=>[
				'ENTITY_ID' => $UFObject,
				'FIELD_NAME' => 'UF_SFF_HONE',
				'USER_TYPE_ID' => 'string',
				'MANDATORY' => 'N',
				"EDIT_FORM_LABEL" => ['ru'=>'Цена', 'en'=>'Price'], 
				"LIST_COLUMN_LABEL" => ['ru'=>'Цена', 'en'=>'Price'],
				"LIST_FILTER_LABEL" => ['ru'=>'Цена', 'en'=>'Price'], 
				"ERROR_MESSAGE" => ['ru'=>'', 'en'=>''], 
				"HELP_MESSAGE" => ['ru'=>'', 'en'=>''],
			],
			'UF_SFF_ROBOTS'=>[
				'ENTITY_ID' => $UFObject,
				'FIELD_NAME' => 'UF_SFF_ROBOTS',
				'USER_TYPE_ID' => 'string',
				'MANDATORY' => 'N',
				"EDIT_FORM_LABEL" => ['ru'=>'Цена', 'en'=>'Price'], 
				"LIST_COLUMN_LABEL" => ['ru'=>'Цена', 'en'=>'Price'],
				"LIST_FILTER_LABEL" => ['ru'=>'Цена', 'en'=>'Price'], 
				"ERROR_MESSAGE" => ['ru'=>'', 'en'=>''], 
				"HELP_MESSAGE" => ['ru'=>'', 'en'=>''],
			],
		];

		foreach($arCartFields as $arCartField){
			$obUserField  = new CUserTypeEntity;
			$ID = $obUserField->Add($arCartField);
			$arSavedFieldsRes[] = $ID;
		}

	}
	
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
	
	public function uninstallHB(){
		
		return true;
	}
	
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