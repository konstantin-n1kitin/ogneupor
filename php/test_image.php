<?php
require_once ('/jpgraph/jpgraph.php');
require_once ('/jpgraph/jpgraph_line.php');
require_once ('/jpgraph/jpgraph_date.php');
			
$values=array(10,20,30,40,50);
$time=array(1,2,3,4,5);

$_GET["par1"];
$_GET["par2"];

$width=950;
$height=605;
$graph = new Graph($width,$height);
$graph->SetScale('datlin');
//$graph->yaxis->SetTitle($_GET["y_title"],'middle');
$lineplot=new LinePlot($values, $time);
$graph->Add($lineplot);
$graph->Stroke();
?>