<?php
if (!class_exists('abc_date')) include_once('../berger/abc_date.php');
if (!defined('ABC_FUNCTIONS')) include_once('../berger/functions.php');
define('TILIM_DEFAULT_LANG', 'he');
define('TILIM_DEFAULT_ENCODE', 'UTF-8');
define('TILIM_FILE', './a27_Psalms.txt');
/////////////////////////////////////////////////////////
//debugeing
error_reporting(E_ALL);
//$debuger = ($_SERVER['HTTP_HOST'] == 'tilim' ? 'localhost/' : '') . 'tilim';
/////////////////////////////////////////////////////////
class titlim_gurnisht {
	/*
	 זמן יוניקס הוא
	 מ13 דצמבר 1901 20:45:52
	 עד 19 ינואר 2038 03:14:07

	 פעולות לביצוע:
	 //	 תמיכה בתאריכים לפני זמן יוניקס
	 חודש אדר

	 פונקציות מתוך ספרית הפונקציות שלי:
	 abc_forms
	 abc_headers
	 abc_goto
	 gimatria (TILIM_DEFAULT_ENCODE)
	 my_md5
	 abc_date::minus_heb
	 abc_date::is_heb
	 abc_connect();
	 incl();
	 */
}

function tilim_thetexts($lang = null, $encode = TILIM_DEFAULT_ENCODE) {
	if (!incl($encode)) $encode = incl();
	$langs = array(
		TILIM_DEFAULT_LANG => TILIM_DEFAULT_LANG,
		'he' => 'he', 
		'en' => 'en', 
	);
	if (!empty($langs[$lang])) {
		$lang = $langs[$lang];
	} else if (!empty($langs[substr($lang, 0, 2)])) {
		$lang = $langs[substr($lang, 0, 2)];
	} else {
		$lang = TILIM_DEFAULT_LANG;
	}
	$ret = $lang == 'en' ? array(
		'lang' => 'en',
		'olang' => 'he',
		'dir' => 'ltr',
		'adir' => 'rtl',
		'align' => 'left',
		'oalign' => 'right',
		'msg_allow_characters' => 'נא להשתמש לשמות באותיות (עברית ו/או אנגלית), מספרים, רווחים, מקפים וקו-תחתון בלבד!',
		'msg_forget_password' => 'Have you forgotten your password? Enter your username and click here.',
		'name' => 'name',
		'password' => 'password',
		'repeat_password' => 'repeat password',
		'email' => 'Email',
		'register' => 'register',
		'invalid_username' => 'invalid username',
		'invalid_password' => 'invalid password',
		'passwords_not_identical' => 'Passwords are not identical',
		'invalid_email' => 'invalid email',
		'exist_username' => 'exist username',
		'exist_email' => 'exist email',
		'unknown_error' => 'unknown error',
		'error_in_sql' => 'Error in sql',
		'log_in' => 'log in',
		'reg_success' => 'registring successfully',
		'invalid_login' => 'invalid login',
		'delcap' => 'delete cap.',
		'dellearning' => 'delete learning',
		'select_cap' => 'fixed cap',
		'cap' => 'cap.',
		'born' => 'born',
		'no_name' => 'no name',
		'on' => 'show all',
		'off' => 'hide all',
		'pagetitle' => 'tehilim',
		'reg' => 'register',
		'login' => 'log in',
		'add_cap' => 'add cap.',
		'logout' => 'log out',
		'day' => 'day',
		'month' => 'month',
		'year' => 'year',
		'heb_months' => array (1 => "Tishrey", "Cheshvan", "Kislev", "Tevet", "Shevat", "Adar / Adar-I", "Adar-II", "Nissan", "Iyar", "Sivan","Tammuz", "Av", "Elul"),
		'cal' => 0,
		'editcap' => 'editcap',
		'moveupcap' => 'moveupcap',
		'moveuplearning' => 'moveuplearning',
		'edit_cap' => 'edit_cap',
		'elul_caps' => 'Elul 3 cap.',
		'kipur_caps' => 'Yom Kipur - 36 cap.',
		'weekly' => 'weekly cap.',
		'monthly-elul' => 'monthly cap. (including Elul 3 cap.)',
		'monthly' => 'monthly cap.',
		'regular_learning' => 'Regular learning',
		'nikud' => array('no nikud', 'plus nikud'),
		'username_not_exist' => 'username does not exist',
	) : array(
		'lang' => 'he',
		'olang' => 'en',
		'dir' => 'rtl',
		'adir' => 'ltr',
		'align' => 'right',
		'oalign' => 'left',
		'msg_allow_characters' => 'נא להשתמש לשמות באותיות (עברית ו/או אנגלית), מספרים, רווחים, מקפים וקו-תחתון בלבד!',
		'msg_forget_password' => 'שכחת את סיסמתך? הכנס את שם המשתמש שלך ולחץ כאן.',
		'name' => 'שם',
		'password' => 'סיסמה',
		'repeat_password' => 'חזור סיסמה',
		'email' => 'דואר אלקטרוני',
		'register' => 'הרשמה',
		'invalid_username' => 'שם שגוי',
		'invalid_password' => 'סיסמה שגויה',
		'passwords_not_identical' => 'הסיסמאות אינן זהות',
		'invalid_email' => 'כתובת דואר אלקטרוני שגויה',
		'error_in_sql' => 'אין אפשרות ליצור קשר עם מסד הנתונים',
		'exist_username' => 'שם המשתמש שבחרת כבר קיים במערכת',
		'exist_email' => 'כתובת הדואר האלקטרוני שנבחרה כבר קיימת במערכת',
		'unknown_error' => 'שגיאה בלתי מזוהה',
		'log_in' => 'כניסה',
		'reg_success' => 'ההרשמה הצליחה.',
		'invalid_login' => 'שגיאה בשם המשתמש או בסיסמה',
		'dellearning' => 'מחק שיעור זה',
		'delcap' => 'מחק פרק זה',
		'select_cap' => 'פרק קבוע:',
		'cap' => 'פרק',
		'born' => 'תאריך לידה עברי',
		'no_name' => 'ללא שם',
		'on' => 'הצג הכל',
		'off' => 'הסתר הכל',
		'pagetitle' => 'פרקי תהילים לפי בחירת המשתמש',
		'reg' => 'רישום למערכת',
		'login' => 'כניסה למערכת',
		'add_cap' => 'הוסף פרק',
		'logout' => 'החלף משתמש',
		'day' => 'יום',
		'month' => 'חודש', 
		'year' => 'שנה',
		'heb_months' => array(1 => "תשרי", "חשון", "כסלו", "טבת", "שבט", "אדר / אדר א'", "אדר ב'", "ניסן", "אייר", "סיון", "תמוז", "אב", "אלול"),
		'cal' => CAL_JEWISH_ADD_ALAFIM_GERESH,
		'editcap' => 'ערוך פרק זה',
		'moveuplearning' => 'הזז שיעור זה למעלה',
		'moveupcap' => 'הזז פרק זה למעלה',
		'edit_cap' => 'עריכת פרק',
		'elul_caps' => 'שלשת הפרקים שאומרים בחודש אלול',
		'kipur_caps' => '36 הפרקים שאומרים ביום כיפור',
		'weekly' => 'חלוקה שבועית',
		'monthly-elul' => 'חלוקה חודשית (כולל 3 הפרקים הנאמרים באלול)',
		'monthly' => 'חלוקה חודשית',
		'regular_learning' => 'שיעור קבוע',
		'nikud' => array('למהדורה ללא ניקוד לחץ כאן', 'למהדורה המנוקדת לחץ כאן'),
		'username_not_exist' => 'שם המשתמש אינו קיים במערכת',
	);
	$ret['PHP_SELF'] = $_SERVER['PHP_SELF'] . '?' . preg_replace('/&*\z/', '' ,preg_replace('/(delcap|logout|editcap|moveupcap)[=\d]*&/', '', $_SERVER['QUERY_STRING'] . '&'));
	$ret['encode'] = $encode;
	return $ret;
}
function tilim_hebdate_text_he($date) {
	global $tilim_thetexts;
	return gimatria($date['day']) . ' ' . $tilim_thetexts['heb_months'][$date['month']] . ' ' . gimatria($date['year']);
}
function tilim_hebdate_text_en($date) {
	global $tilim_thetexts;
	return $tilim_thetexts['heb_months'][$date['month']] . ' ' . $date['day'] . ', ' . $date['year'];
}

function tilim_install() {
	$connect = abc_connect('tilim');
	if ($connect) {
		$connect2 = abc_connect('b16_8790600_tilim', 'b16_8790600', '770770', 'sql308.byethost16.com');
		if ($connect2) {
			echo $connect . $connect2;
			return false;
		}
	}
	if (!tilim_set_all_caps(TILIM_FILE)) return false;
	$sqls = array(
	'tilim_users' => "CREATE TABLE `tilim_users` (
		`user_id` mediumint(8) NOT NULL AUTO_INCREMENT,
		`username` varchar(25) NOT NULL,
		`user_password` varchar(32) NOT NULL,
		`user_email` varchar(255) NOT NULL DEFAULT '',
		`user_newpasswd` varchar(32) DEFAULT NULL,
		PRIMARY KEY (`user_id`,`username`,`user_email`),
		UNIQUE KEY `username` (`username`),
		UNIQUE KEY `user_email` (`user_email`)
	) ENGINE=MyISAM DEFAULT CHARSET=latin1;",
	'tilim_caps' => "CREATE TABLE `tilim_caps` (
		`id` int(10) NOT NULL AUTO_INCREMENT,
		`user_id` int(10) unsigned NOT NULL,
		`name` varchar(120) DEFAULT NULL,
		`cap` varchar(12) NOT NULL,
		`order` int(11) DEFAULT '0',
		PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=latin1;"
	);
	foreach (array_keys($sqls) as $tableName) {
		@mysql_query(sprintf('select * from %s limit 1;', $tableName));
		if (!@mysql_errno()) return $tableName;
	}
	error_log('The user-table and the caps-table do not exist.');
	foreach ($sqls as $tableName => $sql) {
		@mysql_query($sql);
		if (@mysql_errno()) {
			error_log('The table ' . $tableName . ' did not create.');
			return false;
		}
	}
	error_log('The user-table and the caps-table have been created.');
	return true;
}
function tilim_set_all_caps($filename) {
	$numcups = @mysql_fetch_object(@mysql_query('SELECT COUNT(`capnum`) AS caps FROM `tilim_tilim`'))->caps;
	if ($numcups == 172) return true;
	error_log('There are only ' . intval($numcups) . ' caps.');
	@mysql_query('DROP TABLE IF EXISTS `tilim_tilim`;');
	@mysql_query('CREATE TABLE IF NOT EXISTS `tilim_tilim` (`id` smallint(5) unsigned NOT NULL, `capnum` varchar(20) NOT NULL, ' .
		'`cap` text NOT NULL, PRIMARY KEY  (`id`), UNIQUE KEY `capnum` (`capnum`)) ENGINE=MyISAM DEFAULT CHARSET=latin1;');
	$fp = @fopen($filename, 'r');
	$tilim = @fread($fp, filesize($filename));
	@fclose($fp);
	$tilim = preg_split("/\n~ /", "$tilim\n~ ");
	$caps_counter = 0;
	unset($tilim[0]);
	foreach ($tilim as $key => $text) {
		$text =  preg_split("/\r\n\r\n/", $text);
		$text[0] = substr($text[0],11);
		$text[0] = strlen($text[0]) > 1 ? substr($text[0], 0, -1) . '"' . substr($text[0], -1) : "$text[0]\'";
		$sql = sprintf('INSERT INTO `tilim`.`tilim_tilim` (`id`, `capnum`, `cap`) VALUES (%d, \'%s\', \'%s\');', $key, $text[0], $text[1]);
		@mysql_query(iconv("windows-1255", TILIM_DEFAULT_ENCODE, $sql));
		if (mysql_affected_rows() != 1) {
			error_log('Only ' . ($key - 1) . ' caps have been saved.');
			return false;
		}
		if ($key == 119) {
			$text[2] = explode("\r\n", $text[1]);
			for ($ot = 1; $ot < 23; ++$ot) {
				$paragraph = '';
				for ($pasuk = ($ot - 1) * 8; $pasuk < $ot * 8; ++$pasuk) $paragraph .= $text[2][$pasuk] . "\r\n";
				$sql = 'INSERT INTO `tilim`.`tilim_tilim` (`id`, `capnum`, `cap`) VALUES (%d, \'%s\', \'%s\');';
				$sql = sprintf($sql, 200 + $ot, $text[0] . ' - ' . substr($paragraph, strpos($paragraph, ' ') + 1, 1) ,$paragraph);
				@mysql_query(iconv("windows-1255", TILIM_DEFAULT_ENCODE, $sql));
				if (mysql_affected_rows() != 1) {
					error_log('Only 119 caps and ' . ($ot - 1) . ' paragraphs from cap. 119 have been saved.');
					return false;
				}
			}
		}
		if ($key == 150) {
			error_log('The caps have been saved.');
			return true;
		}
	}
	error_log('Only ' . $key . ' caps have been saved.');
	return false;

}
function tilim_from_born($Hborn, $Htilim = null, $adar = false) {
	$gil = abc_date::minus_heb($Hborn, jdtojewish(unixtojd($Htilim)));
	return is_null($gil) ? null : $gil['years'] + ($adar && $gil['adar'] ? 2 : 1);
}
function tilim_select($cap, $end = 0) {
	// TODO: null == today. I have to change this function that we'll be able to put here another date.
	global $tilim_thetexts, $tilim_date_change;
	static $datenow = null;
	if (is_null($datenow)) $datenow = time();
	if ($end) {
		if ($end == 119.5) {
			$cap = 201;
			$end = 213;
		} else if ($cap == 119.5) {
			$cap = 213;
			$end = 223;
		}
		$caps = '';
		$query = @mysql_query($sql = sprintf('SELECT * FROM `tilim_tilim` WHERE id >= %s AND id < %s;', intval($cap), intval($end)));
		while ($cap = @mysql_fetch_array($query)) $caps .= "$cap[capnum]\n$cap[cap]";
		return $caps;
	}
	switch ($cap) {
		case 'weekly':
			$weekly = array(1, 30, 51, 73, 90, 107, 120, 151);
			$today = intval(date('w', $datenow));
			return array('capnum' => $tilim_thetexts[$cap], 'cap' => tilim_select($weekly[$today], $weekly[$today + 1]));
		case 'monthly+elul':
			$cap = 'monthly-elul';
		case 'monthly-elul':
		case 'monthly':
			list($month, $day, $year) = explode('/', jdtojewish(unixtojd($datenow) + $tilim_date_change));
			$monthly = array(1, 10, 18, 23, 29, 35, 39, 44, 49, 55, 60, 66, 69, 72, 77, 79, 83, 88, 90, 97, 104, 106, 108, 113, 119, 119.5, 120, 135, 140, 145, 151);
			$monthly_caps = array(
				'capnum' => $tilim_thetexts[$cap], 
				'cap' => tilim_select($monthly[$day - 1], $monthly[$day + ($day == 29 && !abc_date::is_heb($month . '/30/' . $year))]),
			);
			if ($cap == 'monthly-elul') {
				if ($month == 13) $end = $day * 3 + 1;
				else if ($month == 1) {
					if ($day < 10) $end = $day * 3 + 88;
					else if ($day == 10) $monthly_caps['cap'] .= '<h3>' . $tilim_thetexts['kipur_caps'] . '</h3>' . tilim_select(115, 151);
				}
				if ($end) $monthly_caps['cap'] .= "\n$tilim_thetexts[elul_caps]\n\n" . tilim_select($end - 3, $end);
			}
			return $monthly_caps;
		default:
			$sql = 'SELECT * FROM `tilim_tilim` WHERE id = ' . intval(strpos($cap, '/') ? tilim_from_born($cap, $datenow) : $cap);
			$row = @mysql_fetch_array(@mysql_query($sql));
			return empty($row['capnum']) ? array('capnum' => tilim_err_msgs(0x20, true), 'cap' => '') : $row;
	}
}
function tilim_add() {
	global $tilim_thetexts, $tilim_user;
	if ($_POST['user_id'] != $tilim_user['user_id']) return 0x1;
	$id = isset($_POST['cap_id']) ? intval($_POST['cap_id']) : 0;
	foreach (array('weekly','monthly-elul', 'monthly') as $variable) if (isset($_POST[$variable])) {
		$cap = $variable;
		$capname = $tilim_thetexts['regular_learning'];
		break;
	}
	if (empty($cap)) {
		$capname = isset($_POST['capname']) ? trim(preg_replace('/[^\d\s\wא-ת]/i', ' ', $_POST['capname'])) : '';
		$capname = $capname ? preg_replace('/\s+/' , ' ', $capname) : $tilim_thetexts['no_name'];
		$cap = isset($_POST['born_cap']) ? abs(intval($_POST['born_cap'])) : 0;
		if (isset($_POST['born_cap']) && $_POST['born_cap'] != $cap . '') $cap = gimatria($_POST['born_cap']);
		if (!is_numeric($cap) || $cap > 150) $cap = 0;
		if (!$cap) {
			$born = array();
			foreach (array('month', 'day', 'year') as $value) {
				if (!isset($_POST['born_' . $value])) {
					$born = null;
					break;
				} else {
					$born[$value] = $_POST['born_' . $value];
				}
			}
			if (abc_date::is_heb($born)) $capname = addslashes($capname . ' - ' . call_user_func('tilim_hebdate_text_' . $tilim_thetexts['lang'], $born));
			$cap = implode('/', $born);
		}
		if (!$cap) return 0x20;
	}
	$order = $id ? $id : 1 + @mysql_fetch_object(@mysql_query('SELECT MAX(`order`) AS maxorder FROM `tilim_caps`;'))->maxorder;
	$sql = sprintf($id ? "UPDATE `tilim_caps` SET `name` = '%s', `cap` = '%s' WHERE `user_id` = '%s' AND `id` = '%s';" :
		"INSERT INTO `tilim_caps` (`name`, `cap`, `user_id`, `order`) VALUES ('%s', '%s', '%s', '%s');", $capname, $cap, $tilim_user['user_id'], $order);
	@mysql_query($sql);
	return @mysql_affected_rows() == 1 ? 0 : 0x20;
}
function tilim_edit() {
	global $tilim_thetexts, $tilim_user, $tilim_select, $tilim_forms;
	$sql = sprintf("SELECT * FROM `tilim_caps` WHERE `user_id`='%s' AND `id`='%s';", $tilim_user['user_id'], intval($_GET['editcap']));
	$cap = @mysql_fetch_array(@mysql_query($sql));
	if (empty($cap['id'])) return 0x20;
	foreach ($tilim_forms as $key => $form) {
		if ($form['id'] == 'add_cap_form') break;
	}
	if ($tilim_forms[$key]['id'] != 'add_cap_form') return 0x20;
	$form['legend'] = $tilim_thetexts['edit_cap'];
	$form['submits'] = array(array('name' => 'edit_cap', 'value' => $tilim_thetexts['edit_cap']) + $form['submits'][0]);
	$form['hiddentag'][] = array('name' => 'cap_id', 'value' => $cap['id'], 'others' => '');
	$tilim_select = array();
	if (is_numeric($cap['cap'])) {
		$tilim_select['cap'] = $cap['cap'];
		$tilim_select['name'] = $cap['name'];
	} else {
		$tilim_select['name'] = substr($cap['name'], 0, strrpos($cap['name'], '-') - 1);
		list($tilim_select['month'], $tilim_select['day'], $tilim_select['year']) = explode('/', $cap['cap']);
	}
	$tilim_forms[$key] = $form;
}
function tilim_del() {
	global $tilim_thetexts, $tilim_user;
	$del = intval($_GET['delcap']);
	$sql = "DELETE FROM `tilim_caps` WHERE `user_id`='$tilim_user[user_id]' AND `id`='$del';";
	$query = @mysql_query($sql);
	if (@mysql_affected_rows() == 1) {
		abc_goto($tilim_thetexts['PHP_SELF'], 302);
	} else {
		return 0x20;
	}
}
function tilim_moveup() {
	global $tilim_thetexts, $tilim_user;
	$moveup = array('id' => intval($_GET['moveupcap']));
	$moveup['order'] = @mysql_fetch_object(@mysql_query("SELECT `order` FROM `tilim_caps` WHERE `user_id`='$tilim_user[user_id]' AND `id`='$moveup[id]';"))->order;
	if (empty($moveup) || empty($moveup['order'])) return 0x20;
	$movedown = @mysql_fetch_array(@mysql_query(
		"SELECT `id`, `order` FROM `tilim_caps` WHERE `user_id`='$tilim_user[user_id]' AND `order`<='$moveup[order]' AND `id` <> $moveup[id] ORDER BY `order` DESC LIMIT 1;"
	));
	if (empty($movedown) || empty($movedown['order']) || $movedown['order'] == $moveup['order']) {
		$query = @mysql_query(
			"SELECT `id`, `order` FROM `tilim_caps` WHERE `user_id`='$tilim_user[user_id]' AND `order`='$moveup[order]' AND `id` <> $moveup[id] ORDER BY `id` DESC;"
		);
		$counter = 0;
		while ($movedown = @mysql_fetch_object($query)->id) {
			$order = 1 + @mysql_fetch_object(@mysql_query('SELECT MAX(`order`) AS maxorder FROM `tilim_caps`;'))->maxorder;
			$sql = "UPDATE `tilim_caps` SET `order`='$order' WHERE `id`='$movedown';";
			$query = @mysql_query($sql);
			if (@mysql_affected_rows() != 1) return 0x20;
			else ++$counter;
		}
		if (!$counter) return 0x20;
	} else {
		$sql = "UPDATE `tilim_caps` SET `order`='$moveup[order]' WHERE `id`='$movedown[id]';";
		$query = @mysql_query($sql);
		if (@mysql_affected_rows() != 1) return 0x20;
		$sql = "UPDATE `tilim_caps` SET `order`='$movedown[order]' WHERE `id`='$moveup[id]';";
		$query = @mysql_query($sql);
		if (@mysql_affected_rows() != 1) return 0x20;
	}
	abc_goto($tilim_thetexts['PHP_SELF'], 302);
}

function tilim_form($form_name) {
	global $tilim_thetexts, $tilim_user;
	if (!in_array($form_name, array('add_cap', 'reg', 'login'))) return null;
	$form['id'] = $form_name . '_form';
	$form['legend'] = $tilim_thetexts[$form_name];
	$form['inputtag'] = array();
	$atag = array(
		'value' => '', 
		'others' => '',
	);
	$form['submits'][] = array(
		'name' => $form_name, 
		'value' => $tilim_thetexts[$form_name != 'login' ? $form_name == 'reg' ? 'register' : 'add_cap' : 'log_in'], 
		'others' => '',
	);
	if ($form_name != 'login') $form['msgS'] = $tilim_thetexts['msg_allow_characters'];
	else $form['submits'][] = array(
		'name' => 'forget_pswd',
		'value' => $tilim_thetexts['msg_forget_password'],
		'others' => '',
	);
	if ($form_name != 'add_cap') {
		foreach (($form_name == 'login'
		? array('username' => 'name', 'userpassword' => 'password')
		: array('user_name' => 'name', 'user_password' => 'password', 'user_newpasswd' => 'repeat_password', 'user_email' => 'email')
		) as $key => $value) {
			$atag['name'] = $key;
			$atag['label'] = $tilim_thetexts[$value] . ':';
			$atag['type'] = substr($value,-8) == 'password' ? 'password' : 'text';
			$form['inputtag'][] = $atag;
		}
	} else {
		$form['inputtag'][] = array('name' => 'capname', 'type' => 'text', 'label' => "$tilim_thetexts[name]:", 'value' => '', 'others' => '');
		$atag = array('others' => '', 'open' => '', 'close' => '', 'optgrouptag' => array(array('name' => '')));
		foreach (array('cap', 'day', 'month', 'year') as $value) {
			$atag['name'] = 'born_' . $value;
			$atag['label'] = $tilim_thetexts[$value] . ':';
			$form['selecttag'][] = $atag;
			$atag['others'] = 'disabled="disabled" ';
		}
		$form['selecttag'][0]['open'] = $form['selecttag'][0]['close'] = $form['selecttag'][1]['open'] = $form['selecttag'][3]['close'] = 'p';
		$form['selecttag'][0]['radio'] = array(
			'name' => 'cap_born',
			'title' => $tilim_thetexts['select_cap'],
			'value' => '1',
			'others' => 'checked="checked" ',
			'label' => $tilim_thetexts['select_cap'],
			'hname' => 'cap_born_1',
			'hvalue' => 'cap/day-month-year',
			'hothers' => '',
		);
		$form['selecttag'][1]['radio'] = array(
			'name' => 'cap_born',
			'title' => $tilim_thetexts['born'] . ':',
			'value' => '2',
			'others' => '',
			'label' => $tilim_thetexts['born'] . ':',
			'hname' => 'cap_born_2',
			'hvalue' => 'day-month-year/cap',
			'hothers' => '',
		);
		foreach ($form['selecttag'][0]['optgrouptag'] = array(
		array('name' => 'ספר ראשון', 'caps' =>'1-41'),
		array('name' => 'ספר שני', 'caps' =>'42-72'),
		array('name' => 'ספר שלישי', 'caps' =>'73-89'),
		array('name' => 'ספר רביעי', 'caps' =>'90-106'),
		array('name' => 'ספר חמישי', 'caps' =>'107-150')
		) as $key => $value) {
			list($start, $end) = explode('-', $value['caps']);
			for ($a = $start; $a <= $end; ++$a) {
				$form['selecttag'][0]['optgrouptag'][$key]['optiontag'][$a] = array('id' => 'cap' . $a, 'value' => $a, 'text' => gimatria($a));
			}
		}
		for ($a = 1; $a < 31; ++$a) {
			$form['selecttag'][1]['optgrouptag'][0]['optiontag'][$a] = array('id' => 'day' . $a, 'value' => $a, 'text' => gimatria($a));
		}
		foreach ($tilim_thetexts['heb_months'] as $a => $value) {
			$form['selecttag'][2]['optgrouptag'][0]['optiontag'][$a - 1] = array('id' => 'month' . $a, 'value' => $a, 'text' => $value);
		}
		$end = intval(date('Y')) + 3760 - 150;
		for ($a = $end + 151; $a > $end; --$a) {
			$form['selecttag'][3]['optgrouptag'][0]['optiontag'][$a] = array('id' => 'day' . $a, 'value' => $a, 'text' => gimatria($a));
		}
		$form['hiddentag'][] = array('name' => 'user_id', 'value' => $tilim_user['user_id'], 'others' => '');
		foreach (array('weekly','monthly-elul', 'monthly') as $variable) $form['submits'][] = array(
			'name' => $variable, 
			'value' => $tilim_thetexts[$variable], 
			'others' => '',
		);
	}
	return $form;
}
function tilim_login() {
	global $tilim_user;
	$username = !isset($_POST['user_name']) ? isset($_POST['username']) ? $_POST['username'] : '' : $_POST['user_name'];
	$user_password = !isset($_POST['user_password']) ? isset($_POST['userpassword']) ? md5($_POST['userpassword']) : '' : md5($_POST['user_password']);
	if (isset($_POST['reg'])) {
		$user_newpasswd = isset($_POST['user_newpasswd']) ? $_POST['user_newpasswd'] : '';
		$user_email = isset($_POST['user_email']) ? $_POST['user_email'] : '';
		$err = ($username && tilim_check_username($username) ? 0 : 0x1); // $err & 0x1 = invalid username
		$err += ($user_newpasswd && $user_password == md5($user_newpasswd) ? 0 : 0x2); // $err & 0x2 = Passwords are not identical
		$err += ($user_email && preg_match("/^[\w\-\.]+\@[\w\-\.]+\.[\w\-]+$/i", $user_email) == 1 && $user_email == addslashes($user_email) ? 0 : 0x4); //$err & 0x4 = invalid email
		if ($err) {
			tilim_err_msgs($err);
			return false;
		}
		$sql = "SELECT `username`, `user_email` FROM `tilim_users` WHERE `username`='$username' OR `user_email`='$user_email';";
		$query = @mysql_query($sql);
		while ($row = @mysql_fetch_array($query)) {
			$err += ($row['username'] == $username ? 0x8 : 0); // $err & 0x8 = exist username
			$err += ($row['user_email'] == $user_email ? 0x10 : 0); // $err & 0x10 = exist email
		}
		if ($err) {
			tilim_err_msgs($err);
			return false;
		}
		$sql = "INSERT INTO `tilim_users` (`username`, `user_password`, `user_email`) VALUES ('$username', '$user_password', '$user_email');";
		$query = @mysql_query($sql);
		$err += @mysql_affected_rows() > 0 ? 0 : 0x20; // $err & 0x20 = unknown error
		if ($err) {
			tilim_err_msgs($err);
			return false;
		}
	}
	if ($username) {
		if (isset($_POST['forget_pswd'])) {
			if (!tilim_check_username($username)) {
				tilim_err_msgs(0x100); // $err & 0x100 = username does not exist
				return false;
			}
			$sql = "SELECT * FROM `tilim_users` WHERE `username`='$username';";
			$query = @mysql_query($sql);
			$row = @mysql_fetch_array($query);
			if (!$row) {
				tilim_err_msgs(0x100); // $err & 0x100 = username does not exist
				return false;
			}
			$user_newpasswd = sprintf('%08xqwop%s', unixtojd(), substr(my_md5(time() . rand() . $row['user_newpasswd']), -20));
			$sql = "UPDATE `tilim_users` WHERE `user_id`='$row[user_id]';";
			$query = @mysql_query($sql);
			if (@mysql_affected_rows() != 1) {
				tilim_err_msgs(0x20);
				return false;
			}


			return false;
		}
		$sql = "SELECT * FROM `tilim_users` WHERE `user_password`='$user_password';";
		$query = @mysql_query($sql);
		while (!$tilim_user && $row = @mysql_fetch_array($query)) if ($row['username'] == $username) $tilim_user = $row;
	} elseif (isset($_COOKIE['tilimuser'])) {
		list($id, $user_password) = preg_split('/-/', $_COOKIE['tilimuser'] . '-');
		$sql = sprintf('SELECT * FROM tilim_users WHERE `user_id`=%s;', intval($id));
		$query = @mysql_query($sql);
		$row = @mysql_fetch_array($query);
		if (my_md5($row['user_password']) == $user_password) $tilim_user = $row;
	}
	if ($tilim_user) {
		setcookie('tilimuser', "$tilim_user[user_id]-" . my_md5($tilim_user['user_password']), time() + 30 * 24 * 60 * 60);
		return true;
	}
	if ($username) tilim_err_msgs(0x40);
	return false;
}
function tilim_logout() {
	global $tilim_user;
	setcookie('tilimuser', '', 1);
}
function tilim_caps() {
	global $tilim_thetexts, $tilim_user, $tilim_nn;
	$sql = "SELECT * FROM `tilim_caps` WHERE `user_id`='$tilim_user[user_id]' order by `order` ASC;";
	$query = @mysql_query($sql);
	$counter = 0;
	$caps = array();
	while ($row = @mysql_fetch_array($query)) {
		$cap = tilim_select($row['cap']);
		$cap[(intval($row['cap']) > 0) ? 'id' : 'learning'] = $row['id'];
		$cap['name'] = $row['name'];
		$cap['counter'] = $counter++;
		$caps[] = $tilim_nn ? remove_nikud($cap) : $cap;
	}
	return $caps;
}
function tilim_check_username($username) {
	return $username == preg_replace('/[^\d\s\wא-ת]/i', '', $username);
}
function tilim_err_msgs($err, $ret = false) {
	global $tilim_thetexts, $tilim_errors;
	$error = array();
	if ($err & 0x1) $error[] = $tilim_thetexts['invalid_username'];
	if ($err & 0x2) $error[] = $tilim_thetexts['passwords_not_identical'];
	if ($err & 0x4) $error[] = $tilim_thetexts['invalid_email'];
	if ($err & 0x8) $error[] = $tilim_thetexts['exist_username'];
	if ($err & 0x10) $error[] = $tilim_thetexts['exist_email'];
	if ($err & 0x20) $error[] = $tilim_thetexts['unknown_error'];
	if ($err & 0x40) $error[] = $tilim_thetexts['invalid_login'];
	if ($err & 0x80) $error[] = $tilim_thetexts['error_in_sql'];
	if ($err & 0x100) $error[] = $tilim_thetexts['username_not_exist'];
	if ($ret) {
		return isset($error[0]) ? isset($error[1]) ? $error : $error[0] : null;
	} else {
		foreach ($error as $value) $tilim_errors[] = $value;
	}
}

header("Cache-Control: no-cache");
header("Expires: -1");
$tilim_thetexts = tilim_thetexts(isset($lang) ? $lang : null);
$tilim_errors = array();
$tilim_caps = array();
$tilim_forms = array();
$tilim_date_change = isset($_GET['plusdate']) ? $_GET['plusdate'] : 0;
if (isset($_GET['tilim_nn']) && $_GET['tilim_nn']) {
	$tilim_nn = true;
	$tilim_thetexts['invert_nn'] = preg_replace('/tilim_nn[=\d]*&/', '', $tilim_thetexts['PHP_SELF'] . '&');
} else {
	$tilim_nn = false;
	$tilim_thetexts['invert_nn'] = $tilim_thetexts['PHP_SELF'] . '&amp;tilim_nn=1';
}


if (!tilim_install()) {
	tilim_err_msgs(0x80); // error_in_sql
} else if (!isset($_GET['logout']) && tilim_login()) {
	$tilim_forms[] = tilim_form('add_cap');
	foreach (array('del', 'edit', 'moveup') as $value) if (isset($_GET[$value . 'cap'])) {
		tilim_err_msgs(call_user_func('tilim_' . $value));
		break;
	}
	foreach (array('weekly','monthly-elul', 'monthly', 'add_cap', 'edit_cap') as $variable) if (isset($_POST[$variable])) tilim_err_msgs(tilim_add());
	$tilim_caps = tilim_caps();
} else {
	tilim_logout();
	$tilim_forms[] = tilim_form('reg');
	$tilim_forms[] = tilim_form('login');
}

require('tilim.tpl.php');
