<?php
require_once ('/jpgraph/jpgraph.php');
require_once ('/jpgraph/jpgraph_bar.php');

$data0=explode("&",urldecode($_GET["a"]));
$data[0][0]=$data0[0];
$data[0][1]=$data0[1];
$data[0][2]=$data0[2];
$data[0][3]=$data0[3];
$data[1][0]=$data0[4];
$data[1][1]=$data0[5];
$data[1][2]=$data0[6];
$data[1][3]=$data0[7];

// Create the graph. These two calls are always required
$graph = new Graph(450,300,'auto');
$graph->SetScale("textlin");
$graph->SetY2Scale("lin"); // Y2 axis
$graph->SetY2OrderBack(false);
//$graph->SetAxisStyle(AXSTYLE_BOXIN);

$theme_class=new UniversalTheme;
$graph->SetTheme($theme_class);

//$graph->yaxis->SetTickPositions(array(0,30,60,90,120,150), array(15,45,75,105,135));
$graph->SetBox(false);

$graph->ygrid->SetFill(false);
$graph->y2grid->SetFill(false);
$graph->xaxis->SetTickLabels(array('1','2','3','4'));
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);


$graph->y2axis->HideLine(false);
$graph->y2axis->HideTicks(false,false);
$graph->y2axis->title->SetMargin(5);

// Create the bar plots
$b1plot = new BarPlot($data[0]);
$b2plot = new BarPlot($data[1]);
$zeroplot = new BarPlot(array(0));

// Create the grouped bar plot
$gbplot1 = new GroupBarPlot(array($b1plot,$zeroplot));
$gbplot2 = new GroupBarPlot(array($zeroplot,$b2plot));
// ...and add it to the graPH
$graph->Add($gbplot1);
$graph->AddY2($gbplot2);


$b1plot->SetColor("white");
$b1plot->SetFillColor("firebrick");
$b1plot->value->Show();
$b1plot->value->HideZero();
$b1plot->value->SetFormat('%01.1f');
$b1plot->SetLegend('коксовый газ');


$b2plot->SetColor("white");
$b2plot->SetFillColor("indigo");
$b2plot->value->Show();
$b2plot->value->HideZero();
$b2plot->value->SetFormat('%01.1f');
$b2plot->SetLegend('электричество');


$graph->title->Set("Удельный расход энергоресурсов побригадно");
$graph->SetMargin(40,20,60,20);
$graph->legend->SetPos(0.5,0.12,'center','center');

// Display the graph
//$graph->Stroke();
@unlink("image.png");
$graph->Stroke();


// $width=950;
// $height=570;
// $graph = new Graph($width,$height);
// $graph->SetMargin(70,40,10,80);
// $graph->SetScale('datlin');
// $graph->xaxis->SetPos("min");
// $graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
// $graph->yaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
// $graph->yaxis->SetTitle($data['y_title'],'middle');
// $graph->yaxis->SetTitlemargin(50);
// $graph->yaxis->title->SetFont(FF_VERDANA,FS_BOLD,8);
// $graph->xaxis->SetLabelAngle(30);
// $graph->SetTickDensity(TICKD_NORMAL,TICKD_VERYSPARSE);
// $graph->xaxis->scale->SetDateFormat('d.m.Y H:i');
// $graph->xgrid->Show();
// $graph->xaxis->SetLabelAlign('center','top');
// $lineplot=new LinePlot($values, $time);
// $lineplot->SetStepStyle();
// $graph->Add($lineplot);
// $lineplot->SetColor("blue");
//if (file_exists($file)) unlink($file);
//$graph->img->SetImgFormat('jpeg');
//$graph->Stroke();
?>