<?php


namespace Davidany\Codegen;


class DbConnectionFactory
{
	public static function createCrudGenConnection($dbName = DB_NAME, $dbHost = LOCALHOST, $dbUser = DB_USERNAME, $dbPassword = DB_PASSWORD)
	{
		$dbh = new PDO("" . DB_TYPE . ":host=" . $dbHost . ";dbname=" . $dbName . "", $dbUser, $dbPassword);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		return $dbh;
	}

	public static function createProjectConnection($dbHost, $dbName, $dbUser, $dbPassword)
	{
		$dbh = new PDO("mysql:host={$dbHostb};dbname={$dbName}", $dbUser, $dbPassword);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		# $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT );
		# $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
		return $dbh;

	}
}
