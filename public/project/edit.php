<?php

use Davidany\Codegen\Project;

error_reporting(E_ALL);
ini_set('display_errors', 1);
//
include($_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php');

$id = $_GET['id'];
$project = new Project();
$rows  = $project->edit($id);

?>


<!doctype html>
<html lang = "en">
<head>
	<meta charset = "UTF-8">
	<meta name = "viewport" content = "width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv = "X-UA-Compatible" content = "ie=edge">
	<title>Project Index</title>
	<?php require_once('../head.php'); ?>


</head>
<body>
<?php require_once('../nav.php'); ?>
<div id = "app" class = "container">
	<div class = "row">

		<h2>Edit Project</h2>
		<hr>

	</div>
	<div class = "row">
		<form action = "<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "post" id = "form" class = "form-horizontal" enctype = "multipart/form-data">

			<div class = "form-group">
				<label class = "" for = "name">name</label>
				<input type = "text" class = "form-control" name = "name" maxlength = "255" value = "<?php echo $rows->name; ?>" id = "name">
			</div>

			<div class = "form-group">
				<label class = "" for = "directory">directory</label>
				<input type = "text" class = "form-control" name = "directory" maxlength = "255" value = "<?php echo $rows->directory; ?>" id = "directory">
			</div>

			<input type = "submit" name = "button" class = "btn btn-primary btn-large" value = "Register"/>

		</form>
	</div>
