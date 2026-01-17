<?php

namespace Davidany\Codegen;


use PDO;

class Database
{

	public function insert($redirect_to)
	{

		$submit = post('button');

		if ($submit) {
			$db   = DB::getInstance();
			$stmt = $db->prepare("insert into db_credential ( `name`, `host`, `database`, `username`, `password` ) values ( :name, :host, :database, :username, :password )");
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

		$stmt = $db->prepare('DELETE FROM db_credential WHERE `id` = :id');
		$stmt->bindParam('id', $databaseId);
		$stmt->execute();
	}

	public function selectAll()
	{
		$db   = DB::getInstance();
		$sql  = "SELECT * FROM db_credential ";
		$stmt = $db->prepare($sql);
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_OBJ);

	}

	public function getDatabaseById($databaseId)
	{
		$db   = DB::getInstance();
		$sql  = "SELECT * FROM db_credential WHERE id=:id ";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':id', $databaseId);
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_OBJ);

	}

}
