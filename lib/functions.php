<?php
/*
HEZECOM CMS PRO v1.0
http://hezecom.com
info@hezecom.com
COPYRIGHT 2012 ALL RIGHTS RESERVED
*/

//post
function post($var)
{
	if (isset($_POST[$var])) {
		return $_POST[$var];
	} else {
		return null;
	}
}

function d($data)
{
	if (is_null($data)) {
		$str = "<i>NULL</i>";
	} elseif ($data == "") {
		$str = "<i>Empty</i>";
	} elseif (is_array($data)) {
		if (count($data) == 0) {
			$str = "<i>Empty array.</i>";
		} else {
			$str = "<table style=\"border-bottom:0px solid #000;\" cellpadding=\"0\" cellspacing=\"0\">";
			foreach ($data as $key => $value) {
				$str .= "<tr><td style=\"background-color:#008B8B; color:#FFF;border:1px solid #000;\">".$key."</td><td style=\"border:1px solid #000;\">".d($value)."</td></tr>";
			}
			$str .= "</table>";
		}
	} elseif (is_resource($data)) {
		while ($arr = mysql_fetch_array($data)) {
			$data_array[] = $arr;
		}
		$str = d($data_array);
	} elseif (is_object($data)) {
		$str = d(get_object_vars($data));
	} elseif (is_bool($data)) {
		$str = "<i>".($data ? "True" : "False")."</i>";
	} else {
		$str = $data;
		$str = preg_replace("/\n/", "<br>\n", $str);
	}

	return $str;
}

function dnl($data)
{
	echo d($data)."<br>\n";
}

function ddd($data)
{
	echo dnl($data);
	exit;
}

function ddt($message = "")
{
	echo "[".date("Y/m/d H:i:s")."]".$message."<br>\n";
}

function debug_title($debug_title)
{
	echo '<br>--------------------------------------';
	echo '<br>'.$debug_title.'<br>';
}

//get
function get($var)
{
	if (isset($_GET[$var])) {
		return $_GET[$var];
	} else {
		return null;
	}
}

function get_default($var, $default = '')
{
	if (isset($_GET[$var])) {
		return $_GET[$var];
	} else {
		return $default;
	}
}

//function post_default($var,$default='') {
//	if (isset($_POST[$var])) {
//		return $_POST[$var];
//	} else {
//		return $default;
//	}
//}


function send_to($direction)
{
	if (!headers_sent()) {
		header('Location: '.$direction);
		exit;
	} else {
		print '<script type="text/javascript">';
	}
	print 'window.location.href="'.$direction.'";';
	print '</script>';
	print '<noscript>';
	print '<meta http-equiv="refresh" content="0;url='.$direction.'" />';
	print '</noscript>';
}

//seure sql injection can be improved
function sanitize($value)
{
	$sanitz = mysql_real_escape_string(filter_var($value, FILTER_SANITIZE_STRING));

	return $sanitz;
}

function print_x($value)
{
	echo '<pre style="text-align:left;"><hr />';
	print_r($value);
	echo '</pre><hr />';
}

function print_x2($data)
{
    // capture the output of print_r
    $out = print_r($data, true);

    // replace something like '[element] => <newline> (' with <a href="javascript:toggleDisplay('...');">...</a><div id="..." style="display: none;">
    $out = preg_replace_callback('/([ \t]*)(\[[^\]]+\][ \t]*\=\>[ \t]*[a-z0-9 \t_]+)\n[ \t]*\(/iU', function ($matches) {
        $id = substr(md5(rand() . $matches[0]), 0, 7);
        return $matches[1] . '<a href="javascript:toggleDisplay(\'' . $id . '\');">' . $matches[2] . '</a><div id="' . $id . '" style="display: none;">';
    }, $out);

    // replace ')' on its own on a new line (surrounded by whitespace is ok) with '</div>
    $out = preg_replace('/^\s*\)\s*$/m', '</div>', $out);

    // print the javascript function toggleDisplay() and then the transformed output
    echo '<script language="Javascript">function toggleDisplay(id) { document.getElementById(id).style.display = (document.getElementById(id).style.display == "block") ? "none" : "block"; }</script>' . "\n" . $out;
}

function print_r_tree($data)
{
    // capture the output of print_r
    $out = print_r($data, true);

    // replace something like '[element] => <newline> (' with <a href="javascript:toggleDisplay('...');">...</a><div id="..." style="display: none;">
    $out = preg_replace_callback('/([ \t]*)(\[[^\]]+\][ \t]*\=\>[ \t]*[a-z0-9 \t_]+)\n[ \t]*\(/iU', function ($matches) {
        $id = substr(md5(rand() . $matches[0]), 0, 7);
        return $matches[1] . '<a href="javascript:toggleDisplay(\'' . $id . '\');">' . $matches[2] . '</a><div id="' . $id . '" style="display: none;">';
    }, $out);

    // replace ')' on its own on a new line (surrounded by whitespace is ok) with '</div>
    $out = preg_replace('/^\s*\)\s*$/m', '</div>', $out);

    // print the javascript function toggleDisplay() and then the transformed output
    echo '<script language="Javascript">function toggleDisplay(id) { document.getElementById(id).style.display = (document.getElementById(id).style.display == "block") ? "none" : "block"; }</script>' . "\n" . $out;
}


function generateSignature($arguments, $apiPassword)
{
	return hash_hmac('sha1', $arguments, $apiPassword);
}

function send_to_rr_api($email, $ip_address)
{
	$url = 'http://api.rrddm.com/DDM/Import.cfc?method=submitRecord&ClientID=175';

	$fields = array(
		'DataSourceID'  => '22006',
		'Token'         => 'Y3Q7G7R9F3',
		'TokenPassword' => 'E3K2V2',
		'EmailAddress'  => $email,
		'LeadDate'      => date('Y-m-d H:i:s'),
		'LeadIPAddress' => $ip_address,
		'LeadURL'       => 'visitrotator.com'


	);

	$fields_string = '';
	//url-ify the data for the POST
	foreach ($fields as $key => $value) {
		$fields_string .= $key.'='.$value.'&';
	}
	$fields_string = rtrim($fields_string, '&');

	//open connection
	$ch = curl_init();

	//set the url, number of POST vars, POST data
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, count($fields));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

	//execute post
	$result = curl_exec($ch);

	//close connection
	curl_close($ch);
}

?>
