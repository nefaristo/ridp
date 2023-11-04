<?php session_start(); ?>
<?php
  $_SESSION['my_user'] = "rdpadmin";
  $_SESSION['my_pass'] = "rdpadmin419132";
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<style type="text/css">
body {
    background: #FFFFFF;
    color: #000000;
    font-family: Arial;
    font-size: 12pt;
}
A:link { color: #FF0000 }
A:visited { color: #800080 }
A:active { color: #0000FF }
.ThRows {
    background-color: #068FE6;
    color: #FFFFFF;
    font-weight: bold; text-align: center;
    font-family: Arial;
    font-size: 12pt;
}
.TrRows {
    background-color: #FFFFFF;
    color: #000000;
    font-family: Arial;
    font-size: 12pt;
}
.TrOdd  {
    background-color: #FFFCD9;
    color: #000000;
    font-family: Arial;
    font-size: 12pt;
}
.TrBC { background-color: #000000 }
</style>
</head>
<body>
<table width="100%"><tr><td class="ThRows">
Header text
</td></tr></table>

<?php
  $conn = connect();
  $showrecs = 200;
  $pagerange = 10;
  $a = @$_GET["a"];
  $recid = @$_GET["recid"];
  $page = @$_GET["page"];
  if (!isset($page)) $page = 1;

  $sql = @$_POST["sql"];

  switch ($sql) {
    case "insert":
      sql_insert();
      break;
    case "update":
      sql_update();
      break;
    case "delete":
      sql_delete();
      break;
  }

  switch ($a) {
    case "add":
      addrec();
      break;
    case "view":
      viewrec($recid);
      break;
    case "edit":
      editrec($recid);
      break;
    case "del":
      deleterec($recid);
      break;
    default:
      select();
      break;
  }
  mysql_close($conn);
?>
<table width="100%"><tr><td class="ThRows">
Footer text
</td></tr></table>

</body>
</html>
<?php
function connect()
{
  $c = mysql_connect("localhost:3306", $_SESSION['my_user'], $_SESSION['my_pass']);
  mysql_select_db("rdp");
  return $c;
}
?>
<?php
function sql_getrecordcount()
{
  global $conn;
  $sql = "select count(*) from `_s_tables`";
  $res = mysql_query($sql, $conn) or die(mysql_error());
  $row = mysql_fetch_assoc($res);
  reset($row);
  return current($row);
}
?>
<?php
function sql_select()
{
  global $conn;
  $sql = "SELECT * FROM `_s_tables`;";
  $res = mysql_query($sql, $conn) or die(mysql_error());
  return $res;
}

function select(){
  global $showrecs;
  global $pagerange;
  global $page;
  global $conn;
  $res = sql_select();
  $count = sql_getrecordcount();
  if ($count % $showrecs != 0) {
    $pagecount = intval($count / $showrecs) + 1;
  }
  else {
    $pagecount = intval($count / $showrecs);
  }
  $startrec = $showrecs * ($page - 1);
  if ($startrec < $count) {mysql_data_seek($res, $startrec);}
  $reccount = min($showrecs * $page, $count);
?>
<?php showpagenav($page, $pagecount); ?>
<table width="100%" border="0" cellpadding="4" cellspacing="1">
  <tr>
    <td class="ThRows">&nbsp;</td>
    <td class="ThRows">&nbsp;</td>
    <td class="ThRows">&nbsp;</td>
    <td class="ThRows">id</td>
    <td class="ThRows">parent</td>
    <td class="ThRows">treatment_type</td>
    <td class="ThRows">name</td>
    <td class="ThRows">order</td>
    <td class="ThRows">type</td>
    <td class="ThRows">level</td>
    <td class="ThRows">name_en</td>
    <td class="ThRows">name_it</td>
    <td class="ThRows">desc_it</td>
    <td class="ThRows">desc_en</td>
    <td class="ThRows">filter_on_parent</td>
    <td class="ThRows">notes</td>
  </tr>
<?php
if(mysql_num_rows($res)) {
  for ($i = $startrec; $i < $reccount; $i++)
  {
    $row = mysql_fetch_assoc($res);
    $s = "TrOdd";
    if ($i % 2 == 0) {
      $s = "TrRows";
    }
?>
  <tr>
    <td class="<?php echo $s?>" width = "16"><a href="_s_tables.php?a=view&recid=<?php echo $i ?>" ><img src="ems_php_images\phpview.jpg" title="View record" border="0"></a></td>
    <td class="<?php echo $s?>" width = "16"><a href="_s_tables.php?a=edit&recid=<?php echo $i ?>" ><img src="ems_php_images\phpedit.jpg" title="Edit record" border="0"></a></td>
    <td class="<?php echo $s?>" width = "16"><a href="_s_tables.php?a=del&recid=<?php echo $i ?>" onclick="return confirm('Do you really want to delete row?')"><img src="ems_php_images\phpdrop.jpg" title="Delete record" border="0"></a></td>
    <td class="<?php echo $s?>"><?php echo htmlspecialchars($row['id'])?></td>
    <td class="<?php echo $s?>"><?php echo htmlspecialchars($row['parent'])?></td>
    <td class="<?php echo $s?>"><?php echo htmlspecialchars($row['treatment_type'])?></td>
    <td class="<?php echo $s?>"><?php echo htmlspecialchars($row['name'])?></td>
    <td class="<?php echo $s?>"><?php echo htmlspecialchars($row['order'])?></td>
    <td class="<?php echo $s?>"><?php echo htmlspecialchars($row['type'])?></td>
    <td class="<?php echo $s?>"><?php echo htmlspecialchars($row['level'])?></td>
    <td class="<?php echo $s?>"><?php echo htmlspecialchars($row['name_en'])?></td>
    <td class="<?php echo $s?>"><?php echo htmlspecialchars($row['name_it'])?></td>
    <td class="<?php echo $s?>"><?php echo htmlspecialchars($row['desc_it'])?></td>
    <td class="<?php echo $s?>"><?php echo htmlspecialchars($row['desc_en'])?></td>
    <td class="<?php echo $s?>"><?php echo htmlspecialchars($row['filter_on_parent'])?></td>
    <td class="<?php echo $s?>"><?php echo htmlspecialchars($row['notes'])?></td>
  </tr>
<?php }mysql_free_result($res);}?>
</table>
<?php showpagenav($page, $pagecount); ?>
<?php }?>
<?php function showpagenav($page, $pagecount){ ?>
<table border="0" width="100%"
<tr>
<td><a href="_s_tables.php?a=add">Add record<br></a>
<?php if ($page > 1) { ?>
<a href="_s_tables.php?page=<?php echo $page - 1 ?>">&lt;&lt;&nbsp;Prev</a>&nbsp;
<?php } ?>
<?php
global $pagerange;
if ($pagecount > 1) {
  if ($pagecount % $pagerange != 0)
    $rangecount = intval($pagecount / $pagerange) + 1;
  else
    $rangecount = intval($pagecount / $pagerange);
  for ($i = 1; $i < $rangecount + 1; $i++) {
    $startpage = (($i - 1) * $pagerange) + 1;
    $count = min($i * $pagerange, $pagecount);
    if ((($page >= $startpage) && ($page <= ($i * $pagerange)))) {
      for ($j = $startpage; $j < $count + 1; $j++) {
        if ($j == $page) {
?>
<b><?php echo $j ?></b>
<?php } else { ?>
<a href="_s_tables.php?page=<?php echo $j ?>"><?php echo $j ?></a>
<?php } } } else { ?>
<a href="_s_tables.php?page=<?php echo $startpage ?>"><?php echo $startpage ."..." .$count ?></a>
<?php } } } ?>
<?php if ($page < $pagecount) { ?>
&nbsp;<a href="_s_tables.php?page=<?php echo $page + 1 ?>">Next&nbsp;&gt;&gt;</a>&nbsp;</td>
<?php } ?>
</tr>
</table>
<?php } ?>

<?php function showrecnav($a, $recid, $count)
{
?>
<table border="0" cellspacing="1" cellpadding="4">
<tr>
<td><a href="_s_tables.php">Index Page</a></td>
<?php if ($recid > 0) { ?>
<td><a href="_s_tables.php?a=<?php echo $a ?>&recid=<?php echo $recid - 1 ?>">Prior Record</a></td>
<?php } if ($recid < $count - 1) { ?>
<td><a href="_s_tables.php?a=<?php echo $a ?>&recid=<?php echo $recid + 1 ?>">Next Record</a></td>
<?php } ?>
</tr>
</table>
<hr size="1" noshade>
<?php } ?>

<?php function showrow($row, $recid)
  {
?> 
<table border="0" cellspacing="1" cellpadding="5" width="50%">
<tr>
<td class="ThRows"><?php echo htmlspecialchars("id")."&nbsp;" ?></td>
<td class="TrRows"><?php echo htmlspecialchars($row["id"]) ?></td>
</tr>
<tr>
<td class="ThRows"><?php echo htmlspecialchars("parent")."&nbsp;" ?></td>
<td class="TrOdd"><?php echo htmlspecialchars($row["parent"]) ?></td>
</tr>
<tr>
<td class="ThRows"><?php echo htmlspecialchars("treatment_type")."&nbsp;" ?></td>
<td class="TrRows"><?php echo htmlspecialchars($row["treatment_type"]) ?></td>
</tr>
<tr>
<td class="ThRows"><?php echo htmlspecialchars("name")."&nbsp;" ?></td>
<td class="TrOdd"><?php echo htmlspecialchars($row["name"]) ?></td>
</tr>
<tr>
<td class="ThRows"><?php echo htmlspecialchars("order")."&nbsp;" ?></td>
<td class="TrRows"><?php echo htmlspecialchars($row["order"]) ?></td>
</tr>
<tr>
<td class="ThRows"><?php echo htmlspecialchars("type")."&nbsp;" ?></td>
<td class="TrOdd"><?php echo htmlspecialchars($row["type"]) ?></td>
</tr>
<tr>
<td class="ThRows"><?php echo htmlspecialchars("level")."&nbsp;" ?></td>
<td class="TrRows"><?php echo htmlspecialchars($row["level"]) ?></td>
</tr>
<tr>
<td class="ThRows"><?php echo htmlspecialchars("name_en")."&nbsp;" ?></td>
<td class="TrOdd"><?php echo htmlspecialchars($row["name_en"]) ?></td>
</tr>
<tr>
<td class="ThRows"><?php echo htmlspecialchars("name_it")."&nbsp;" ?></td>
<td class="TrRows"><?php echo htmlspecialchars($row["name_it"]) ?></td>
</tr>
<tr>
<td class="ThRows"><?php echo htmlspecialchars("desc_it")."&nbsp;" ?></td>
<td class="TrOdd"><?php echo htmlspecialchars($row["desc_it"]) ?></td>
</tr>
<tr>
<td class="ThRows"><?php echo htmlspecialchars("desc_en")."&nbsp;" ?></td>
<td class="TrRows"><?php echo htmlspecialchars($row["desc_en"]) ?></td>
</tr>
<tr>
<td class="ThRows"><?php echo htmlspecialchars("filter_on_parent")."&nbsp;" ?></td>
<td class="TrOdd"><?php echo htmlspecialchars($row["filter_on_parent"]) ?></td>
</tr>
<tr>
<td class="ThRows"><?php echo htmlspecialchars("notes")."&nbsp;" ?></td>
<td class="TrRows"><?php echo htmlspecialchars($row["notes"]) ?></td>
</tr>
</table>
<?php } ?>

<?php
function select_fk_keys($tablename, $fieldname){
  global $conn;
  $sql = "SELECT $fieldname FROM $tablename;";
  $res = mysql_query($sql, $conn) or die(mysql_error());
  return $res;
}
?>
<?php function showroweditor($row, $iseditmode)
  {
?>
<table border="0" cellspacing="1" cellpadding="5"width="50%">
<tr>
<td class="ThRows"><?php echo htmlspecialchars("id")."&nbsp;" ?></td>
<td class="TrRows">
<input type="text" name="id" value="<?php echo str_replace('"', '&quot;', trim($row["id"])) ?>"></td>
</tr>
<tr>
<td class="ThRows"><?php echo htmlspecialchars("parent")."&nbsp;" ?></td>
<td class="TrRows">
<input type="text" name="parent" value="<?php echo str_replace('"', '&quot;', trim($row["parent"])) ?>"></td>
</tr>
<tr>
<td class="ThRows"><?php echo htmlspecialchars("treatment_type")."&nbsp;" ?></td>
<td class="TrRows">
<textarea name="treatment_type" cols="36" rows="4"><?php echo str_replace('"', '&quot;', trim($row["treatment_type"]))?></textarea></td>
</tr>
<tr>
<td class="ThRows"><?php echo htmlspecialchars("name")."&nbsp;" ?></td>
<td class="TrRows">
<textarea name="name" cols="36" rows="4"><?php echo str_replace('"', '&quot;', trim($row["name"]))?></textarea></td>
</tr>
<tr>
<td class="ThRows"><?php echo htmlspecialchars("order")."&nbsp;" ?></td>
<td class="TrRows">
<input type="text" name="order" value="<?php echo str_replace('"', '&quot;', trim($row["order"])) ?>"></td>
</tr>
<tr>
<td class="ThRows"><?php echo htmlspecialchars("type")."&nbsp;" ?></td>
<td class="TrRows">
<textarea name="type" cols="36" rows="4"><?php echo str_replace('"', '&quot;', trim($row["type"]))?></textarea></td>
</tr>
<tr>
<td class="ThRows"><?php echo htmlspecialchars("level")."&nbsp;" ?></td>
<td class="TrRows">
<input type="text" name="level" value="<?php echo str_replace('"', '&quot;', trim($row["level"])) ?>"></td>
</tr>
<tr>
<td class="ThRows"><?php echo htmlspecialchars("name_en")."&nbsp;" ?></td>
<td class="TrRows">
<textarea name="name_en" cols="36" rows="4"><?php echo str_replace('"', '&quot;', trim($row["name_en"]))?></textarea></td>
</tr>
<tr>
<td class="ThRows"><?php echo htmlspecialchars("name_it")."&nbsp;" ?></td>
<td class="TrRows">
<textarea name="name_it" cols="36" rows="4"><?php echo str_replace('"', '&quot;', trim($row["name_it"]))?></textarea></td>
</tr>
<tr>
<td class="ThRows"><?php echo htmlspecialchars("desc_it")."&nbsp;" ?></td>
<td class="TrRows">
<input type="text" name="desc_it" value="<?php echo str_replace('"', '&quot;', trim($row["desc_it"])) ?>"></td>
</tr>
<tr>
<td class="ThRows"><?php echo htmlspecialchars("desc_en")."&nbsp;" ?></td>
<td class="TrRows">
<input type="text" name="desc_en" value="<?php echo str_replace('"', '&quot;', trim($row["desc_en"])) ?>"></td>
</tr>
<tr>
<td class="ThRows"><?php echo htmlspecialchars("filter_on_parent")."&nbsp;" ?></td>
<td class="TrRows">
<textarea name="filter_on_parent" cols="36" rows="4"><?php echo str_replace('"', '&quot;', trim($row["filter_on_parent"]))?></textarea></td>
</tr>
<tr>
<td class="ThRows"><?php echo htmlspecialchars("notes")."&nbsp;" ?></td>
<td class="TrRows">
<input type="text" name="notes" value="<?php echo str_replace('"', '&quot;', trim($row["notes"])) ?>"></td>
</tr>
</tr>
</table>
<?php } ?>

<?php function addrec()
{
?>
<table border="0" cellspacing="1" cellpadding="4">
<tr>
<td><a href="_s_tables.php">Index Page</a></td>
</tr>
</table>
<hr size="1" noshade>
<form enctype="multipart/form-data" action="_s_tables.php" method="post">
<p><input type="hidden" name="sql" value="insert"></p>
<?php
$row = array(
  "id" => "",
  "parent" => "",
  "treatment_type" => "",
  "name" => "",
  "order" => "",
  "type" => "",
  "level" => "",
  "name_en" => "",
  "name_it" => "",
  "desc_it" => "",
  "desc_en" => "",
  "filter_on_parent" => "",
  "notes" => "");
showroweditor($row, false);
?>
<p><input type="submit" name="action" value="Post"></p>
</form>
<?php } ?>

<?php function viewrec($recid)
{
$res = sql_select();
  $count = sql_getrecordcount();
  mysql_data_seek($res, $recid);
  $row = mysql_fetch_assoc($res);
  showrecnav("view", $recid, $count);
?>
<br>
<?php showrow($row, $recid) ?>
<br>
<hr size="1" noshade>
<table border="0" cellspacing="1" cellpadding="4">
<tr>
<td><a href="_s_tables.php?a=add">Add record</a></td>
<td><a href="_s_tables.php?a=edit&recid=<?php echo $recid ?>">Edit record</a></td>
<td><a href="_s_tables.php?a=del&recid=<?php echo $recid ?>">Delete record</a></td>
</tr>
</table>
<?php
  mysql_free_result($res);
} ?>

<?php function editrec($recid)
{
  $res = sql_select();
  $count = sql_getrecordcount();
  mysql_data_seek($res, $recid);
  $row = mysql_fetch_assoc($res);
  showrecnav("edit", $recid, $count);
?>
<br>
<form enctype="multipart/form-data" action="_s_tables.php" method="post">
<input type="hidden" name="sql" value="update">
<input type="hidden" name="xid" value="<?php echo $row["id"] ?>">
<?php showroweditor($row, true); ?>
<p><input type="submit" name="action" value="Post"></p>
</form>
<?php
  mysql_free_result($res);
} ?>
<?php function deleterec($recid)
{
  $res = sql_select();
  $count = sql_getrecordcount();
  mysql_data_seek($res, $recid);
  $row = mysql_fetch_assoc($res);
?>
<br>
<form name="delete_form" action="_s_tables.php" method="post">
<input type="hidden" name="sql" value="delete">
<input type="hidden" name="xid" value="<?php echo $row["id"] ?>">
<script type="text/javascript">
  document.getElementById("delete_form").submit();
</script>
</form>
<?php
  mysql_free_result($res);
} ?>
<?php
function sqlvalue($val, $quote)
{
  if ($quote)
    $tmp = sqlstr($val);
  else
    $tmp = $val;
  if ($tmp == "")
    $tmp = "NULL";
  elseif ($quote)
    $tmp = "'".$tmp."'";
  return $tmp;
}

function sqlstr($val)
{
  return str_replace("'", "''", $val);
}

function primarykeycondition()
{
  global $_POST;
  $pk = "";
  $pk .= "(`id`";
  if (@$_POST["xid"] == "") {
    $pk .= " IS NULL";
  } else {
  $pk .= " = " .sqlvalue(@$_POST["xid"], false);
  };
  $pk .= ")";
  return $pk;
}

function sql_insert()
{
  global $conn;
  global $_POST;
  $sql = "insert into `_s_tables` (`id`, `parent`, `treatment_type`, `name`, `order`, `type`, `level`, `name_en`, `name_it`, `desc_it`, `desc_en`, `filter_on_parent`, `notes`) values (".sqlvalue(@$_POST["id"], false).", ".sqlvalue(@$_POST["parent"], false).", ".sqlvalue(@$_POST["treatment_type"], true).", ".sqlvalue(@$_POST["name"], true).", ".sqlvalue(@$_POST["order"], false).", ".sqlvalue(@$_POST["type"], true).", ".sqlvalue(@$_POST["level"], false).", ".sqlvalue(@$_POST["name_en"], true).", ".sqlvalue(@$_POST["name_it"], true).", ".sqlvalue(@$_POST["desc_it"], true).", ".sqlvalue(@$_POST["desc_en"], true).", ".sqlvalue(@$_POST["filter_on_parent"], true).", ".sqlvalue(@$_POST["notes"], true).")";
  mysql_query($sql, $conn) or die(mysql_error());
}
?>
<?php
function sql_update()
{
  global $conn;
  global $_POST;
  $sql = "update `_s_tables` set `id`=".sqlvalue(@$_POST["id"], false).", `parent`=".sqlvalue(@$_POST["parent"], false).", `treatment_type`=".sqlvalue(@$_POST["treatment_type"], true).", `name`=".sqlvalue(@$_POST["name"], true).", `order`=".sqlvalue(@$_POST["order"], false).", `type`=".sqlvalue(@$_POST["type"], true).", `level`=".sqlvalue(@$_POST["level"], false).", `name_en`=".sqlvalue(@$_POST["name_en"], true).", `name_it`=".sqlvalue(@$_POST["name_it"], true).", `desc_it`=".sqlvalue(@$_POST["desc_it"], true).", `desc_en`=".sqlvalue(@$_POST["desc_en"], true).", `filter_on_parent`=".sqlvalue(@$_POST["filter_on_parent"], true).", `notes`=".sqlvalue(@$_POST["notes"], true)." where " .primarykeycondition();
  mysql_query($sql, $conn) or die(mysql_error());
}
?>
<?php
function sql_delete()
{
  global $conn;
  $sql = "delete from `_s_tables` where " .primarykeycondition();
  mysql_query($sql, $conn) or die(mysql_error());
}
?>
