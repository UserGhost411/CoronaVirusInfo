 <?php 
 error_reporting(0);
 //1$data = json_decode(file_get_contents("../db.json"),true);
 $dataall = json_decode(file_get_contents("../alldb.json"),true);
 //$timestampx = $data['updated'];
 //$timenya = date("H:i d-m-Y",$timestampx);
 //$ago =time_elapsed_string("@".$timestampx);
 function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
$mostdeath=0;
$mostdeathcountry="";
$mostinfect=0;
$mostinfectcountry="";

		$negara;
		$inf;
		$die;
		
		foreach($dataall as $k => $v){
		if(!$k){continue;}
		
		foreach($v as $k1 => $v1){
		if(!$k1){continue;}	
		if($v1['country']==str_replace("_"," ",$_GET['id'])){
		$negara['infect'] = $v1['infect'];
		$negara['death'] = $v1['death'];
		$datax[0][]=date("H:i d/m/Y",$k);
		$datax[1][]=$v1['infect'];
		$datax[2][]=$v1['death'];
		}
		}
		}
		echo json_encode($datax);
?>