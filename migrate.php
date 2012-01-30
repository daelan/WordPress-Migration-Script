<?php
/*  Copyright 2012  Daelan Wood  (email : daelan@daelan.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
?>
<?php require_once('wp-blog-header.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><html>
	<head>
		<title>WordPress Migrate</title>
		<style type="text/css">
		
		body{
		
		font-family:Helvetica, Arial, Sans-Serif;
		text-align:center;
		background:#efefef;
		font-size:12px;
		}
		
		#wrapper{
		
		width:500px;
		height:auto;
		color:#333;
		margin:30px auto;
		text-align:left;
		background:#fff;
		padding:30px;
		-moz-border-radius:5px;
		-webkit-border-radius:5px;
		-o-border-radius:5px;
		border-radius:5px;
		
		}
		
		#mastHead{
		
		border-bottom:1px solid #efefef;
		padding:0 0 18px 0;
		margin:0 0 18px 0;
		
		}
		
		#footer{
		
		border-top:1px solid #efefef;
		padding:18px 0 18px 0;
		margin:18px 0 0 0;
		color:#999;
		font-size:11px;
		
		}
		
		p{
		
		line-height:18px;
		
		}
		
		a:link, a:visited{
		
		text-decoration:none;
		color:#333;
		
		
		}
		
		.success{
		
		display:block;
		padding:10px;
		background:#dce5dc;
		border:1px solid #9eb29e;
		}
		
		.error{	
			display:block;
			padding:10px;
			background:#eac7c7;
			border:1px solid #b23939;
		}
				
		form label{
			display: block;
			float: left; 
			width: 150px; 
			padding: 0; 
			margin: 12px 0 0;
			text-align: right; 
			font-weight:bold;
		}
		
		
		form input{
			padding:5px;
			border:1px solid #ccc;
			width:200px;
			margin:5px 0 0 10px; 
			-moz-border-radius:3px;
			-webkit-border-radius:3px;
			-o-border-radius:3px;
			border-radius:3px;
			
			                      
		}
		
		input.submit{
			font-size:16px;
			color:#000;
			text-decoration:none;
			display:block;
			padding:10px;
			border:1px solid #DDD;
			text-align:center;
			-moz-border-radius:5px;
			-webkit-border-radius:5px;
			-o-border-radius:5px;
			border-radius:5px;
			background:#FFFFFF;
			background:-webkit-gradient(linear, 0% 0%, 0% 100%, from(#FFFFFF), to(#EEE));
			background:-moz-linear-gradient(0% 90% 90deg, #EEE, #FFF);
			margin:18px 0 0 160px;
			cursor: pointer;
			cursor: hand;
		}
		
		small{
		
		margin:0 0 0 160px;
		
		}

		
		
		</style>
	</head>
	<body>
	<div id="wrapper">
	<div id="mastHead">
	<h1>WordPress Migration</h1>
	</div>
	<div id="form">
	<p>This is a simple script for facilitating the process of updating the references to the URL in the Database after moving a WordPress install from 1 server to another.</p>
	<h2>Give us your deets</h2>
	<?php
	
	if(isset($_POST['submit'])){
	
	$message = "";
	$errors = "";
	
	$prefix = $_POST['prefix'];
	
	$oldUrl = $_POST['oldUrl'];
	
	$newUrl = $_POST['newUrl'];
	
	
	if(empty($oldUrl)){
	
	$errors .= "<p class='error'>Please enter your old URL.</p>";
	
	}
	
	if(empty($newUrl)){
	
	$errors .= "<p class='error'>Please enter your new URL.</p>";
	
	}
	
	if(empty($prefix)){
	
	$errors .= "<p class='error'>Please enter a table prefix. If you are unsure it's probably <strong>wp_</strong></p>";
	
	}
	
	if(empty($errors)){
	
	/* -- Update Siteurl & Homeurl -- */
	
	$query1 = "UPDATE ".$prefix."options SET option_value = replace(option_value, '".$oldUrl."', '".$newUrl."') WHERE option_name = 'home' OR option_name = 'siteurl'";
		
	$result1 = mysql_query($query1);
		
	if (!$result1) {
	    die('Invalid query: ' . mysql_error());
	}else{
	
	$numResults1 = mysql_affected_rows();
	
	if ($numResults1 > 0) {


	
	$message .= "<p class='success'>Siteurl & Homeurl Successfully Updated!</p>";
	
	}
	
	}
	
	
	/* -- Update GUID -- */
	
	
	$query2 = "UPDATE ".$prefix."posts SET guid = REPLACE (guid, '".$oldUrl."', '".$newUrl."')";
	

	$result2 = mysql_query($query2);
		
	if (!$result2) {
	    die('Invalid query: ' . mysql_error());
	}else{
	
	$numResults2 = mysql_affected_rows();
	
	if ($numResults2 > 0) {

	
	$message .= "<p class='success'>GUID Successfully Updated!</p>";
	
	}
	
	}
	
	/* -- Update URL in Content -- */
	
	
	$query3 = "UPDATE ".$prefix."posts SET post_content = REPLACE (post_content, '".$oldUrl."', '".$newUrl."')";
	

	$result3 = mysql_query($query3);
		
	if (!$result3) {
	    die('Invalid query: ' . mysql_error());
	}else{
	
		$numResults3 = mysql_affected_rows();
	
	if ($numResults3 > 0) {

	
	$message .= "<p class='success'>URL in content Successfully Updated!</p>";
	
	$message .= "<p class='success'><strong>Your WordPress Migration is Complete!</strong></p><p class='error'>Be sure to <strong>Delete</strong> the migrate.php file from your web server.</p>";
	
	}
	
	}
	
	
	}else{
	
	$message = $errors;
	
	}
	
	
	}
	
	if(isset($message)){
	
	
	echo $message;
	
	}
	
	?>
	<form name="migrate" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
	<label for="oldUrl">Old URL: </label> <input type="text" name="oldUrl" id="oldUrl" value="<?php if(isset($oldUrl)){ echo $oldUrl; }else{ echo "http://oldurl.com"; } ?>"/><br/>
	<label for="newUrl">New URL: </label> <input type="text" name="newUrl" id="newUrl" value="<?php if(isset($newUrl)){ echo $newUrl; }else{ echo "http://newurl.com"; } ?>"/><br/>
	<label for="prefix">Table Prefix: </label> <input type="text" name="prefix" id="prefix" value="<?php if(isset($prefix)){ echo $prefix; }else{ echo "wp_"; } ?>"/><br/><small>If you're unsure it is probably wp_</small><br/>
	<input type="submit" class="submit" name="submit" value="Migrate"/>
	</form>
	</div>
	<div id="footer">
	Created by <a href="http://daelan.com">Daelan Wood</a>
	</div>
	</div>
	
	</body>
</html>
