<?php
session_start();
if(isset($_SESSION['time']))
{
		 $hh=intval($_SESSION['time']/3600);
		 $mm=intval($_SESSION['time']/60);
		 $ss=intval($_SESSION['time']);
		 
		 $diff=$_SESSION['time'];
		 
		if($hh < 10){
			$hh = '0' .floor($diff / 3600) . ' : ' ;
		}else{
			$hh = floor($diff / 3600) . ' : ' ;
		}

		
		$diff = $diff % 3600;

		if(floor($diff / 60) < 10){
			$mm = '0' .floor($diff / 60) . ' : ' ;
		}else{
			$mm = floor($diff / 60) . ' : ' ;
		}

		$diff = $diff % 60;

		if(floor($diff) < 10){
			$ss = '0' .floor($diff) ;
		}else{
			$ss = floor($diff);
		}

		 
		
		echo $hh;
		 echo $mm;
		 echo $ss;
		 
		$_SESSION['time']=$_SESSION['time']+1;
}
?>









