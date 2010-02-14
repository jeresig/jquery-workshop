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
	
	$ajax = $_SERVER['HTTP_X_REQUESTED_WITH'];
	$action = $_REQUEST['action'];

	if ( $action == "reset" ) {
	  $_SESSION['social_head'] = "";
		reset_users();
		home();
	} else if ( $action == "done" ) {
		$_SESSION['social_head'] = file_get_contents("code.txt");
		reset_users();
		home();
  } else if ( $action == "do_edit" ) {
  	$_SESSION['social_head'] = $_REQUEST['edit'];
  	
  	$action = "edit";
	} else if ( $action == "do_send" ) {
		// TODO: Do something!
		sleep(1);
		home();
	} else if ( $action == "do_remove" ) {
		$users = array();
		
		foreach ($_SESSION['users'] as $user) {
			if ( getID($user) != $_REQUEST['id'] ) {
				array_push( $users, $user );
			}
		}
		
		$_SESSION['users'] = $users;
		home();
	} else if ( $action == "do_add" ) {
		sleep(2);
		array_unshift( $_SESSION['users'], $_REQUEST['name'] );
		home();
	}
	
	function home(){
		header("Location: .");
		exit();
	}
	
	if ( !$ajax ) {?>
<html>
<head>
    <title>My Social Site</title>
    <link rel="stylesheet" href="site.css"/>
		<link rel="stylesheet" href="../ui/jquery-ui.css"/>
		<script src="../js/jquery.js"></script>
		<script src="../js/jquery-ui.js"></script>
		<script src="../js/jquery.form.js"></script>
		<script src="../js/edit.js"></script>
    <script src="../codemirror/js/codemirror.js"></script>
    <script><?php echo $_SESSION['social_head']; ?></script>
</head>
<body>
<div id="wrapper">
<div id="head"><a href="?">My Social Site</a></div>
<div id="content">
<?php }

	if ( $action == "add" ) { ?>
	
	<form id="add" action="" method="POST">
		<b>Add a friend:</b><br/>
		<label>Friend's Full Name:</label> <input type="text" name="name"/><br/>
		<input type="submit" value="Add Friend"/>
		<input type="hidden" name="action" value="do_add"/>
	</form>

	<?php } else if ( $action == "edit" ) { ?>
	  
	  <form id="editform" action="" method="POST">
	    <input type="submit" value="Save Header" style="float: right;"/>
  		<textarea id="edit" name="edit"><?php echo $_SESSION['social_head']; ?></textarea><br/>
  		<input type="hidden" name="action" value="do_edit"/>
  	</form>
	  
	<?php } else if ( $action == "send" ) {
		$user = "";
		
		foreach ($_SESSION['users'] as $u) {
			if ( getID($u) == $_REQUEST['id'] ) {
				$user = $u;
			}
		} ?>
		
	<form id="send" action="" method="POST">
		<b>Send A Message to <?php echo $user ?>:</b><br/>
		<textarea name="msg"></textarea><br/>
		<input type="submit" value="Send Message"/>
		<input type="hidden" name="action" value="do_send"/>
	</form>
		
	<?php } else {
		if ( !$_SESSION['users'] ) {
			reset_users();
		} ?>
	
	<div id="main">
	<b>Quick Jump:</b>
	<ul id="quick">
		<li><a href="#friends"><span>Friends</span></a></li>
		<li><a href="?action=add"><span>Add Friend</span></a></li>
	</ul>

	<div id="friends" class="tabs-container">
		<ul>
		<?php foreach ( $_SESSION['users'] as $user ) {
			$id = getID($user);
		?>
			<li id="<?php echo $id ?>">
				<form action="" method="POST">
					<input type="hidden" name="action" value="do_remove"/>
					<input type="hidden" name="id" value="<?php echo $id ?>"/>
					<img src="head.gif" alt="<?php echo $user ?>"/>
					<b><?php echo $user ?></b><br/>
					Boston, MA<br/>
					<a href="?action=send&amp;id=<?php echo $id ?>" class="send">Send Message</a>
					<span class="line">|</span>
					<input type="submit" value="Remove Friend" class="remove"/>
				</form>
			</li>
		<?php } ?>
		</ul>
	</div>
	</div>
	<?php }
	
function getID($user){
	return preg_replace( "/ /", "", strtolower($user) );
}
	
function reset_users(){
	$_SESSION['users'] = array(
		"John Resig",
		"Rey Bango",
		"Paul Bakaus",
		"Yehuda Katz",
		"Richard Worth",
		"Karl Swedberg"
	);
}

if ( !$ajax ) {?>
</div>
</div>
<div id="dialog"></div>
</body>
</html>
<?php } ?>
