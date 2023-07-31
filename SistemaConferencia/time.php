<?php
session_start();
if(isset($_SESSION['time']))
{
		 $hh=intval($_SESSION['time']/3600);
		 $mm=intval($_SESSION['time']/60);
		 $ss=intval($_SESSION['time']);
		 
		 $diff=$_SESSION['time'];
		 
		 
		$hh = floor($diff / 3600) . ' : ';
		$diff = $diff % 3600;
		$mm = floor($diff / 60) . ' : ';
		$diff = $diff % 60;
		$ss = $diff;
		 
		 
		 
		 echo $hh;
		 echo $mm;
		 echo $ss;
		 
		$_SESSION['time']=$_SESSION['time']+1;
}
?>









