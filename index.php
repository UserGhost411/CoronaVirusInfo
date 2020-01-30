 <?php 
 $data = json_decode(file_get_contents("db.json"),true);
 $dataall = json_decode(file_get_contents("alldb.json"),true);
$lastup = count($dataall)-2;
$datx = [];
$i=0;
foreach($dataall as $k => $v){
if($i==$lastup){
$datx = $v;
}
$i++;
}

 $timestampx = $data['updated'];
 $timenya = date("H:i d-m-Y",$timestampx);
 $ago =time_elapsed_string("@".$timestampx);
 function getlastinfect($country){
	 //print_r($GLOBALS['datx']);
	foreach($GLOBALS['datx'] as $k => $v){
		
		if($v['country']==$country){
		return array($v['infect'],$v['death']);
		}
		//return array(0,0);
	}
 }
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
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>CoronaVirus Databases</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="res/chart.min.css">
  <script src="res/chart.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  <style>
body{
 color:white;
 background: #404040;
}
/* unvisited link */
a:link {
  color: #67c2dc;
}

/* visited link */
a:visited {
  color: #67c2dc;
}

/* mouse over link */
a:hover {
  color: #09FF00;
}

/* selected link */
a:active {
  color: #09FF00;
}
</style>
</head>
<body>

<div class="container" style="margin-top:50px;">
	
  <h1>CoronaVirus Databases</h1>
  <p>Last Update : <?= $timenya." ($ago)"; ?></p>    
<div class="row">

<div class="col-sm-6">
  <h2>Country Data:</h2>
  <table class="table table-dark table-hover">
    <thead>
      <tr>
		<th>#</th>
        <th>Country</th>
        <th>Infected</th>
        <th>Death</th>
      </tr>
    </thead>
    <tbody>
		<?php 
		$negara;
		$inf;
		$die;
		foreach($data['data'] as $k => $v){
		$id = str_replace(" ","_",$k);
		if(!$k){continue;}
		echo "<tr onclick=\"loadchart('$id');\"><td><img width='16px' src='".$v['flag']."png'></td><td>".$k."</td>";
		$infect_dif = abs($v['infect']-getlastinfect($k)[0]);
		$death_dif = abs($v['death']-getlastinfect($k)[1]);
		echo "<td>".$v['infect'];
		if($infect_dif){
		echo " <small data-toggle='tooltip' title='New Infected Confirmed' class='text-danger'>^$infect_dif</small>";
		}
		echo "</td>";
		echo "<td>".$v['death'];
		if($death_dif){
		echo " <small  data-toggle='tooltip' title='New Death Confirmed' class='text-danger'>^$death_dif</small>";
		}
		echo "</td></tr>";
		if($mostdeath<$v['death']){$mostdeath=$v['death'];$mostdeathcountry=$k;}
		if($mostinfect<$v['infect']){$mostinfect=$v['infect'];$mostinfectcountry=$k;}
		$negara[] = $k;
		$inf[] = $v['infect'];
		$die[] = $v['death'];
		
		}
		?>
    </tbody>
  </table>
  </div>
  


  <div class="col-sm-6">
    <h2>Summary:</h2>
  <table class="table table-dark table-hover">
   
    <tbody>
		<tr>
		<td>Total Infected</td>
		<td>:</td>
		<td><?=$data['total']['infect']?></td>
		</tr>
		<tr>
		<td>Total Death</td>
		<td>:</td>
		<td><?=$data['total']['death']?></td>
		</tr>
		<tr>
		<td>Most Death County</td>
		<td>:</td>
		<td><?=$mostdeathcountry." ( $mostdeath death) "?></td>
		</tr>
		<tr>
		<td>Most Infected County</td>
		<td>:</td>
		<td><?=$mostinfectcountry." ( $mostinfect Infected) "?></td>
		</tr>
		<tr>
		<td>Disease Name</td>
		<td>:</td>
		<td><a href="https://en.wikipedia.org/wiki/Coronavirus" target="_blank">CoronaVirus</a></td>
		</tr>
		<tr>
		<td>Disease Type</td>
		<td>:</td>
		<td><a href="https://en.wikipedia.org/wiki/Virus" target="_blank">Virus</a></td>
		</tr>
		<tr>
		<td>Origin</td>
		<td>:</td>
		<td><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Flag_of_the_People%27s_Republic_of_China.svg/23px-Flag_of_the_People%27s_Republic_of_China.svg.png" width="16px"><a href="https://en.wikipedia.org/wiki/Wuhan" target="_blank" > Wuhan , China</a></td>
		</tr>
    </tbody>
  </table>
  <canvas id="myChart" height="250"></canvas>
  </div>
  </div>
  <center style="margin:20px">Statistic By <a href="http://www.userghost411.cf" target="_blank">UserGhost411</a> , Powered By <a target="_blank" href="https://www.wikipedia.org/">WIKIPEDIA</a>
  </center>
</div>
<div class="modal fade" id="myModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header" style="background:#454d55">
        <h4 class="modal-title">Data Library</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body" id="meong" style="background:#404040">
       
      </div>

      <!-- Modal footer -->
      <div class="modal-footer"  style="background:#454d55">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<script>
var ctx = document.getElementById('myChart');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($negara) ?>,
        datasets: [{
            label: '# Infected',
            data: <?= json_encode($inf) ?>,
			backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        },{
            label: '# Death',
            data: <?= json_encode($die) ?>,
			backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }]
    },
	type: 'horizontalBar',
    options: {
		legend: {
            labels: {
                // This more specific font property overrides the global property
                fontColor: 'white'
            }
        },
        scales: {
            yAxes: [{
                ticks: {
					fontColor: "white",
                    beginAtZero: true
                }
            }]
        }
    }
});
function loadchart(id){
//alert(id);
$("#myModal").modal('show')
document.getElementById('meong').innerHTML=' <canvas id="salesChart" style="height: 180px;"></canvas>';
var ctx = document.getElementById('salesChart');

 var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
	//alert(this.status);
	//alert(this.responseText);
    if (this.readyState == 4 && this.status == 200) {
	var sddd = JSON.parse(this.responseText.trim());
	var labex = sddd[0];
	var datax1 = sddd[1];
	var datax2 = sddd[2];
	var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labex,
        datasets: [{
            label: '# Infected',
            data: datax1,
			backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        },{
            label: '# Death',
            data: datax2,
			backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
    }

  };
  xhttp.open("GET", "chart/"+id, true);
  //xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("");
  
}
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});
</script>
</body>
</html>
