<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>
<?
global $DB;

if(isset($_POST['add_f']) && !empty($_POST['add_f'])){
	if(!empty($_POST['url'])){
		$row = $DB->Query("
			SELECT
				*
			FROM
				seo_for_filter
			WHERE
				url = '".$_POST['url']."'
			;",true, $err_mess.__LINE__
		);
		if($res = $row->Fetch()){
			$msg = 'Такой адрес уже существует';
		}else{
			$DB->Query("
				INSERT INTO 
					seo_for_filter(
						url,
						keywords,
						description,
						title,
						hone,
						robots
						) 
				VALUES(
					'".$_POST['url']."',
					'".$_POST['keywords']."',
					'".$_POST['description']."',
					'".$_POST['title']."',
					'".$_POST['hone']."',
					'".$_POST['robots']."'
					)
				;", true, $err_mess.__LINE__
			);
		}
	}else{
		$msg = 'Заполните поле url';
	}
}elseif(isset($_POST['delete_f']) && !empty($_POST['delete_f'])){
	$DB->Query("
		DELETE FROM 
			seo_for_filter 
		WHERE 
			filter_id='".$_GET['id']."'
		;", true, $err_mess.__LINE__
	);
}elseif(isset($_POST['update_f']) && !empty($_POST['update_f'])){
	$DB->Query("
		UPDATE 
			seo_for_filter
		SET
			url         = '".$_POST['url']."',
			keywords    = '".$_POST['keywords']."',
			description = '".$_POST['description']."',
			title       = '".$_POST['title']."',
			hone        = '".$_POST['hone']."',
			robots      = '".$_POST['robots']."'
		WHERE
			filter_id='".$_GET['id']."'
		;",true, $err_mess.__LINE__
	);
}elseif(isset($_POST['search']) && !empty($_POST['link'])){
	$row = $DB->Query("
		SELECT
			*
		FROM
			seo_for_filter
		WHERE
			url = '".$_POST['link']."'
		;",true, $err_mess.__LINE__
	);
	$links = array();
	while ($res = $row->Fetch()){
		$links[] = $res;
	}
}


$co = $DB->Query("
		SELECT 
			COUNT(*) 
		FROM 
			seo_for_filter
		;", true, $err_mess.__LINE__
	)->Fetch();
$count = $co['COUNT(*)'];
$pages = ($count/10)+1;
$pages = (int)$pages;


if(!isset($_POST['search'])){
	if(!isset($_GET['page']) || $_GET['page'] == 0){
		$row = $DB->Query("
			SELECT 
				* 
			FROM 
				seo_for_filter
			WHERE 
				filter_id
			ORDER BY 
				filter_id DESC
			LIMIT 
				10
			OFFSET
				0
			;", true, $err_mess.__LINE__
		);
		$links = array();
		while ($res = $row->Fetch()){
			$links[] = $res;
		}
	}else if(isset($_GET['page'])){
		$row = $DB->Query("
			SELECT 
				* 
			FROM 
				seo_for_filter 
			WHERE 
				filter_id
			ORDER BY 
				filter_id DESC
			LIMIT 
				10
			OFFSET
				".($_GET['page'] - 1)."0
			;", true, $err_mess.__LINE__
		);
		$links = array();
		while ($res = $row->Fetch()){
			$links[] = $res;
		}
	}
}

include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sff/admin/template.php");
?>