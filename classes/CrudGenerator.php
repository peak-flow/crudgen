<?php

namespace Davidany\Codegen;


class CrudGenerator
{

	public  $projectName;
	public  $versionNumber;
	public  $destinationPath;
	private $laravelBuilderValueArray;

	public function __construct($laravelBuilderValueArray)
	{
		$this->laravelBuilderValueArray = $laravelBuilderValueArray;
//        print_x($this->laravelBuilderValueArray);
	}

	public function getDestinationPath($projectName)
	{
		$this->projectName     = $projectName;
		$this->destinationPath = $_SERVER['DOCUMENT_ROOT'].'/output/'.$this->projectName.'/';
		$this->getVersionNumber();

		if (!file_exists($this->destinationPath)) {
			mkdir($this->destinationPath, 0777, true);
		}
		echo $this->destinationPath;
	}

	public function getVersionNumber()
	{
		$directory = $this->destinationPath;
		$fileCount = 0;
		$files     = glob($directory."*");

		if ($files) {
			$fileCount = count($files);
		}
		$this->versionNumber   = $fileCount + 1;
		$this->destinationPath = $this->destinationPath.$this->versionNumber.'/';
	}


	public function getStub($type)
	{
		return file_get_contents($_SERVER['DOCUMENT_ROOT']."/stubs/$type.stub");
	}

	public function getTables()
	{
		foreach ($this->laravelBuilderValueArray as $key => $value) {
			$name = $this->laravelBuilderValueArray[$key]['ModelClassName'];


			if (!file_exists($this->destinationPath.'models/')) {
				mkdir($this->destinationPath.'models/', 0777, true);
			}
			$modelTemplate = str_replace(['{{ModelClassName}}'], [$name], $this->getStub('Model'));
			file_put_contents($this->destinationPath."models/{$name}.php", $modelTemplate);
		}
	}

	public function buildModel()
	{
		foreach ($this->laravelBuilderValueArray as $key => $value) {
			$name = $this->laravelBuilderValueArray[$key]['ModelClassName'];

			if (!file_exists($this->destinationPath.'models/')) {
				mkdir($this->destinationPath.'models/', 0777, true);
			}
			$modelTemplate = str_replace(['{{ModelClassName}}'], [$name], $this->getStub('Model'));
			file_put_contents($this->destinationPath."models/{$name}.php", $modelTemplate);
		}
	}

	public function buildController()
	{
		foreach ($this->laravelBuilderValueArray as $key => $value) {
			$modelClassName                   = $this->laravelBuilderValueArray[$key]['ModelClassName'];
			$controllerName                   = $this->laravelBuilderValueArray[$key]['ControllerName'];
			$controllerVariableName           = $this->laravelBuilderValueArray[$key]['ControllerVariableName'];
			$controllerVariableNameSingular   = $this->laravelBuilderValueArray[$key]['ControllerVariableNameSingular'];
			$controllerCompactName            = $this->laravelBuilderValueArray[$key]['ControllerCompactName'];
			$viewFolderName                   = $this->laravelBuilderValueArray[$key]['ViewFolderName'];
			$unCapitalizedTableNameWithDashes = $this->laravelBuilderValueArray[$key]['unCapitalizedTableNameWithDashes'];


			if (!file_exists($this->destinationPath.'controllers/')) {
				mkdir($this->destinationPath.'controllers/', 0777, true);
			}


			$modelTemplate = str_replace(['{{ModelClassName}}'], [$modelClassName], $this->getStub('Controller'));

			$modelTemplate = str_replace(['{{ControllerName}}'], [$controllerName], $modelTemplate);
			$modelTemplate = str_replace(['{{ControllerVariableName}}'], [$controllerVariableName], $modelTemplate);
			$modelTemplate = str_replace(['{{ControllerVariableNameSingular}}'], [$controllerVariableNameSingular], $modelTemplate);
			$modelTemplate = str_replace(['{{ControllerCompactName}}'], [$controllerCompactName], $modelTemplate);
			$modelTemplate = str_replace(['{{ViewFolderName}}'], [$viewFolderName], $modelTemplate);
			$modelTemplate = str_replace(['{{unCapitalizedTableNameWithDashes}}'], [$unCapitalizedTableNameWithDashes], $modelTemplate);
			file_put_contents($this->destinationPath."controllers/{$controllerName}.php", $modelTemplate);
		}
	}

	public function buildApiController()
	{
		foreach ($this->laravelBuilderValueArray as $key => $value) {
			$modelClassName                 = $this->laravelBuilderValueArray[$key]['ModelClassName'];
			$controllerName                 = $this->laravelBuilderValueArray[$key]['ControllerName'];
			$controllerVariableName         = $this->laravelBuilderValueArray[$key]['ControllerVariableName'];
			$controllerVariableNameSingular = $this->laravelBuilderValueArray[$key]['ControllerVariableNameSingular'];
			$viewFolderName                 = $this->laravelBuilderValueArray[$key]['ViewFolderName'];

			if (!file_exists($this->destinationPath.'controllers/api')) {
				mkdir($this->destinationPath.'controllers/api', 0777, true);
			}

			$modelTemplate = str_replace(['{{ModelClassName}}'], [$modelClassName], $this->getStub('Api-Controller'));
			$modelTemplate = str_replace(['{{ControllerName}}'], [$controllerName], $modelTemplate);
			$modelTemplate = str_replace(['{{ControllerVariableName}}'], [$controllerVariableName], $modelTemplate);
			$modelTemplate = str_replace(['{{ControllerVariableNameSingular}}'], [$controllerVariableNameSingular], $modelTemplate);
			$modelTemplate = str_replace(['{{ViewFolderName}}'], [$viewFolderName], $modelTemplate);
			file_put_contents($this->destinationPath."controllers/api/{$controllerName}.php", $modelTemplate);
		}
	}

	public function buildIndexView()
	{
		foreach ($this->laravelBuilderValueArray as $key => $value) {
			$viewFolderName            = $this->laravelBuilderValueArray[$key]['ViewFolderName'];
			$viewDisplayTableName      = $this->laravelBuilderValueArray[$key]['ViewDisplayTableName'];
			$viewIndexColumnTitleTR    = $this->laravelBuilderValueArray[$key]['ViewIndexColumnTitleTR'];
			$viewIndexColumnValueTR    = $this->laravelBuilderValueArray[$key]['ViewIndexColumnValueTR'];
			$viewClassVariablePlural   = $this->laravelBuilderValueArray[$key]['ViewClassVariablePlural'];
			$viewClassVariableSingular = $this->laravelBuilderValueArray[$key]['ViewClassVariableSingular'];

			if (!file_exists($this->destinationPath.'views/'.$viewFolderName)) {
				mkdir($this->destinationPath.'views/'.$viewFolderName.'/', 0777, true);
			}
			$modelTemplate = str_replace(['{{ViewFolderName}}'], [$viewFolderName], $this->getStub('index'));
			$modelTemplate = str_replace(['{{ViewDisplayTableName}}'], [$viewDisplayTableName], $modelTemplate);
			$modelTemplate = str_replace(['{{ViewIndexColumnTitleTR}}'], [$viewIndexColumnTitleTR], $modelTemplate);
			$modelTemplate = str_replace(['{{ViewIndexColumnValueTR}}'], [$viewIndexColumnValueTR], $modelTemplate);
			$modelTemplate = str_replace(['{{ViewClassVariablePlural}}'], [$viewClassVariablePlural], $modelTemplate);
			$modelTemplate = str_replace(['{{ViewClassVariableSingular}}'], [$viewClassVariableSingular], $modelTemplate);
			file_put_contents($this->destinationPath."views/{$viewFolderName}/index.blade.php", $modelTemplate);
		}
	}

	public function buildCreateView()
	{
//        print_x($this->laravelBuilderValueArray );

		foreach ($this->laravelBuilderValueArray as $key => $value) {
			$formBlockBuilder = '';

			if (isset($value['Columns'])) {
				foreach ($value['Columns'] as $innerKey => $innerValue) {
					$columnName           = $innerValue['ColumnName'];
					$viewDisplayTableName = $innerValue['ColumnDisplayName'];
					if (!($columnName == 'id' || $columnName == 'created_at' || $columnName == 'updated_at')) {
						$formBlock        = str_replace(['{{ColumnName}}'], [$columnName], $this->getStub('bootstrap-form-group-view'));
						$formBlock        = str_replace(['{{ColumnDisplayName}}'], [$viewDisplayTableName], $formBlock);
						$formBlockBuilder .= $formBlock;
					}
				}
			}
			$viewFolderName       = $this->laravelBuilderValueArray[$key]['ViewFolderName'];
			$viewDisplayTableName = $this->laravelBuilderValueArray[$key]['ViewDisplayTableName'];


			if (!file_exists($this->destinationPath.'views/'.$viewFolderName)) {
				mkdir($this->destinationPath.'views/'.$viewFolderName.'/', 0777, true);
			}
			$modelTemplate = str_replace(['{{ViewFolderName}}'], [$viewFolderName], $this->getStub('create'));
			$modelTemplate = str_replace(['{{ViewDisplayTableName}}'], [$viewDisplayTableName], $modelTemplate);
			$modelTemplate = str_replace(['{{FormBlockBuilder}}'], [$formBlockBuilder], $modelTemplate);

			file_put_contents($this->destinationPath."views/{$viewFolderName}/create.blade.php", $modelTemplate);
		}
	}

	public function buildShowView()
	{
		foreach ($this->laravelBuilderValueArray as $key => $value) {
			$formBlockBuilder = '';

			if (isset($value['Columns'])) {
				foreach ($value['Columns'] as $innerKey => $innerValue) {
					$columnName                = $innerValue['ColumnName'];
					$viewDisplayTableName      = $innerValue['ColumnDisplayName'];
					$viewClassVariableSingular = $this->laravelBuilderValueArray[$key]['ViewClassVariableSingular'];

					$formBlock = str_replace(['{{ColumnName}}'], [$columnName], $this->getStub('paragraph-list-columns'));
					$formBlock = str_replace(['{{ColumnDisplayName}}'], [$viewDisplayTableName], $formBlock);
					$formBlock = str_replace(['{{ViewClassVariableSingular}}'], [$viewClassVariableSingular], $formBlock);

					$formBlockBuilder .= $formBlock;
				}
				$viewFolderName            = $this->laravelBuilderValueArray[$key]['ViewFolderName'];
				$viewDisplayTableName      = $this->laravelBuilderValueArray[$key]['ViewDisplayTableName'];
				$viewClassVariableSingular = $this->laravelBuilderValueArray[$key]['ViewClassVariableSingular'];

				if (!file_exists($this->destinationPath.'views/'.$viewFolderName)) {
					mkdir($this->destinationPath.'views/'.$viewFolderName.'/', 0777, true);
				}
				$modelTemplate = str_replace(['{{ViewFolderName}}'], [$viewFolderName], $this->getStub('show'));
				$modelTemplate = str_replace(['{{ViewDisplayTableName}}'], [$viewDisplayTableName], $modelTemplate);
				$modelTemplate = str_replace(['{{ViewClassVariableSingular}}'], [$viewClassVariableSingular], $modelTemplate);
				$modelTemplate = str_replace(['{{ParagraphListColumns}}'], [$formBlockBuilder], $modelTemplate);

				file_put_contents($this->destinationPath."views/{$viewFolderName}/show.blade.php", $modelTemplate);
			}
		}
	}

	public function buildEditView()
	{
		foreach ($this->laravelBuilderValueArray as $key => $value) {
			$formBlockBuilder = '';

			if (isset($value['Columns'])) {
				foreach ($value['Columns'] as $innerKey => $innerValue) {
					$columnName                = $innerValue['ColumnName'];
					$viewDisplayTableName      = $innerValue['ColumnDisplayName'];
					$viewClassVariableSingular = $this->laravelBuilderValueArray[$key]['ViewClassVariableSingular'];
					if (!($columnName == 'id' || $columnName == 'created_at' || $columnName == 'updated_at')) {
						$formBlock = str_replace(['{{ColumnName}}'], [$columnName], $this->getStub('bootstrap-form-group-edit'));
						$formBlock = str_replace(['{{ColumnDisplayName}}'], [$viewDisplayTableName], $formBlock);
						$formBlock = str_replace(['{{ViewClassVariableSingular}}'], [$viewClassVariableSingular], $formBlock);

						$formBlockBuilder .= $formBlock;
					}
				}
				$viewFolderName       = $this->laravelBuilderValueArray[$key]['ViewFolderName'];
				$viewDisplayTableName = $this->laravelBuilderValueArray[$key]['ViewDisplayTableName'];
				if (!file_exists($this->destinationPath.'views/'.$viewFolderName)) {
					mkdir($this->destinationPath.'views/'.$viewFolderName.'/', 0777, true);
				}
				$modelTemplate = str_replace(['{{ViewFolderName}}'], [$viewFolderName], $this->getStub('edit'));
				$modelTemplate = str_replace(['{{ViewDisplayTableName}}'], [$viewDisplayTableName], $modelTemplate);
				$modelTemplate = str_replace(['{{ViewClassVariableSingular}}'], [$viewClassVariableSingular], $modelTemplate);
				$modelTemplate = str_replace(['{{FormBlockBuilder}}'], [$formBlockBuilder], $modelTemplate);

				file_put_contents($this->destinationPath."views/{$viewFolderName}/edit.blade.php", $modelTemplate);
			}
		}
	}

	public function buildRoute()
	{
		foreach ($this->laravelBuilderValueArray as $key => $value) {
			$formBlockBuilder = '';
			$routeBuilder     = '';

			if (isset($value['Columns'])) {
				foreach ($this->laravelBuilderValueArray as $key => $value) {
					$controllerName = $this->laravelBuilderValueArray[$key]['ControllerName'];
					$viewFolderName = $this->laravelBuilderValueArray[$key]['ViewFolderName'];

					if (!file_exists($this->destinationPath.'routes/')) {
						mkdir($this->destinationPath.'routes/', 0777, true);
					}
					$modelTemplate = str_replace(['{{ControllerName}}'], [$controllerName], $this->getStub('route'));
					$modelTemplate = str_replace(['{{ViewFolderName}}'], [$viewFolderName], $modelTemplate);

					$routeBuilder .= $modelTemplate;
					$routeBuilder .= "\r\n";
					$routeBuilder .= "\r\n";
					$routeBuilder .= "\r\n";

					file_put_contents($this->destinationPath."routes/web.php", $routeBuilder);
				}
			}
		}
	}

	public function buildMigration()
	{
		$tableSchema = "";

		foreach ($this->laravelBuilderValueArray as $key => $value) {
			$formBlockBuilder = '';
			if (isset($value['Columns'])) {
				foreach ($value['Columns'] as $innerKey => $column) {
					$columnName     = $column['ColumnName'];
					$columnType     = $column['DATA_TYPE'];
					$columnNullable = $column['Null'] == "YES" ? "->nullable()" : "";
					$columnDefault  = $column['ColumnDefault'] == null ? "" : "->default('".$column['Default']."')";
					if ($columnName != 'created_at') {
						if ($columnName != 'updated_at') {
							$tableSchema .= "\$table->".strtolower($columnType)."('".$columnName."')".$columnNullable.$columnDefault.";".PHP_EOL;
							if ($columnType == 'varchar') {
								if (is_int($column['CharacterMaximumLength'])) {
									$tableSchema .= "\$table->string\"('".$columnName."',".$column['CharacterMaximumLength'].")".$columnNullable.$columnDefault.";".PHP_EOL;
								} else {
									$tableSchema .= "\$table->string\"('".$columnName."')".$columnNullable.$columnDefault.";".PHP_EOL;
								}
							}
						}
					}
				}

				foreach ($value['Columns'] as $innerKey => $innerValue) {
					$migrationTableName = $innerValue['ColumnName'];
					if ($migrationTableName != 'id') {
						$migrationTableType               = $innerValue['DATA_TYPE'];
						$migrationTableCharacterMaxLength = $innerValue['CharacterMaximumLength'];
						$migrationNull                    = $innerValue['Null'];
						$migrationKey                     = $innerValue['ColumnKey'];
						$migrationDefault                 = $innerValue['ColumnDefault'];
						$migrationExtra                   = $innerValue['Extra'];

						$columnBuilder = '$table->';


						if ($migrationKey == 'MUL') {
							$columnBuilder .= "unsignedInteger('$migrationTableName')";
						} elseif (($migrationKey == 'PRI') && ($migrationTableType == 'INT')) {
							$columnBuilder .= "bigIncrements('$migrationTableName')";
						} elseif (($migrationKey == 'PRI') && ($migrationTableType == 'bigint')) {
							$columnBuilder .= "bigIncrements('$migrationTableName')";
						} else {
							$tinyIntSearch      = '/^tinyint/';
							$bigIntSearch       = '/^bigint/';
							$smallIntSearch     = '/^smallint/';
							$bitSearch          = '/^bit/';
							$intSearch          = '/^int/';
							$decimalSearch      = '/^decimal/';
							$decimalInnerSearch = '/\d*\,\d*/';
							$timestampSearch    = '/^timestamp/';
							$datetimeSearch     = '/^datetime/';
							$dateSearch         = '/^date(?!time)/';
							$varSearch          = '/^varchar/';
							$varInnerSearch     = '/\d+/';
							$charSearch         = '/^char/';
							$textSearch         = '/^text/';
							$enumSearch         = '/^enum/';

							if (preg_match($tinyIntSearch, $migrationTableType, $match)) {
								$columnBuilder .= "tinyInteger('$migrationTableName')";
							}
							if (preg_match($bigIntSearch, $migrationTableType, $match)) {
								$columnBuilder .= "bigInteger('$migrationTableName')";
							}
							if (preg_match($smallIntSearch, $migrationTableType, $match)) {
								$columnBuilder .= "smallInteger('$migrationTableName')";
							}
							if (preg_match($bitSearch, $migrationTableType, $match)) {
								$columnBuilder .= "smallInteger('$migrationTableName')";
							}
							if (preg_match($intSearch, $migrationTableType, $match)) {
								$columnBuilder .= "integer('$migrationTableName')";
							}
							if (preg_match($timestampSearch, $migrationTableType, $match)) {
								$columnBuilder .= "timestamp('$migrationTableName')";
							}
							if (preg_match($datetimeSearch, $migrationTableType, $match)) {
								$columnBuilder .= "dateTime('$migrationTableName')";
							}
							if (preg_match($dateSearch, $migrationTableType, $match)) {
								$columnBuilder .= "date('$migrationTableName')";
							}
							if (preg_match($textSearch, $migrationTableType, $match)) {
								$columnBuilder .= "text('$migrationTableName')";
							}
							if (preg_match($enumSearch, $migrationTableType, $match)) {
								if (preg_match('!\(([^\)]+)\)!', $migrationTableType, $enumValues)) {
									$enumValueText = $enumValues[1];
								}
								$enumValueArray = explode(',', $enumValueText);
								$columnBuilder  .= "enum('$migrationTableName',[";
								foreach ($enumValueArray as $value) {
									$columnBuilder .= $value.',';
								}
								$columnBuilder = rtrim($columnBuilder, ',');
								$columnBuilder .= '])';
							}

							if (preg_match($decimalSearch, $migrationTableType, $match)) {
								preg_match($decimalInnerSearch, $migrationTableType, $matches);
								$deciNum       = explode(',', $matches[0]);
								$columnBuilder .= "decimal('$migrationTableName',$deciNum[0],$deciNum[1])";
							}
							if (preg_match($varSearch, $migrationTableType, $match)) {
								preg_match($varInnerSearch, $migrationTableType, $matches);
								if (!empty($matches) && $matches[0] > 500) {
									$columnBuilder .= "string('$migrationTableName',500)";
								} else {
									$columnBuilder .= "string('$migrationTableName',".(!empty($matches) ? $matches[0] : "255").")";
								}
								// var_dump($columnBuilder);
							}
							if (preg_match($charSearch, $migrationTableType, $match)) {
								preg_match($varInnerSearch, $migrationTableType, $matches);
								$columnBuilder .= "char('$migrationTableName',$matches[0])";
							}
						}

						if ($migrationDefault != '') {
							if ($migrationDefault == 'CURRENT_TIMESTAMP') {
								$columnBuilder .= "->useCurrent()";
							} else {
								if (preg_match($bitSearch, $migrationTableType, $match)) {
									$columnBuilder .= "->default(0)";
								} else {
									$columnBuilder .= "->default($migrationDefault)";
								}
							}
						}

						if ($migrationNull == 'YES') {
							$columnBuilder .= '->nullable()';
						}

						$columnBuilder .= ';';

						$formBlockBuilder .= $columnBuilder;
					} else {
						$formBlockBuilder .= '$table->id();';
					}
				}
			}
			$formBlockBuilder .= '$table->timestamps();';


			$migrationTableName = $this->laravelBuilderValueArray[$key]['MigrationTableName'];
			$migrationClassName = $this->laravelBuilderValueArray[$key]['MigrationClassName'];
			$viewFolderName     = $this->laravelBuilderValueArray[$key]['ViewFolderName'];
			$migrationFileName  = $this->laravelBuilderValueArray[$key]['MigrationFileName'];

			if (!file_exists($this->destinationPath.'migrations')) {
				mkdir($this->destinationPath.'migrations/', 0777, true);
			}
			$modelTemplate = str_replace(['{{MigrationTableList}}'], [$formBlockBuilder], $this->getStub('migration'));
			$modelTemplate = str_replace(['{{MigrationClassName}}'], [$migrationClassName], $modelTemplate);
			$modelTemplate = str_replace(['{{MigrationTableName}}'], [$migrationTableName], $modelTemplate);


			file_put_contents($this->destinationPath."migrations/{$migrationFileName}.php", $modelTemplate);
		}
	}
}
