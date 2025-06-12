<?php
function del_from_get($del) {
	if ($del['delete']=='del') 
		foreach ($del as $key => $value) 
			if ($a++ > 0 && $key == 'del'.$a && unlink($value) && ++$b)
				echo "$b:&nbsp;&nbsp;<b>$value</b> delete successfully!<BR>";
	return $b;
}
function checkidnum($idnum, $ech = 0) {
	for ( $a = strlen($idnum)-2; $a > -2; $a -= 2 )
	{
		$b = $idnum[$a] * 2;
		$sumval += $idnum[$a + 1] + $b - ($b > 9 ? 9 : 0);
	}
	if ($ech) echo "���� ����� $idnum ";
	if ($sumval % 10)
	{
		if ($ech) echo '���� ����.';
		return false;
	}
	if ($ech) echo '����.';
	return true;
}
function gimatria($num) {
	static $gim = array(
		100 => array(1 => '�', '�', '�', '�'),
		10 => array(1 => '�', '�', '�', '�', '�', '�', '�', '�', '�'),
		1 => array(1 => '�', '�', '�', '�', '�', '�', '�', '�', '�')
	);
	if (abs($num) !== 0 || $num === 0) {
		$num = abs(intval($num));
		if ($num > 1000) {
			$gimatria = gimatria(intval($num / 1000)) . "'";
			$num %= 1000;
		} else {
			$gimatria = '';
		}
		switch ($num) {
			case 674:
				$gimatria .= "����";
				$num = 0;
				break;
			case 304:
				$gimatria .= "��";
				$num = 0;
				break;
			case 275:
				$gimatria .= "���";
				$num = 0;
				break;
			case 270:
				$gimatria .= "��";
				$num = 0;
				break;
		}
		while ($num >= 400) {
			$gimatria .= "�";
			$num -= 400;
		}
		switch ($num) {
			case 272:
				$gimatria .= "���";
				$num = 0;
				break;
			case 274:
				$gimatria .= "���";
				$num = 0;
				break;
			case 298:
				$gimatria .= "���";
				$num = 0;
				break;
			case 344:
				$gimatria .= "���";
				$num = 0;
				break;
			case 351:
				$gimatria .= "���";
				$num = 0;
				break;
		}
		foreach ($gim as $a => $value) {
			if ($num >= $a) {
			    $gimatria .= $value[$num / $a];
			    $num %= $a;
			}
			if ($a == 100 && ($num == 15 || $num == 16)) {
				$gimatria .= '�';
				$num -= 9;
			}
		}
	} else {
		static $mantzapach0 = array('�', '�', '�', '�', '�');
		static $mantzapach1 = array('�', '�', '�', '�', '�');
		$gimatria = 0;
		$num = str_replace($mantzapach0, $mantzapach1, $num);
		$temp = split("'", $num);
		if (isset($temp[1])) {
			foreach ($temp as $value) {
				$gimatria = $gimatria * 1000 + gimatria($value);
			}
		} else {
			foreach ($gim as $a => $value) {
			    foreach ($value as $key => $ot) {
			    	$gimatria += substr_count($num,$ot) * $a * $key;
			    };
			}
		}
	}
	return $gimatria;
}
?>