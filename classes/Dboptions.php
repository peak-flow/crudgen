<?php

namespace Davidany\Codegen;

use DB;
use PDO;

class Dboptions
{

	public function dbSelect($table, $fieldname = null, $id = null, $classname)
	{
		$db   = DB::getInstance();
		$sql  = "SELECT * FROM `$table` WHERE `$fieldname`=:id";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':id', $idd);
		//$id=$idd;
		$stmt->execute();
		echo $db_name . $db_host;

		return $stmt->fetchObject($classname);
	}

	public function rawSelect($sql)
	{
		$db = DB::getInstance();

		return $db->query($sql);
	}

	public function rawQuery($sql)
	{
		$db = DB::getInstance();
		$db->query($sql);
	}

	public function dbInsert($table, $values)
	{
		$db         = DB::getInstance();
		$fieldnames = array_keys($values[0]);
		$size       = sizeof($fieldnames);
		$i          = 1;
		$sql        = "INSERT INTO $table";
		$fields     = '( ' . implode(' ,', $fieldnames) . ' )';
		$bound      = '(:' . implode(', :', $fieldnames) . ' )';
		$sql        .= $fields . ' VALUES ' . $bound;
		$stmt       = $db->prepare($sql);
		foreach ($values as $vals) {
			$stmt->execute($vals);
		}
	}

	public function dbUpdate($table, $fieldname, $value, $pk, $id)
	{
		$db   = DB::getInstance();
		$sql  = "UPDATE `$table` SET `$fieldname`='{$value}' WHERE `$pk` = :id";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':id', $id, PDO::PARAM_STR);
		$stmt->execute();
	}

	public function dbDelete($table, $fieldname, $id)
	{
		$db   = DB::getInstance();
		$sql  = "DELETE FROM `$table` WHERE `$fieldname` = :id";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(':id', $id, PDO::PARAM_STR);
		$stmt->execute();
	}


	public function get_column_names($table)
	{

		$db = DB::getInstance();

		$rows = $db->query("SELECT * FROM `$table` LIMIT 1");
		for ($i = 0; $i < $rows->columnCount(); $i++) {
			$column    = $rows->getColumnMeta($i);
			$columns[] = $column['name'];
		}

		return $columns;


	}
}

?>
