<?php

use Davidany\Codegen\Project;

error_reporting(E_ALL);
ini_set('display_errors', 1);
//
include($_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php');

$id      = $_GET['id'];
$project = new Project();
$rows    = $project->edit($id);

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

		<h2> Deploy Project</h2>
		<hr>

	</div>
	<div class = "gen-container">
		<h3> Project Details </h3>
		<a href = "edit.php?id=<?= $id; ?>">
			<button type = "button" class = "btn btn-primary">Edit Project</button>
		</a>

		<div class = "row">
			<table width = "74%" align = "center" class = "table table-striped table-bordered">
				<thead>

				<tr>
					<th width = "279">Name</th>
					<th width = "321"><?php echo $rows->name; ?></th>
				</tr>

				<tr>
					<th width = "279">Directory</th>
					<th width = "321"><?php echo $rows->directory; ?></th>
				</tr>
				</thead>
			</table>
		</div>
	</div>
	<div class = "gen-container">
		<h3>Database Connection</h3>
		<a href = "edit.php?id=<?= $id; ?>&projectName=<?= $rows->name; ?>">
			<button type = "button" class = "btn btn-primary">Add Database Connection</button>
		</a>

		<div class = "row">

		</div>
	</div>
	<div class = "row">
		<h2>Database Connection</h2>

	</div>

</div>

</body>
</html>
