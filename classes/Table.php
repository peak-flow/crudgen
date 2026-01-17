<?php

namespace Davidany\Codegen;


use PDO;

class Table
{

	public $databaseObject;

	public function insert($redirect_to)
	{

		$submit = post('button');

		if ($submit) {
			$db   = DB::getInstance();
			$stmt = $db->prepare("insert into database_connections ( `name`, `host`, `database`, `username`, `password` ) values ( :name, :host, :database, :username, :password )");
			$stmt->bindParam(':name', $_POST['name']);
			$stmt->bindParam(':host', $_POST['host']);
			$stmt->bindParam(':database', $_POST['database']);
			$stmt->bindParam(':username', $_POST['username']);
			$stmt->bindParam(':password', $_POST['password']);
			$stmt->execute();
			send_to($redirect_to);
		}

	}

	public function delete($databaseId)
	{
		$db = DB::getInstance();

		$stmt = $db->prepare('DELETE FROM database_connections WHERE `id` = :id');
		$stmt->bindParam('id', $databaseId);
		$stmt->execute();
	}

	public function selectAll(Database $databaseObject)
	{
		$this->databaseObject = $databaseObject;
		$db                   = DB::getInstance();
		$sql                  = "SELECT * FROM database_connections ";
		$stmt                 = $db->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_OBJ);

	}

	public function listTablesByDatabaseId($databaseResult)
	{

		print_x($databaseResult);

	}

}
