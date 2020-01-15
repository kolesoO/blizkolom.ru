<?
header('Content-Type: text/html; charset=utf-8');
//error_reporting(0);
$base_url = "/home/temnu/domains/blizkolom.ru/public_html/static/mobile";

$regions = include $base_url."/regions.data.php";
$str = $_POST['reg'];
$result = '';
foreach ($regions as $region) {
	if ($region['type']=='city') {
		if ($str && mb_stripos($region['name'], $str, 0, 'UTF-8') !== false) {
			$result .= "<div class='reg-load' data-reg=\"".$region['url']."\">".preg_replace("/$str/iu", '<b>$0</b>', $region['name']).", ".$region['reg']."</div>";
		}
	};
};
foreach ($regions as $region) {
	if ($region['type']=='obl') {
		if ($str && mb_stripos($region['name'], $str, 0, 'UTF-8') !== false) {
			$result .= "<div class='reg-load' data-reg=\"".$region['url']."\">".preg_replace("/$str/iu", '<b>$0</b>', $region['name'])."</div>";
		}
	};
};
echo $result;
?>