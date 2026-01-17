<?php

namespace Davidany\Codegen;

use PDO;
class TableColumn
{

	/**
	 * TableColumn constructor.
	 */
	public function __construct()
	{
	}

	public function getColumns($tableName, $dbCredential)
	{

		$dbProject = DB::getInstance($dbCredential->database, $dbCredential->host, $dbCredential->username, $dbCredential->password);
		$sql       = "SHOW COLUMNS  FROM $tableName";
		$stmt      = $dbProject->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_COLUMN);

	}
}
