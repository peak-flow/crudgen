<?php
use Davidany\Codegen\CrudGenerator;
use Davidany\Codegen\CrudTemplateVariable;
use Davidany\Codegen\DbCredential;
use Davidany\Codegen\Project;

error_reporting(E_ALL);
ini_set('display_errors', 1);
//

include($_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php');
$projectId   = $_GET['id'];
$project     = new Project();
$projectRows = $project->edit($projectId);
$dbCredential    = new DbCredential();
$dbCredentialRow = $dbCredential->getByProjectId($projectId);


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
		<a href = "edit.php?id=<?= $projectId; ?>">
			<button type = "button" class = "btn btn-primary">Edit Project</button>
		</a>

		<div class = "row">
			<table width = "74%" align = "center" class = "table table-striped table-bordered">
				<thead>

				<tr>
					<th width = "279">Name</th>
					<th width = "321"><?php echo $projectRows->name; ?></th>
				</tr>

				<tr>
					<th width = "279">Directory</th>
					<th width = "321"><?php echo $projectRows->directory; ?></th>
				</tr>
				</thead>
			</table>
		</div>
	</div>
	<div class = "gen-container">
		<h3>Database Credentials</h3>
		<a href = "../db_credential/create.php?id=<?= $projectId; ?>">
			<button type = "button" class = "btn btn-primary">Add Database Connection</button>
		</a>
		<?php

		if (isset($dbCredentialRow->host)) {


			?>

			<div class = "row">
				<table width = "74%" align = "center" class = "table table-striped table-bordered">
					<thead>

					<tr>
						<th width = "279">host</th>
						<th width = "321"><?php echo $dbCredentialRow->host; ?></th>
					</tr>

					<tr>
						<th width = "279">database</th>
						<th width = "321"><?php echo $dbCredentialRow->database; ?></th>
					</tr>
					<tr>
						<th width = "279">username</th>
						<th width = "321"><?php echo $dbCredentialRow->username; ?></th>
					</tr>
					<tr>
						<th width = "279">password</th>
						<th width = "321"><?php echo $dbCredentialRow->password; ?></th>
					</tr>
					</thead>
				</table>
			</div>
			<?php
		}
		?>
	</div>
	<div class = "gen-container">
		<h3>Tables </h3>

		<div class = "row">

			<?php

			if (isset($dbCredentialRow->host)) {
				$crudTemplateVariable = new CrudTemplateVariable();
				$crudTemplateVariable->build($dbCredentialRow, $projectId);

			//	print_x($crudTemplateVariable);
				$generate = new CrudGenerator($crudTemplateVariable->laravelBuilderValueArray);
				$generate->getDestinationPath($projectRows->name);
				$generate->getTables();
				$generate->buildModel();
				$generate->buildController();
				$generate->buildApiController();
				$generate->buildIndexView();
				$generate->buildCreateView();

				$generate->buildShowView();

				$generate->buildEditView();
				$generate->buildRoute();
				$generate->buildMigration();
			}
			?>

		</div>
	</div>
	<div class = "gen-container">
		<h3>Relationships </h3>


	</div>


</div>

</div>

</body>
</html>
