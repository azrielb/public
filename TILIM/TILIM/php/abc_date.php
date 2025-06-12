<?php
if (!class_exists('abc_date')):
class abc_date {
	protected $hebdate;
	protected $gredate;
	protected $JD;
	protected $dow;
	protected $meubar;
	protected $type;
	//	protected $gauss;
	//	protected $gauss_;
	protected $march;

	public function __construct($date = null, $gre = false) {
		if ($gre || !$this->setheb($date)) $this->setgre($this->is_gre($date) ? $date : date('m-d-Y'));
	}

	public function setheb($heb = null) {
		$heb = $this->is_heb($heb);
		if (!$heb) return null;
		list($this->hebdate['month'], $this->hebdate['day'], $this->hebdate['year']) = preg_split('#/#', $this->hebdate['date'] = $heb);
		list($this->gredate['month'], $this->gredate['day'], $this->gredate['year']) = preg_split('#/#', $this->gredate['date'] = $this->hebtogre($heb, true));
		return true;
	}

	public function setgre($gre = null) {
		$gre = $this->is_gre($gre);
		if (!$gre) return null;
		list($this->gredate['month'], $this->gredate['day'], $this->gredate['year']) = preg_split('#/#', $this->gredate['date'] = $gre);
		return $this->gretoheb();
	}

	public function is_heb($date) {
		if (!is_array($date)) $date = preg_split('#(/|-)#' , "$date/0/0");
		else extract($date, EXTR_SKIP);
		if (!isset($month) || !isset($day) || !isset($year)) list($month, $day, $year) = $date;
		$date = $month . '/' . $day . '/' . $year;
		return $date == jdtojewish(jewishtojd($month, $day, $year))? $date : false;
	}

	public function is_gre($date) {
		if (!is_array($date)) $date = preg_split('#(/|-)#' , "$date/0/0");
		else extract($date, EXTR_SKIP);
		if (!isset($month) || !isset($day) || !isset($year)) list($month, $day, $year) = $date;
		$date = $month . '/' . $day . '/' . $year;
		return $date == jdtogregorian(gregoriantojd($month, $day, $year))? $date : false;
	}

	public function is_meubar($year) {
		return (12 * $year + 17) % 19 > 11;
	}

	public function gauss($hebyear) {
		$greyear = $hebyear - 3760;
		$ibur = (12 * $hebyear + 17) % 19;
		$feb = $hebyear % 4;
		$molad = 32.0441 + 1.55424 * $ibur + 0.25 * $feb - 0.00317779 * $hebyear;
		$molad -= $march_day = intval($molad);
		$pesach_dow = ($march_day + 3 * $hebyear + 5 * $feb + 5) % 7;
		switch ($pesach_dow) {
			case 0:
				if ($ibur > 11 && $molad > 0.8977) {
					++$pesach_dow;
					++$march_day;
				}
				break;
			case 1:
				if ($ibur > 6 && $molad > 0.6329) {
					$pesach_dow += 2;
					$march_day += 2;
				}
				break;
			case 2:
			case 4:
			case 6:
				++$pesach_dow;
				++$march_day;
				break;
		}
		$march_day += intval(intval($greyear / 100) * .75 - 1.25) - ($greyear < 200);
		$month = $march_day > 31 ? $march_day > 61 ? 5 : 4 : 3;
		$day = $march_day > 31 ? $march_day > 61 ? $march_day - 61 : $march_day - 31 : $march_day;
		return compact('hebyear', 'greyear', 'pesach_dow', 'march_day', 'month', 'day');
	}

	public function gretoheb($gre = null) {
		$gre_cls = is_null($gre) ? $this : new self($gre, true);
		$gre_cls->year_type('gre');
		$gre_cls->march = $gre_cls->gretomarch();
		$gre_cls->nissan = $gre_cls->gauss['march_day'] - 15 - $march;
		//TODO: what is this??????????????????
		$gre_cls->hebdate['date'] = $gre_cls;
		if (is_null($gre)) return $gre_cls->hebdate['date'];
		list($this->hebdate['month'], $this->hebdate['day'], $this->hebdate['year']) = preg_split('#/#', $this->hebdate['date']);
		return true;
	}

	public function gretomarch($gre = null) {
		if (is_null($gre)) $gre = $this->gredate['date'];
		if (!is_array($gre)) $gre = preg_split('#(/|-)#' , "$gre/0/0");
		list($month, $day, $year) = $gre;
		return $month > 2
		? $day + intval(($month - 3) * 30.5 + .5 + ($month > 8) * .5)
		: $day - (28 + (($year % 4 == 0) && ($year % 100 || !($year % 400))) + ($month > 1 ? 0 : 31));
	}

	public function marchtogre($march_day, $year = null) {
		if (is_null($year)) $year = $this->gredate['year'];
		if ($march_day > 306) {
			++$year;
			$next = (($year % 4 == 0) && ($year % 100 || !($year % 400))) ? 366 : 365;
			if ($march_day > $next) return self::marchtogre($march_day - $next, $year);
			$month = $march_day > 337 ? -1 : -2;
			$day = $march_day - ($march_day > 337 ? 337 : 306);
		} elseif ($march_day > 0) {
			$march_day -= ($march_day > 170 ? 1.45 : .55);
			$month = intval($march_day / 30.5);
			$day = intval($march_day - $month * 30.5) + 1;
		} else {
			$march_day += (($year % 4 == 0) && ($year % 100 || !($year % 400)));
			if ($march_day < -59) return self::marchtogre($march_day + 365, $year - 1);
			$month = $march_day > -28 ? -1 : -2;
			$day = $march_day + ($march_day > -28 ? 28 : 59);
		}
		return ($month + 3) . '/' . $day . '/' . $year;
	}

	public function hebtonissan($heb = null) {
		if (is_null($heb)) $heb = $this->hebdate['date'];
		if (!is_array($heb)) $heb = preg_split('#(/|-)#' , "$heb/0/0");
		list($month, $day, $year) = $heb;
		if ($month < 4) {
			if (($type = substr(self::year_type($year),1,1)) == '-') ++$day;
			if ($month < 3 && $type == '+') --$day;
		}
		return $month > 7
		? $day + intval(($month - 8) * 29.5 + .5)
		: $day - (29 + ((self::is_meubar($year) && $month < 7) ? 30 : 0) + (0 < $month && $month < 6 ? intval((6 - $month) * 29.5 + .5) : 0));
	}

	public function nissantoheb($nissan_day, $year = null) {
		if (is_null($year)) $year = $this->hebdate['year'];
		if ($nissan_day > 177) {
			++$year;
			if ($nissan_day > 236) return self::nissantoheb($nissan_day + self::hebtonissan("1/1/$year") - 178, $year);
			$month = $nissan_day > 207 ? -5 : -6;
			$day = $nissan_day - ($nissan_day > 207 ? 207 : 177);
		} elseif ($nissan_day > 0) {
			$month = intval(($nissan_day - .5) / 29.5);
			$day = intval($nissan_day - $month * 29.5);
		} else {
			$is_meubar = self::is_meubar($year);
			if ($nissan_day > -29) {
				$month = -1;
				$day = $nissan_day + 29;
			} elseif ($nissan_day > -59 && $is_meubar) {
				$month = -2;
				$day = $nissan_day + 59;
			} else {
				if ($is_meubar) $nissan_day += 30;
				if ($nissan_day < -87 && ($type = substr(self::year_type($year),1,1)) == '-') --$nissan_day;
				if ($nissan_day < -117 && $type == '+') ++$nissan_day;
				if ($nissan_day < -176) return self::nissantoheb($nissan_day + 354, $year - 1);
				$nissan_day += 177;
				$month = intval(($nissan_day - .5) / 29.5);
				$day = intval($nissan_day - $month * 29.5);
				$month -= 7;
			}
		}
		return ($month + 8) . '/' . $day . '/' . $year;
	}

	public function types($type) {
		switch (str_replace('0', '7', strtolower($type))) {
			case 'k2lp': case '1p': case '2-3': case '2p3': return '2-3';
			case 'k2ap': case '2p': case '2+5': case '2p5': return '2+5';
			case 'k2lm': case '1m': case '2-5': case '2m5': return '2-5';
			case 'k2am': case '2m': case '2+7': case '2m7': return '2+7';
			case 'k3rp': case '3p': case '3=5': case '3p5': return '3=5';
			case 'k3rm': case '3m': case '3=7': case '3m7': return '3=7';
			case 'k5rp': case '4p': case '5=7': case '5p7': return '5=7';
			case 'k5ap': case '5p': case '5+1': case '5p1': return '5+1';
			case 'k5lm': case '4m': case '5-1': case '5m1': return '5-1';
			case 'k5am': case '5m': case '5+3': case '5m3': return '5+3';
			case 'k7lp': case '6p': case '7-1': case '7p1': return '7-1';
			case 'k7ap': case '7p': case '7+3': case '7p3': return '7+3';
			case 'k7lm': case '6m': case '7-3': case '7m3': return '7-3';
			case 'k7am': case '7m': case '7+5': case '7m5': return '7+5';
		}
		return $type;
	}

	public function year_type($year = null) {
		if ($year === 'gre') {
			$year = $this->gredate['year'] + 3760;
		} elseif (is_null($year)) {
			$year = $this->hebdate['year'];
		} else {
			$yearG = self::gauss($year, $year + 1);
			$year1G = self::gauss($year - 1, $year);
			$type = self::types((($year1G['pesach_dow'] + 2) % 7) . (self::is_meubar($year) ? 'm' : 'p') . ($yearG['pesach_dow']));
			{
				$type_by_molad = self::types(self::RH_by_molad($year). (self::is_meubar($year) ? 'm' : 'p') . ((self::RH_by_molad($year + 1) + 5) % 7));
				if ($type != $type_by_molad) echo "<p>$year: $type != $type_by_molad</p>";
			}
			return $type;
		}
		$this->gauss = $this->gauss($year);
		$this->gauss_ = $this->gauss($year - 1);
		return $this->type = $this->types((($this->gauss_['pesach_dow'] + 2) % 7) . (($this->meubar = $this->is_meubar($year)) ? 'm' : 'p') . ($this->gauss['pesach_dow']));
	}

	public function RH_by_molad($year) {
		$molad = 9433 * $year / 2160 - 2323 / 1080 + intval((7 * $year - 6) / 19) * 39673 / 25920;
		$molad -= $rosh_hashana = intval($molad);
		$rosh_hashana %= 7;
		if ($molad >= 0.75
		|| ($rosh_hashana == 3 && $molad >= 827 / 2160 && !self::is_meubar($year))
		|| ($rosh_hashana == 2 && $molad >= 16789 / 25920 && self::is_meubar($year - 1))) ++$rosh_hashana;
		if (in_array($rosh_hashana, array(1,4,6))) ++$rosh_hashana;
		return $rosh_hashana % 7;
	}

	public function us_from_il($num, $type = null) {
		$type = $this->types($type);
		if (!$type) $type = $this->type;
		switch (substr($type, -1)) {
			case '5': if ($num > 39 && $num < 44) --$num; elseif ($num == 44) ++$num; break;
			case '7': if (substr($type, 0, 1) == '5' && $num > 27 && $num < 37) {
				switch ($num) {
					case 30: case 33: $num -= 3; break;
					case 34: case 35: --$num; break;
					case 36: ++$num; break;
				}
			} elseif ($num > 30 && $num < 49) {
				switch ($num) {
					case 31: case 34: case 38: case 46: $num -=2; break;
					case 32: case 35: case 36: case 39: case 40: case 41: case 42: case 43: case 44: case 47: --$num; break;
					case 48: ++$num; break;
				}
			}
			break;
		}
		return $num;
	}

	public function minus_heb($thesmall, $thebig = null) {
		$thebig = $thebig == null ? jdtojewish(unixtojd()) : str_replace('-', '/', $thebig);
		$thesmall = str_replace('-', '/', $thesmall);
		list($small['month'], $small['day'], $small['year']) = preg_split('#/#' , $thesmall);
		list($big['month'], $big['day'], $big['year']) = preg_split('#/#' , $thebig);
		/*		$err = self::is_heb($small['month'], $small['day'], $small['year']) ? '' : "small ($thesmall) is not hebdate\n";
		 if ($thebig != jdtojewish(jewishtojd($big['month'], $big['day'], $big['year']))) $err .= "big ($thebig) is not hebdate\n";
		 if ($err) return null;//*/
		$diff['years'] = $big['year'] - $small['year'];
		$diff['months'] = $big['month'] - $small['month'];
		$diff['days'] = $big['day'] - $small['day'];
		$diff['adar'] = false;
		if ($small['month'] > 5 && $small['month'] < 8 && $big['month'] > 5 && $big['month'] < 8 && self::is_meubar($big['year']) != self::is_meubar($small['year'])) {
			if ($diff['days'] < 0) {
				$diff['years'] -= 1;
				$diff['months'] += 11 + self::is_meubar($big['year'] - 1);
				$diff['days'] += 30;
			} else {
				if (self::is_meubar($small['year']) || $big['month'] > 6) {
					$diff['months'] = 0;
				} else {
					$diff['years'] -= 1;
					$diff['months'] += 12;
				}
			}
			if ($diff['months'] == 12) {
				$diff['adar'] = true;
			}
			return $diff;
		}
		if ($diff['days'] < 0) {
			$diff['months'] -= 1;
			$diff['days'] += cal_days_in_month(2, $big['month'] > 1 ? $big['month'] - 1 : 13, $big['month'] > 0 ? $big['year'] : $big['year']-1);
		}
		if ($diff['months'] < 0) {
			$diff['years'] -= 1;
			$diff['months'] += 12 + self::is_meubar($big['year'] - ($big['month'] < 7));
		} elseif ($big['month'] > 7 && $small['month'] < 6 && !self::is_meubar($big['year'])) {
			--$diff['months'];
		}
		return $diff;
	}

	public function molad_to_clock($molad) {
		$day = intval($molad) % 7;
		$hour = ($molad - intval($molad)) * 24;
		$chelek = ($hour - intval($hour)) * 1080;
		$hour = intval($hour);
		return "$day-$hour:$chelek";
	}

	public function tkufatTamuzDate($year, $nextMolad = null, $nextRH = null) {
		return jdtojewish(347893 + intval(365.25 * $year));
		
		if (is_null($nextMolad)) $nextMolad = 9433 * ($year + 1) / 2160 - 2323 / 1080 + intval((7 * $year + 1) / 19) * 39673 / 25920;
		if (intval($nextRH) !== $nextRH || 0 > $nextRH || $nextRH > 6) $nextRH = self::RH_by_molad($year + 1);
		$tamuzMolad = $nextMolad - 3 * 39673 / 25920;
		$tamuzMolad -= intval($tamuzMolad / 7) * 7;
		$tamuzRC = ($nextRH + 3) % 7;
		$circles = intval(($year - 1) / 19);
		$circleYears = ($year - 1) % 19;
		$circleMeubarot = intval((7 * $circleYears + 8) / 19);
		$tkufatTamuz = intval($tamuzMolad + 227413/8640 + $circles * 313/5184 + $circleYears * 23507/2160 - $circleMeubarot * 765433/25920) - $tamuzRC - 1;
		if ($tamuzRC < intval($tamuzMolad)) $tkufatTamuz -= 7;
		if ($tkufatTamuz > 30) {
			$tkufatTamuz -= 30;
			if ($tkufatTamuz > 29) {
				$tkufatTamuz -=29;
				$month = 12;
			} else {
				$month = 11;
			}
		} else {
			$month = 10;
		}
//		pre(compact('year', 'nextMolad', 'nextRH', 'tamuzMolad', 'tamuzRC', 'circleYears', 'circleMeubarot', 'tkufatTamuz', 'month'));
		return $month . '/' . $tkufatTamuz . '/' . $year;
	}

}
endif;

if (!defined('ABC_FUNCTIONS')) include_once('functions.php');
if (is_file('abc_date.php') && $_SERVER['SCRIPT_FILENAME'] == __FILE__) {
	function test_abc_date($year) {
		$a = array();
		$year += 3760;
		$a[] = abc_date::tkufatTamuzDate($year);
		
		$text = "\n\n$year => " . abc_date::year_type($year);
//		$text .= "\n" . implode("\t" . ($a[0] == $a[1] ? '=' : '!') . "=\t", $a);
		return $text;
	}
	function the14years() {
		return
		test_abc_date(1993) . //בחג
		test_abc_date(1999) . //בשה
		test_abc_date(2006) . //גכה
		test_abc_date(1998) . //הכז
		test_abc_date(1994) . //השא
		test_abc_date(2001) . //זחא
		test_abc_date(2004) . //זשג
		test_abc_date(1989) . //בחה
		test_abc_date(1992) . //בשז
		test_abc_date(1995) . //גכז
		test_abc_date(2005) . //החא
		test_abc_date(1984) . //השג
		test_abc_date(1997) . //זחג
		test_abc_date(2003) . //זשה
		//*/
		'';
	}
	?>
<html>
<head>
<title>test of abc_date</title>
</head>
<body>
<div><pre>
	<?php
	echo time();
	echo the14years();
	if (false) {
		$test = new abc_date();
		pre($test);
	}
	?>
</pre></div>
</body>
</html>
<?php
}
