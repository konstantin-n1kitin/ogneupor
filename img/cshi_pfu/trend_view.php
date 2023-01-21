<?php // content="text/plain; charset=utf-8"
$data['Database']='PFU';
$data['Tag']='234';
$data['Begintime']='2011-07-20 00:00:00';
$data['Endtime']='2011-07-30 23:59:59';
$data['Width']=1200;
$data['Height']=800;
$data['StepStyle']=true;
$trend= new Model_Trend();
$trend->Make($data);
?>