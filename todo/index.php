<?php
session_start();

# Handy function from:
# http://us2.php.net/manual/en/function.stripslashes.php#79976
if (get_magic_quotes_gpc()) {
	function stripslashes_deep($value) {
		$value = is_array($value) ?
			array_map('stripslashes_deep', $value) :
			stripslashes($value);

		return $value;
	}

	$_POST = array_map('stripslashes_deep', $_POST);
	$_GET = array_map('stripslashes_deep', $_GET);
	$_COOKIE = array_map('stripslashes_deep', $_COOKIE);
	$_REQUEST = array_map('stripslashes_deep', $_REQUEST);
}

if ( !isset($_SESSION['items']) ) {
	$_SESSION['items'] = array();
}

$item = preg_replace("/</", "&lt;", $_REQUEST['item']);

if ( $item ) {
	array_push( $_SESSION['items'], $item );
}

if ( $_REQUEST['items'] ) {
	$_SESSION['items'] = $_REQUEST['items'];
}

$items = $_SESSION['items'];
$action = $_REQUEST['action'];

if ( $action == 'reset' ) {
	$_SESSION['todo_head'] = "";
	reset_items();
	home();
} else if ( $action == 'done' ) {
	$_SESSION['todo_head'] = file_get_contents("code.txt");
	reset_items();
	home();
} else if ( $action == "do_edit" ) {
	$_SESSION['todo_head'] = $_REQUEST['edit'];
	$action = "edit";
}

function reset_items() {
	$_SESSION['items'] = array();
}

function home() {
	header("Location: ./");
	exit();
}
?>
<html>
<head>
	<title>Todo List</title>
	<link rel="stylesheet" href="site.css"/>
	<link rel="stylesheet" href="../ui/jquery-ui.css"/>
	<script src="../js/jquery.js"></script>
	<script src="../js/jquery-ui.js"></script>
	<script src="../js/edit.js"></script>
  <script src="../codemirror/js/codemirror.js"></script>
	<script><?php echo $_SESSION['todo_head']; ?></script>
</head>
<body>
	<h2>Todo List</h2>
	<?php if ( $action == "edit" ) { ?>
	  
	  <form id="editform" action="" method="POST">
	    <input type="submit" value="Save Header" style="float: right;"/>
  		<textarea id="edit" name="edit"><?php echo $_SESSION['todo_head']; ?></textarea><br/>
  		<input type="hidden" name="action" value="do_edit"/>
  	</form>

	<?php } else { ?>
		<form id="form" action="" method="POST">
			<input id="item" type="text" name="item"/>
			<input type="submit" value="Add"/>
		</form>
	
		<ul><?php foreach ( $items as $item ) { ?>
			<li><?php echo $item; ?></li>
		<?php } ?></ul>
	<?php } ?>
</body>
</html>
