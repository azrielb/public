<?php
if (!defined('ABC_FUNCTIONS')):
define('ABC_FUNCTIONS', true);
@include_once('abc_date.php');
@include_once('tbs_class.php');

function abc_connect($db = null, $username = 'root', $password = null, $server = null) {
	if (!@mysql_connect($server, $username, $password)) {
		return mysql_abc_err(null, __FILE__, __LINE__);
	}
	if (!$db || @mysql_select_db($db)) {
		return null;
	}
	$err = mysql_abc_err(null, __FILE__, __LINE__);
	@mysql_query($sql = 'CREATE DATABASE ' . $db . ';');
	return mysql_errno() ? $err . mysql_abc_err($sql, __FILE__, __LINE__) : null;
}

function xhtml_head($lang = 'he', $encode = 'utf-8', $dir = null, $htmldir = null) {
	if (!$dir) $dir = $lang == 'he' ? 'rtl' : 'ltr';
	if (!$htmldir) $htmldir = 'ltr';
	return <<<xhtml
<?xml version="1.0" encoding="$encode"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="$lang" lang="$lang" dir="$htmldir">
<head>
<meta http-equiv="content-type" content="text/html;charset=$encode" />
<meta http-equiv="content-language" content="$lang" />
<title>no title</title>
</head>
<body dir="$dir" />
</html>
xhtml;
}

function abc_headers($lang = 'he', $encode = 'utf-8', $dir = null) {
	if (!$dir) $dir = 'ltr';
	header ("Content-Type: text/html; charset=$encode");
	header ("Content-Language: $lang");
	return <<<head
<?xml version="1.0" encoding="$encode"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="$lang" lang="$lang" dir="$dir">
<head>
<meta http-equiv="content-type" content="text/html;charset=$encode" />
<meta http-equiv="content-language" content="$lang" />
head;
}

function abc_goto($location, $http_response_code = 301) {
	if ($http_response_code == 301)	Header( 'HTTP/1.1 301 Moved Permanently' );
	Header( "Location: $location" , true, $http_response_code);
	exit;
}

function echo_mysql_fetch_array($result, &$row, $emptys = false) {
	$row = @mysql_fetch_array($result);
	if (!is_array($row)) return null;
	$a = 0;
	$aa = '';
	reset ($row);
	while (each($row) && list($key1, $value1) = each ($row)) {
		if ($emptys || $value1 || $value1 === '0') $aa .= ++$a . ". $key1 = $value1<br>";
	}
	return ($aa) ? "the line is:<BR>$aa" :'Empty line';
}

function incl($encod = 'UTF8') {
	return (@include_once 'functions' . preg_replace('/win(dows)?(\-)?/i', 'WIN', $encod) . '.php') ? $encod : false;
}

function IsValidEmailAddress($str) {
	return (preg_match("/^[\w\-\.]+\@[\w\-\.]+\.[\w\-]+$/i",$str) == 1);
}

function microtime_float() {
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}

function my_md5($pass) {
	return md5(substr(md5($pass.$pass),-10));
}

function mysql_abc_err($sql = null,$file = null,$line = null) {
	//echo mysql_abc_err($sql,__file__,__line__);
	$line = ($line) ? " in line <b>$line</b>" : '';
	$file = ($file) ? "file <b>$file</b>" : 'unknown file';
	$sql = ($sql) ? "<br>sql query: <b>$sql</b>" : '';
	return "<p align='left' dir='ltr'>Error$line in $file$sql<br>mysql say: (" .  @mysql_errno() . ') ' . @mysql_error() . '</p>';
}

function mysql_db_connect($db, $un, $pswd, $host = null, $new_link = null, $flags = null) {
	// not connect = false, connect and not select db = 0, connect and select db = resource.
	return ($connection = @mysql_connect($host, $un, $pswd, $new_link, $flags)) ? @mysql_select_db($db, $connection) ? $connection : 0 : false;
}

function Send_Mail($From, $FromName, $To, $ToName, $Subject, $Text, $Html, $AttmFiles, $lang) {
	/*
	 <?php
	 //Set these variables yourself:
	 $to = "steve@ispname.com";
	 $subject = "Web mail";
	 $message = "This email has an attachment.";
	 $attachment = "image.jpg";
	 $attachment_MIME_type = "image/jpeg";

	 $handle = fopen ($attachment, "rb");
	 $data = fread ($handle, filesize($attachment));
	 fclose ($handle);

	 $boundary = "---Multipart_Boundary---";

	 $headers = "\nMIME-Version: 1.0\n" .
	 "Content-Type: multipart/mixed;\n" .
	 " boundary=\"" . $boundary . "\"";

	 $data = chunk_split(base64_encode($data));

	 $text = "--" . $boundary . "\n" .
	 "Content-Type:text/plain\nContent-Transfer-Encoding: 7bit\n\n" .
	 $message . "\n\n--" . $boundary . "\n" .
	 "Content-Type: " . $attachment_MIME_type . ";\n name=\"" .
	 $attachment . "\"\nContent-Transfer-Encoding: base64\n\n" .
	 $data . "\n\n--" . $boundary . "--\n";

	 $result = @mail($to, $subject, $text, $headers);

	 if($result) {
	 echo "The email was sent.";
	 } else {
	 echo "The email was not sent.";
	 }
	 ?>

	 */
	$OB = '----=_OuterBoundary_000';
	$IB = '----=_InnerBoundery_001';
	$Html = $Html ? $Html : preg_replace("/\n/" , '<br>' , $Text)
	or die('neither text nor html part present.');
	$Text = $Text ? $Text : 'Sorry, but you need an html mailer to read this mail.';
	$From or die('sender address missing');
	$To or die('recipient address missing');

	$headers = "MIME-Version: 1.0\r\n"
	. "From: $FromName <$From>\n"
	. "To: $ToName <$To>\n"
	. "Reply-To: $FromName <$From>\n"
	. "X-Priority: 1\n"
	. "X-MSMail-Priority: High\n"
	. "X-Mailer: My PHP Mailer\n"
	. "Content-Type: multipart/mixed;\n\tboundary=\"$OB\"\n";

	//Messages start with text/html alternatives in OB
	$Msg = "This is a multi-part message in MIME format.\n"
	. "\n--".$OB."\n"
	. "Content-Type: multipart/alternative;\n\tboundary=\"$IB\"\n\n";

	//plaintext section
	$Msg .= "\n--$IB\n"
	. "Content-Type: text/plain;\n\tcharset=\"iso-8859-8-i\"\n"
	. "Content-Transfer-Encoding: quoted-printable\n\n";
	// plaintext goes here
	$Msg .= "$Text\n\n";

	// html section
	$Msg .= "\n--$IB\n"
	. "Content-Type: text/html;\n\tcharset=\"iso-8859-8-i\"\n"
	. "Content-Transfer-Encoding: base64\n\n";
	// html goes here
	if ($lang == "he") $Html = "<DIV style='direction:rtl'>\n$Html\n</DIV>";
	$Msg .= chunk_split(base64_encode($Html))."\n\n";

	// end of IB
	$Msg .= "\n--$IB--\n";

	// attachments
	if($AttmFiles) {
		foreach($AttmFiles as $AttmFile) {
			$patharray = explode ('/', $AttmFile);
			$FileName = $patharray[count($patharray) - 1];
			$Msg .= "\n--$OB\n"
			. "Content-Type: application/octetstream;\n\tname=\"$FileName\"\n"
			. "Content-Transfer-Encoding: base64\n"
			. "Content-Disposition: attachment;\n\tfilename=\"$FileName\"\n\n";

			//file goes here
			$fd = fopen ($AttmFile, 'r');
			$FileContent = fread($fd, filesize($AttmFile));
			fclose($fd);
			$FileContent = chunk_split(base64_encode($FileContent));
			$Msg .= "$FileContent\n\n";
		}
	}

	//message ends
	$Msg .= "\n--$OB--\n";
	$a = mail($To, $Subject, $Msg, $headers);
	syslog(LOG_INFO, "Mail: Message sent to $ToName <$To>");
	return $a;
}

function myswap(&$a, &$b) {
	$temp = $a;
	$a = $b;
	$b = $temp;
}

function division(&$big, &$small, $div) {
	$big += ($small - ($temp = $small % $div)) / $div;
	if (abs($temp) == $temp) $small = $temp; else {
		--$big;
		$small = $div + $temp;
	}
}

function multiply(&$big, &$small, $mul) {
	$small += ($big - $temp = intval($big)) * $mul;
	$big = $temp;
}

function sum() {
	if (func_num_args() > 1) return sum(func_get_args());
	$b = func_get_arg(0);
	if (!is_array($b)) return $b * 1;
	$a = 0;
	foreach ($b as $value) $a += sum($value);
	return $a;
}

function abc_forms($tag) {
	static $names;
	foreach (array(
		'id' => null, 
		'name' => null, 
		'tag' => 'input',
		'type' => 'text', 
		'title' => 'no_title',
		'label' => null, 
		'value' => '', 
		'text' => '', 
		'before' => '', 
		'between' => '', 
		'after' => '', 
		'others' => '',
		'class' => '', 
		'lclass' => '', 
	) as $key => $value) {
		if (!isset($tag[$key]) || is_null($tag[$key])) $tag[$key] = $value;
	}
	if (is_null($tag['name'])) $tag['name'] = 'abc_name_' . $names++;
	$tag['id'] = $tag['name'] ? " id='" . (is_null($tag['id']) ? $tag['name'] : $tag['id']) . "' name='$tag[name]'" : '';
	if (is_null($tag['label'])) $tag['label'] = $tag['title'];
	$ret = $tag['before'] . ($tag['label'] ? "<label for='$tag[name]'" . ($tag['lclass'] ? " class='$tag[lclass]'" : '') . ">$tag[label]</label>" : '') . $tag['between'];
	$ret .= "<$tag[tag]" . ($tag['type'] ? " type='$tag[type]'" : '') . "$tag[id]$tag[others]" . ($tag['class'] ? " class='$tag[class]'" : '');
	$ret .= ($tag['value'] ? " value='$tag[value]'" : '') . ($tag['text'] ? ">$tag[text]</$tag[tag]>" : ' />') . $tag['after'];
	return $ret;
}

function echo_array($value, $arr = 0, $leng = null, $end = true) /* this function is like print_r() */ {
	if (is_object($value)) return '<OBJECT>';
	if (!is_array($value)) return $leng ? short_str($value, $leng, $end) : $value;
	if (!$value) return '';
	$a = 0;
	$aa = '';
	foreach ($value as $key1=>$value1) {
		if ($key1 == 'GLOBALS' && isset($value1['GLOBALS']) && isset($value1['GLOBALS']['GLOBALS'])) {
			$value1 = '*RECURSION*';
		}
		$aa .= $value1 || $value1 == '0' ? str_repeat('&nbsp;', $arr * 3) . $a++ . '. ' . echo_array($key1) . ' = ' . echo_array($value1, $arr + 1, $leng) . '<br />' : '';
	}
	return ($aa) ? (!$arr ? '<P dir="ltr">' : '') . "Array($a):<BR>$aa" . (!$arr ? '</P>' : '') :'Empty array';
}

function MakeActiveLinks($str) {
	return preg_replace("/(^|[\W])(http:\/\/[\S]*)([\s]|$)/i","\\1<A href=\"\\2\">\\2</A>\\3",$str);
}

function my_slashes($text) {
	$text = preg_replace('/"/', "''", $text);
	$text = preg_replace("/'/", "\'", $text);
	return $text;
}

function phprules() {
	$data = 'iVBORw0KGgoAAAANSUhEUgAAABwAAAASCAMAAAB/2U7WAAAABl'
	. 'BMVEUAAAD///+l2Z/dAAAASUlEQVR4XqWQUQoAIAxC2/0vXZDr'
	. 'EX4IJTRkb7lobNUStXsB0jIXIAMSsQnWlsV+wULF4Avk9fLq2r'
	. '8a5HSE35Q3eO2XP1A1wQkZSgETvDtKdQAAAABJRU5ErkJggg==';
	$data = base64_decode($data);

	$im = imagecreatefromstring($data);
	if ($im !== false) {
		header('Content-Type: image/png');
		imagepng($im);
	}
	else {
		echo 'An error occured.';
	}
	die;
}

function php_img() {
	echo '<img src="?=PHPE9568F36-D428-11d2-A769-00AA001ACF42" />';
}

function pre($value, $type = null, $leng = null) {
	echo '<table dir="ltr"><tr><td>';
	switch ($type) {
		case 'var_dump':
			echo '<pre>';
			var_dump($value);
			echo '</pre>';
			break;
		case 'echo_array':
			echo echo_array($value);
			break;
		case 'short':
			echo echo_array($value, 0, $leng);
			break;
		default:
			echo '<pre>' . print_r($value , true) . '</pre>';
			break;
	}
	echo '</td></tr></table>';
}

function your_typing() {
	return 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . ($_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '' );
}

function short_str($str, $leng = 23, $end = null) {
	if ((is_null($end) && strlen($str) > $leng) || strlen($str) > $leng * 2 + 5) {
		$a = strrpos(substr($str, 0, $leng), ' ');
		$start = substr($str, 0, $a ? $a  : $leng - 1);
		if (!is_null($end)) {
			$b = strpos(substr($str, 0 - $leng), ' ');
			$end = substr($str, $b + 1 - $leng);
		}
		return $start . ' ... ' . $end;
	} else {
		return $str;
	}
}

function make_file_name($filename, $address = '') {
	if (($pos = strrpos($filename, '.')) > 0 || substr($filename, 0, 1) == '.') {
		$ext = substr($filename, $pos);
		$filename = substr($filename, 0, $pos);
	} else {
		$ext = '';
	}
	if (!$filename) $filename = 'a';
	if ($address) $address .= '/';
	$add = '';
	for ($a = 97; $a < 123 && file_exists($address . $filename . $add . $ext); ++$a)
	$add = chr($a);
	for ($a = 97; $a < 123 && file_exists($address . $filename . $add . $ext); ++$a)
	if (!file_exists($address . $filename . chr($a) . 'z' . $ext))
	for ($b = 97; $b < 123 && file_exists($address . $filename . $add . $ext); ++$b)
	$add = chr($a) . chr($b);
	for ($a = 97; $a < 123 && file_exists($address . $filename . $add . $ext); ++$a)
	if (!file_exists($address . $filename . chr($a) . 'zz' . $ext))
	for ($b = 97; $b < 123 && file_exists($address . $filename . $add . $ext); ++$b)
	if (!file_exists($address . $filename . chr($a) . chr($b) . 'z' . $ext))
	for ($c = 97; $c < 123 && file_exists($address . $filename . $add . $ext); ++$c)
	$add = chr($a) . chr($b) . chr($c);
	return file_exists($address . $filename . $add . $ext) ? false : $filename . $add . $ext;
}

function tbs_head($pagetitle = null, $encode = null, $lang = null, $dir = null, $styles = null, $htmldir = 'ltr') {
	$thetexts = array(
		'mynull' => '',
		'encode' => $encode ? $encode : 'UTF-8',
		'lang' => $lang ? $lang : 'he',
		'pagetitle' => $pagetitle ? $pagetitle : 'No title',
		'dir' => $dir ? $dir : 'rtl',
		'htmldir' => $htmldir,
	);
	$TBS = new clsTinyButStrong;
	$sent = headers_sent() ? false : true;
	if ($sent) {
		header("Content-Type: text/html; charset=$encode");
		header("Content-Language: $lang");
		$TBS->LoadTemplate(dirname(__FILE__) . '/head_tbs.html', 'utf-8');
	} else {
		$TBS->LoadTemplate(dirname(__FILE__) . '/head_tbs1.html', 'utf-8');
	}
	$TBS->MergeField('thetexts', $thetexts);
	$TBS->MergeBlock('styles', $styles = (array)$styles);
	$html = $TBS->Show(true);
	$GLOBALS['arr'] = '';
	$TBS = NULL;
	echo iconv('UTF-8', $encode, html_entity_decode($html));
	return $sent;
}

function test_time() {
	$times = 1000000;
	$time[] = microtime_float();
	$a=0;while ($a++<$times)
	while ($march_day++ < 100) {
		$month = $march_day > 31 ? $march_day > 61 ? 5 : 4 : 3;
		$day = $march_day > 31 ? $march_day > 61 ? $march_day - 61 : $march_day - 31 : $march_day;
	}
	$time[] = microtime_float();
	$a=0;while ($a++<$times)
	while ($march_day++ < 100) {
		$month = $march_day / 30.5;
		$day = $march_day % 30.5;
	}
	$time[] = microtime_float();
	foreach ($time as $key => $value) {
		if ($key) echo "\n" , $value - $time[$key - 1];
	}
}

function sqlmktime($sql_time) {
	$sql_time = preg_split('/[: -]/', $sql_time);
	return mktime($sql_time[3], $sql_time[4], $sql_time[5], $sql_time[1], $sql_time[2], $sql_time[0]);
}

function settimeSMH($seconds, $minutes = 0, $hours = 0) {
	$time = $seconds + ($minutes + $hours * 60) * 60;
	$sgn = $time / abs($time);
	$time = abs($time);
	$hours = intval($time / 3600);
	$minutes = intval($time / 60) % 60;
	$seconds = $time - intval($time / 60) * 60;
	return compact('time', 'hours', 'minutes', 'seconds');
}

function convert_mysql() {
	return null;
	mysql_query("SET NAMES 'utf8'");
	$query = mysql_query("select * from videos_lables_reservation");
	$sqls=array();

	while ($result=@mysql_fetch_assoc($query)) {
		foreach($result as $key=>$value) $result[$key]=addslashes($value);
		$sqls[] = iconv('UTF-8', 'windows-1255', implode("', '" ,$result));
	}
	mysql_query("SET NAMES 'latin1'");
	foreach ($sqls as $sql) {
		mysql_query($sql = "insert into `Avideos_lables_reservation` VALUES('$sql');");
		if (mysql_errno()) echo mysql_abc_err($sql, __FILE__, __LINE__) , '<br>';
	}
}

/*
 exit("".__LINE__);

 func_num_args
 func_get_arg
 func_get_args
 <input name="userfile[]" type="file" accept="image/gif,image/jpeg" />
 */
endif;
?>
