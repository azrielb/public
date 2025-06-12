<?
//exit;
	header ('Content-Type: text/html; charset=utf-8');
$src_file = './a27_Psalms.txt';
$fp = fopen($src_file, 'r');
$tilim = fread($fp, filesize($src_file));
fclose($fp);
$tilim = split("\n~ ",$tilim);
$a=0;
foreach ($tilim as $key => $value) {
	$text = str_replace('! {','{',$value);
	$text = preg_replace("/}\r\n/",'} ',$text);
	$text =  preg_split("/\r\n\r\n/", $text);
	$text[0] = substr($text[0],11);
	$text[0] = strlen($text[0]) > 1 ? substr($text[0], 0, -1) . '"' . substr($text[0], -1) : "$text[0]'";
	$text = implode(".\r\n",$text);
	$text = iconv("windows-1255", "UTF-8", $text);
/*
	$fp = fopen("tilim$key.txt", 'w');
	if (fwrite($fp, $text))	$a++;
	fclose($fp);//*/$a++;
	if ($key==117) {
		echo nl2br($text);
	}
}
echo $a;
?>