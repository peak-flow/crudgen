<?php

namespace Davidany\Codegen;

use PDO;

class Project
{

	public $name;

	public $directory;

	/**
	 * Project constructor.
	 */
	public function __construct()
	{
	}

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

	public function store($redirect_to)
	{
		//post call to store
		$submit = post('button');
		if ($submit) {
			$db   = DB::getInstance();
			$stmt = $db->prepare("insert into project ( `name`, `directory` ) values ( :name, :directory )");
			$stmt->bindParam(':name', $_POST['name']);
			$stmt->bindParam(':directory', $_POST['directory']);
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

}
