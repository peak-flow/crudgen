<?php


namespace Davidany\Codegen;


class File
{
	public function append($path, $data)
	{
		return file_put_contents($path, $data, FILE_APPEND);
	}

}
