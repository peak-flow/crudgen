<?php


namespace Davidany\Codegen;

use PDO;
class DbCredential
{

	public function index()
	{
		// display view of projects
		$db   = DB::getInstance();
		$stmt = $db->prepare("SELECT * FROM project ");
		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_OBJ);

	}

	public function create()
	{
		//display view form to create project

	}

	public function store($projectId, $redirect_to)
	{
		//post call to store
		$submit = post('button');
		if ($submit) {
			$db   = DB::getInstance();
			$stmt = $db->prepare("insert into db_credential (  `host`, `database`, `username`, `password`, `project_id` ) values ( :host, :database, :username, :password, :project_id )");
			$stmt->bindParam(':host', $_POST['host']);
			$stmt->bindParam(':database', $_POST['database']);
			$stmt->bindParam(':username', $_POST['username']);
			$stmt->bindParam(':password', $_POST['password']);
			$stmt->bindParam(':project_id', $projectId);
			$stmt->execute();


			send_to($redirect_to);
		}
	}

	public function show($id)
	{
		// display view of specific id
	}

	public function edit($id)
	{
		// display view editable form of specific id
		$db   = DB::getInstance();
		$stmt = $db->prepare("SELECT * FROM project WHERE id=:id   ");
		$stmt->bindParam(':id', $id);

		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_OBJ);
	}

	public function update($id)
	{
		// db call to update specific project
	}

	public function delete($id)
	{
		// db call to delete project
	}

	public function getByProjectId($projectId)
	{
		$db   = DB::getInstance();
		$stmt = $db->prepare("SELECT * FROM db_credential WHERE project_id=:id ");
		$stmt->bindParam(':id', $projectId);
		$stmt->execute();

		return $stmt->fetch(PDO::FETCH_OBJ);


	}
}
