<?

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;

Class sff extends CModule
{
    public $MODULE_ID = 'sff';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;

    public function sff() {
        $this->MODULE_NAME = 'SEO FOR FILTER';
        $this->MODULE_DESCRIPTION = 'Модуль SEO для страниц фильтрации';
        $this->MODULE_VERSION = '1.0.0';
        $this->MODULE_VERSION_DATE = '2019-01-20';
    }

    public function DoInstall() {
        RegisterModule($this->MODULE_ID);
		$this->installFiles();
		$this->installDB();
		$this->installEvents();
    }

    public function DoUninstall() {
        UnRegisterModule($this->MODULE_ID);
		$this->uninstallFiles();
		$this->uninstallDB();
		$this->uninstallEvents();
    }
	
	public function installFiles(){
		
		/*
		** Create files
		*/
		
		file_put_contents(
			$_SERVER['DOCUMENT_ROOT']."/bitrix/admin/seomenu.php", 
			"<?"."\n".'require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sff/admin/seomenu.php");'."\n"."?>"
		);
		
		return true;
	}
	
	public function installDB(){
		
		/*
		** Create tables on DB
		*/
		
		global $DB, $DBType;
		$errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sff/install/".$DBType."/install.sql");
		return true;
	}
	
	public function uninstallFiles(){
		
		/*
		** Delete files
		*/
		
		unlink($_SERVER['DOCUMENT_ROOT']."/bitrix/admin/seomenu.php");
		return true;
	}
	
	public function uninstallDB(){
		
		/*
		** Delete tables from DB
		*/
		
		global $DB, $DBType;
		$errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sff/install/".$DBType."/uninstall.sql");
		return true;
	}
	
	public function installEvents(){
		
		/*
		** Module events
		*/
		
		//event for buffer content
		EventManager::getInstance()->registerEventHandler(
            'main',
            'OnEndBufferContent',
            $this->MODULE_ID,
            'SFF\\Subs',
            'url_begin'
        );
		
		//event for application
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
		
		/*
		**
		*/
		
		//event for buffer content
		EventManager::getInstance()->unRegisterEventHandler(
            'main',
            'OnEndBufferContent',
            $this->MODULE_ID,
            'SFF\\Subs',
            'url_begin'
        );
		
		//event for application
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