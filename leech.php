<?php 
error_reporting(0);
if($_GET['pass']!="meong163903474411"){
backup_data();
http_response_code(404);
die("");
}
$data = file_get_contents("https://en.wikipedia.org/wiki/2019–20_Wuhan_coronavirus_outbreak");
$data_proses = get_string_between($data,'<table class="wikitable sortable" style="width:auto; float:right; clear:right; margin:0px 0px 0.5em 1em;">','</table>');
$data_proses = str_replace('class="navbox-abovebelow"',"",$data_proses);
$data_proses = str_replace(' align="right"',"",$data_proses);
$data_proses = str_replace('<span class="nowrap">',"",$data_proses);
$data_proses = str_replace(' align="left"',"",$data_proses);
$data_proses = getContents($data_proses,"<tr>","</tr>");
$datax = [];
$infect = 0;
$die = 0;
$all = [];
foreach($data_proses as $k => $v){
$data_proses2 = getContents($v,"<td>","</td>");
//if(!$data_proses2[0]){continue;}
$infect += intval(str_replace(",","",$data_proses2[1]));
$die += intval(str_replace(",","",$data_proses2[2]));
$dataimgdannama = get_string_between($data_proses2[0],'<span class="flagicon">','&#160;</span>');
$flag = get_string_between($data_proses2[0],'src="','png"');
$nama = str_replace('<span class="flagicon">'.$dataimgdannama,"",$data_proses2[0]);
$nama = str_replace('&#160;</span>',"",$nama);
$nama = str_replace('</span>',"",$nama);
$nama = trim($nama);
$datax['data'][$nama]['flag'] =$flag;
$datax['data'][$nama]['infect'] = intval(str_replace(",","",$data_proses2[1]));
$datax['data'][$nama]['death'] = intval(str_replace(",","",$data_proses2[2]));
$allsub['country'] = $nama;
$allsub['infect'] = intval(str_replace(",","",$data_proses2[1]));
$allsub['death'] = intval(str_replace(",","",$data_proses2[2]));
$all[]=$allsub;
/*
echo "<tr>";
echo "<td>".$data_proses2[0]."</td>";
echo "<td>".$data_proses2[1]."</td>";
echo "<td>".$data_proses2[2]."</td>";
echo "</tr>";
*/
}
$datax['total']['infect'] = $infect;
$datax['total']['death'] = $die;
$datax['updated']= time();
die(save_all_db($all,$datax));
function backup_data(){
$tmp = file_get_contents("db.json");
file_put_contents("backup/db_backup_".date("HdmY").".json",$tmp);

}
function save_all_db($all,$datax){
backup_data();
file_put_contents("db.json",json_encode($datax));
$tmp = json_decode(file_get_contents("alldb.json"),true);
$tmp[time()] = $all;
file_put_contents("alldb.json",json_encode($tmp));
return "ok";
}
function get_string_between($string, $start, $end){
    $string = " " . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) {return "";}
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
  }
    function getContents($str, $startDelimiter, $endDelimiter) {
    $contents = array();
    $startDelimiterLength = strlen($startDelimiter);
    $endDelimiterLength = strlen($endDelimiter);
    $startFrom = $contentStart = $contentEnd = 0;
    while (false !== ($contentStart = strpos($str, $startDelimiter, $startFrom))) {
      $contentStart += $startDelimiterLength;
      $contentEnd = strpos($str, $endDelimiter, $contentStart);
      if (false === $contentEnd) {break;}
      $contents[] = substr($str, $contentStart, $contentEnd - $contentStart);
      $startFrom = $contentEnd + $endDelimiterLength;
    }
  return $contents;
  }
?>