<table>
<tr>
	<form method="POST" action="/bitrix/admin/seomenu.php">
		<td><input type="text" name="link" value=""></td>
		<td><input type="submit" name="search" value="Поиск"></td>
	</form>
</tr>
<tr>
	<th>url</th>
	<th>keywords</th>
	<th>description</th>
	<th>title</th>
	<th>h1</th>
	<th>robots</th>
	<th>Действие</th>
	<th>Удалить</th>
</tr>
<tr>
<?if(!empty($msg)){?>
	<td>
		<font color="red"><?=$msg?></font>
	</td>
<?}?>
</tr>
<tr class="new">
	<form method="POST" action="/bitrix/admin/seomenu.php">
	<td><input type="text"   name="url"         value="<?=!empty($msg)?$_POST['url']:'';?>"         placeholder="/contacts/"         ></td>
	<td><input type="text"   name="keywords"    value="<?=!empty($msg)?$_POST['keywords']:'';?>"    placeholder="слово1, слово2"     ></td>
	<td><input type="text"   name="description" value="<?=!empty($msg)?$_POST['description']:'';?>" placeholder="описание странички" ></td>
	<td><input type="text"   name="title"       value="<?=!empty($msg)?$_POST['title']:'';?>"       placeholder="Контакты"           ></td>
	<td><input type="text"   name="hone"        value="<?=!empty($msg)?$_POST['hone']:'';?>"        placeholder="Заголовок"          ></td>
	<td><input type="text"   name="robots"      value="<?=!empty($msg)?$_POST['robots']:'';?>"      placeholder="index, follow"      ></td>
	<td><input type="submit" name="add_f"       value="Добавить"                                                                     ></td>
	</form>
</tr>
<?foreach($links as $link){?>
<tr class="old">
	<form method="POST" action="/bitrix/admin/seomenu.php?id=<?=$link['filter_id']?>">
	<td><input type="text"   name="url"         value="<?=$link['url']?>"         ></td>
	<td><input type="text"   name="keywords"    value="<?=$link['keywords']?>"    ></td>
	<td><input type="text"   name="description" value="<?=$link['description']?>" ></td>
	<td><input type="text"   name="title"       value="<?=$link['title']?>"       ></td>
	<td><input type="text"   name="hone"        value="<?=$link['hone']?>"        ></td>
	<td><input type="text"   name="robots"      value="<?=$link['robots']?>"      ></td>
	<td><input type="submit" name="update_f"    value="Изменить"                  ></td>
	</form>
	<form method="POST" action="/bitrix/admin/seomenu.php?id=<?=$link['filter_id']?>">
	<td><input type="submit" name="delete_f"    value="Удалить"                   ></td>
	</form>
</tr>
<?}?>
<table>


<div id="pagination">
    <?if($_GET['page'] != 1){?>
      <a href="/bitrix/admin/seomenu.php?page=1" title="Первая страница">&lt;&lt;&lt;</a>
      <a href="<?php if ($_GET['page'] == 2){?>/bitrix/admin/seomenu.php?page=<?php } else { ?><?=($_GET['page'] - 1)?><?php } ?>" title="Предыдущая страница">&lt;</a>
    <?}?>
	<?for($i = 1; $i <= $pages; $i++){?>
		<?if($i == $_GET['page']){?>
			<span><?=$i?></span>
		<?}else{?>
			<a href="/bitrix/admin/seomenu.php?page=<?=$i?>"> <?=$i?></a>
		<?}?>
    <?}?>
    <?if ($_GET['page'] != $pages){?>
      <a href="/bitrix/admin/seomenu.php?page=<?=($_GET['page']+1)?>" title="Следующая страница">&gt;</a>
      <a href="/bitrix/admin/seomenu.php?page=<?=$pages?>" title="Последняя страница">&gt;&gt;&gt;</a>
    <?}?>
  </div>
