<?
namespace SFF;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Application;
use \Bitrix\Main\Entity\Event;

class Subs
{
    public static function url_begin(&$content)
	{
		$meta = self::db_get(explode('?', $_SERVER['REQUEST_URI'])[0]);
		if($meta){
			if(!empty($meta['hone'])){$content = preg_replace('/<h1*([^>]+)>.*?<\/h1>/m', '<h1>'.$meta['hone'].'</h1>', $content);}
			if(!empty($meta['description'])){$content = preg_replace('/<meta name="description" *([^>]+)>/m', '<meta name="description" content="'.$meta['description'].'">', $content);}
			if(!empty($meta['keywords'])){$content = preg_replace('/<meta name="keywords" *([^>]+)>/m', '<meta name="keywords" content="'.$meta['keywords'].'">', $content);}
			if(!empty($meta['robots'])){$content = preg_replace('/<meta name="robots" *([^>]+)>/m', '<meta name="robots" content="'.$meta['robots'].'">', $content);}
			if(!empty($meta['title'])){$content = preg_replace('/<title*([^>]+)>.*?<\/title>/m', '<title>'.$meta['title'].'</title>', $content);}
		}
    }
	
	public static function page_props(){
		global $APPLICATION;
		$meta = self::db_get(explode('?', $_SERVER['REQUEST_URI'])[0]);
		if($meta){
			if(!empty($meta['title']))$APPLICATION->SetPageProperty("title", $meta['title']);
			if(!empty($meta['keywords']))$APPLICATION->SetPageProperty("keywords", $meta['keywords']);
			if(!empty($meta['description']))$APPLICATION->SetPageProperty("description", $meta['description']);
			if(!empty($meta['robots']))$APPLICATION->SetPageProperty("robots", $meta['robots']);
            if(!empty($meta['hone']))$APPLICATION->SetTitle($meta['hone']);

        }
	}
	
	public static function db_get($current_url){
		global $DB;
		$meta_temp = $DB->Query("
			SELECT 
				*
			FROM 
				seo_for_filter
			WHERE
				url = '".$current_url."'
			;", false, $err_mess.__LINE__
		);
		if($meta = $meta_temp->Fetch()){
			return $meta;
		}else{
			return false;
		}
	}
}

?>