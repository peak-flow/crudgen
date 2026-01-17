<?php

use Davidany\Codegen\Project;

error_reporting(E_ALL);
ini_set('display_errors', 1);
//
include($_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php');


$project = new Project();
$result  = $project->index();

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

		<h2>Projects</h2>
		<hr>

		<!--		<button v-on:click = "addNewProject" type = "button" class = "btn btn-primary">Add New</button>-->
		<a href = "create.php">
			<button type = "button" class = "btn btn-primary">Add New</button>
		</a>


	</div>

	<div class = "row">
		<div class = "col-md-8">
			<h3></h3>
			<h3></h3>
			<h4>View Projects </h4>
		</div>

		<table align = "center" class = "table table-striped table-bordered">
			<thead>
			<tr>
				<th>name</th>
				<th>directory</th>

				<th>edit</th>
				<th>delete</th>
			</tr>
			</thead>
			<tbody>
			<?php
			// //Loop through
			foreach ($result as $rows) {

				?>
				<tr>
					<td><a href = "deploy.php?id=<?php echo $rows->id; ?>"><?php echo ucwords($rows->name); ?></a></td>
					<td><?php echo ucwords($rows->directory); ?></td>


					<td width = "25px"><a href = "edit.php?id=<?php echo $rows->id; ?>" title = "Edit"> <span class = "btn btn-mini"><img
										src = "../assets/icons/update.png" width = "16" height = "16"></span></a>
					</td>
					<td width = "25px"><a href = "databaseView.php?databaseId=<?php echo $rows->id; ?>" title = "Delete"> <span class = "btn btn-mini"><img
										src = "../assets/icons/delete.png" width = "16" height = "16"></span></a>
					</td>
				</tr>
			<?php } ?>
			</tbody>
		</table>

	</div>
</div>
</body>

</html>

<script>

	new Vue({
		el: '#app',
		data: {
			message: 'hello',
		},
		methods: {},
	});

</script>
