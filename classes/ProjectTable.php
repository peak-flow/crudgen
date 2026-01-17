<?php


namespace Davidany\Codegen;

use PDO;

class ProjectTable
{
	/**
	 * ProjectTable constructor.
	 */
	public function __construct()
	{
	}

	public function getListOfTables($projectId, $dbCredential)
	{

		$dbProject = DB::getInstance($dbCredential->database, $dbCredential->host, $dbCredential->username, $dbCredential->password);
		$sql       = "SHOW TABLES FROM $dbCredential->database";
		$stmt      = $dbProject->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_COLUMN);


	}
}
