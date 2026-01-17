<?php

namespace Davidany\Codegen;

use Carbon\Carbon;
use PDO;

class CrudTemplateVariable
{

	public $projectTableNames;
	public $singleModelNameArray;
	public $dbCredential;
	public $projectId;
	public $tableColumnNames;
	public $laravelBuilderValueArray = [];
	public $projectForeignKeys;

	public function build($dbCredential, $projectId)
	{
		$this->dbCredential = $dbCredential;
		$this->projectId    = $projectId;
		$this->getRelationships();
		$this->getTablesAndColumns();
	}

	public function getRelationships()
	{
		$this->getManyToManyRelationships();
		$this->getOneToManyRelationships();
	}


	public function getProjectTableNames()
	{
		$dbProject                = DB::getInstance($this->dbCredential->database, $this->dbCredential->host, $this->dbCredential->username, $this->dbCredential->password);
		$tableNamesToIgnoreString = "('failed_jobs', 'migrations', 'password_reset_tokens', 'personal_access_tokens', 'telescope_entries', 'telescope_entries_tags', 'telescope_monitoring')";
		$sql                      = "SHOW TABLES WHERE `Tables_in_".$this->dbCredential->database."` NOT IN ".$tableNamesToIgnoreString;

		return $dbProject->query($sql)->fetchAll(PDO::FETCH_COLUMN);
	}

	public function getTablesAndColumns()

	{
		echo '</div>';

		$this->projectTableNames = $this->getProjectTableNames();
		$this->buildModelNameArrays();

		foreach ($this->projectTableNames as $tableKey => $tableName) {
			$this->tableColumnNames = $this->getTableColumnNames($tableName);
			$this->generateLaravelBuilderValueArray($tableName, $tableKey);

			$this->fillLaravelBuilderValueArray($tableName, $tableKey);
		}
	}


	public function getTableColumnNames($tableName)
	{
		$schemaName    = $this->dbCredential->database;
		$fullTableName = $this->dbCredential->database.'.'.$tableName.'';
		$dbProject     = DB::getInstance($this->dbCredential->database, $this->dbCredential->host, $this->dbCredential->username, $this->dbCredential->password);
		$sql           = "SELECT  COLUMN_NAME, COLUMN_DEFAULT, IS_NULLABLE, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH, 
             COLUMN_TYPE, COLUMN_KEY, EXTRA
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE 1=1
            AND  TABLE_SCHEMA = '$schemaName'
            AND TABLE_NAME = '$tableName';
           ";

		$stmt = $dbProject->prepare($sql);
		$stmt->execute();
		$this->tableColumnNames[$tableName] = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $this->tableColumnNames;
	}

	private function buildModelNameArrays()
	{
		foreach ($this->projectTableNames as $tableKey => $tableName) {
			if (!strpos($this->manyToManyRelationshipsTableNames, $tableName)) {
				$singularTableName             = Inflect::singularize($tableName);
				$this->singleModelNameArray[]  = $singularTableName;
				$this->pluralModelNameArray[]  = $tableName;
				$this->referencedColumnArray[] = $tableName.'_id';
			}
		}
	}


	public function getManyToManyRelationships()
	{
		$dbProject = DB::getInstance($this->dbCredential->database, $this->dbCredential->host, $this->dbCredential->username, $this->dbCredential->password);


		$sql  = "SELECT DISTINCT(TABLE_NAME) 
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
        WHERE REFERENCED_TABLE_NAME IS NOT NULL 
        AND TABLE_SCHEMA = 'lararel' 
        AND TABLE_NAME LIKE CONCAT('%', REFERENCED_TABLE_NAME, '%')";
		$stmt = $dbProject->query($sql);
		$stmt->execute();
		$manyToManyTables = $stmt->fetchAll(PDO::FETCH_COLUMN);


		$this->manyToManyRelationships           = [];
		$this->manyToManyRelationshipsTableNames = '(';

		foreach ($manyToManyTables as $manyToManyTable) {
			$tables      = explode('_', $manyToManyTable);
			$firstTable  = $tables[0];
			$secondTable = $tables[1];

			if (!isset($this->manyToManyRelationships[$firstTable][$secondTable])) {
				$this->manyToManyRelationships[$firstTable][$secondTable] = $manyToManyTable;
				$this->manyToManyRelationships[$secondTable][$firstTable] = $manyToManyTable;

				$this->manyToManyRelationshipsTableNames .= "'".$manyToManyTable."',";
			}
		}

		$this->manyToManyRelationshipsTableNames = rtrim($this->manyToManyRelationshipsTableNames, ',');
		$this->manyToManyRelationshipsTableNames .= ")";
	}

	public function getOneToManyRelationships()
	{
		$dbProject = DB::getInstance($this->dbCredential->database, $this->dbCredential->host, $this->dbCredential->username, $this->dbCredential->password);

		$sql = " SELECT REFERENCED_TABLE_NAME,  TABLE_NAME 
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE REFERENCED_TABLE_NAME IS NOT NULL
            AND TABLE_SCHEMA = 'lararel'
            AND REFERENCED_TABLE_NAME != TABLE_NAME
        AND TABLE_NAME not in ".$this->manyToManyRelationshipsTableNames."
        ORDER BY REFERENCED_TABLE_NAME";


		$stmt = $dbProject->query($sql);
		$stmt->execute();
		$oneToManyTables = $stmt->fetchAll(PDO::FETCH_ASSOC);


		$this->oneToManyRelationships = [];
		$this->manyToOneRelationships = [];
		foreach ($oneToManyTables as $oneToManyTable) {
			$table                                    = $oneToManyTable['REFERENCED_TABLE_NAME'];
			$hasMany                                  = $oneToManyTable['TABLE_NAME'];
			$this->oneToManyRelationships[$table][]   = $hasMany;
			$this->manyToOneRelationships[$hasMany][] = $table;
		}
	}

	/**
	 * @param $tableName
	 * @param $tableKey
	 * @return string
	 */
	private function generateLaravelBuilderValueArray($tableName, $tableKey): string
	{
		$capitalizedTableNameWithoutUnderscoresPlural   = str_replace('_', '', ucwords($tableName, '_'));
		$capitalizedTableNameWithSpacesPlural           = str_replace('_', ' ', ucwords($tableName, '_'));
		$unCapitalizedTableNameWithoutUnderscoresPlural = str_replace('_', '', ucwords($tableName, '_'));
		$unCapitalizedTableNameWithoutUnderscoresPlural = lcfirst($unCapitalizedTableNameWithoutUnderscoresPlural);

		// singularize table names
		$singularTableName = Inflect::singularize($tableName);

		// remove underscores
		$capitalizedTableNameWithoutUnderscores       = str_replace('_', '', ucwords($singularTableName, '_'));
		$capitalizedTableNameWithDashes               = str_replace('_', '-', ucwords($singularTableName, '_'));
		$unCapitalizedTableNameWithDashes             = str_replace('_', '-', ($singularTableName));
		$capitalizedTableNamePluralWithoutUnderscores = str_replace('_', '', ucwords($tableName, '_'));

		//Migration Class Name
		$migrationClassName = 'Create'.$capitalizedTableNamePluralWithoutUnderscores.'Table';
		//Migration File Name
		$date                = Carbon::now();
		$migrationDatePrefix = $date->format('Y_m_d_His');

		$migrationFileName = $migrationDatePrefix.'_create_'.$tableName.'_table';
		// uncapitalize first letter
		$unCapitalizedTableNameWithoutUnderscores = lcfirst($capitalizedTableNameWithoutUnderscores);


		$this->laravelBuilderValueArray[$tableKey] = [
			'ControllerName'                                 => $capitalizedTableNameWithoutUnderscores.'Controller',
			'MigrationClassName'                             => $migrationClassName,
			'MigrationFileName'                              => $migrationFileName,
			'MigrationTableName'                             => $tableName,
			'ControllerVariableName'                         => $unCapitalizedTableNameWithoutUnderscoresPlural,
			'ControllerVariableNameSingular'                 => $unCapitalizedTableNameWithoutUnderscores,
			'ControllerCompactName'                          => $unCapitalizedTableNameWithoutUnderscoresPlural,
			'ViewDisplayTableName'                           => $capitalizedTableNameWithSpacesPlural,
			'ViewClassVariablePlural'                        => $unCapitalizedTableNameWithoutUnderscoresPlural,
			'ViewClassVariableSingular'                      => $unCapitalizedTableNameWithoutUnderscores,
			'singularTableName'                              => $singularTableName,
			'capitalizedTableNameWithoutUnderscoresPlural'   => $capitalizedTableNameWithoutUnderscoresPlural,
			'unCapitalizedTableNameWithoutUnderscoresPlural' => $unCapitalizedTableNameWithoutUnderscoresPlural,
			'unCapitalizedTableNameWithoutUnderscores'       => $unCapitalizedTableNameWithoutUnderscores,
			'capitalizedTableNameWithoutUnderscores'         => $capitalizedTableNameWithoutUnderscores,
			'unCapitalizedTableNameWithDashes'               => $unCapitalizedTableNameWithDashes,
			'capitalizedTableNameWithDashes'                 => $capitalizedTableNameWithDashes,
			'ModelClassName'                                 => $capitalizedTableNameWithoutUnderscores,
			'ViewFolderName'                                 => $unCapitalizedTableNameWithDashes,
			'RouteModelName'                                 => $unCapitalizedTableNameWithDashes,
			'Factory'                                        => $tableName,
			'ViewIndexColumnTitleTR'                         => '',
			'ViewIndexColumnValueTR'                         => ''
		];

		return true;
	}

	private function fillLaravelBuilderValueArray($tableName, int $tableKey)
	{
		ini_set('xdebug.var_display_max_depth', 25);


		foreach ($this->tableColumnNames[$tableName] as $columnKey => $columnArray) {
			$columnName                                                                                 = $columnArray['COLUMN_NAME'];
			$displayColumnName                                                                          = str_replace('_', ' ', ucwords($columnName, '_'));
			$this->laravelBuilderValueArray[$tableKey]['Columns'][$columnKey]['ColumnName']             = $columnName;
			$this->laravelBuilderValueArray[$tableKey]['Columns'][$columnKey]['ColumnDisplayName']      = $displayColumnName;
			$this->laravelBuilderValueArray[$tableKey]['Columns'][$columnKey]['DATA_TYPE']              = $columnArray['DATA_TYPE'];
			$this->laravelBuilderValueArray[$tableKey]['Columns'][$columnKey]['ColumnType']             = $columnArray['COLUMN_TYPE'];
			$this->laravelBuilderValueArray[$tableKey]['Columns'][$columnKey]['CharacterMaximumLength'] = $columnArray['CHARACTER_MAXIMUM_LENGTH'];
			$this->laravelBuilderValueArray[$tableKey]['Columns'][$columnKey]['Null']                   = $columnArray['IS_NULLABLE'];
			$this->laravelBuilderValueArray[$tableKey]['Columns'][$columnKey]['ColumnKey']              = $columnArray['COLUMN_KEY'];
			$this->laravelBuilderValueArray[$tableKey]['Columns'][$columnKey]['ColumnDefault']          = $columnArray['COLUMN_DEFAULT'];
			$this->laravelBuilderValueArray[$tableKey]['Columns'][$columnKey]['Extra']                  = $columnArray['EXTRA'];

			$this->laravelBuilderValueArray[$tableKey]['ViewIndexColumnTitleTR'] .= '<td scope="col">'.$displayColumnName.'</td>';
			$this->laravelBuilderValueArray[$tableKey]['ViewIndexColumnValueTR'] .= '<td>{{$'.$this->laravelBuilderValueArray[$tableKey]['unCapitalizedTableNameWithoutUnderscores'].'["'.$columnName.'"]}}</td>';
		}
		if (isset($this->manyToManyRelationships[$tableName]) && count($this->manyToManyRelationships[$tableName]) > 0) {
			foreach ($this->manyToManyRelationships[$tableName] as $key => $value) {
				$this->laravelBuilderValueArray[$tableKey]['Relationships']['ManyToMany'][$key]['table']     = $key;
				$this->laravelBuilderValueArray[$tableKey]['Relationships']['ManyToMany'][$key]['joinTable'] = $value;
			}
		}
		if (isset($this->oneToManyRelationships[$tableName]) && count($this->oneToManyRelationships[$tableName]) > 0) {
			foreach ($this->oneToManyRelationships[$tableName] as $value) {
				$this->laravelBuilderValueArray[$tableKey]['Relationships']['HasMany'][] = $value;
			}
		}
		if (isset($this->manyToOneRelationships[$tableName]) && count($this->manyToOneRelationships[$tableName]) > 0) {
			foreach ($this->manyToOneRelationships[$tableName] as $value) {
				$this->laravelBuilderValueArray[$tableKey]['Relationships']['BelongsTo'][] = $value;
			}
		}
	}
}
