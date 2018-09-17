<?php
	
	global $secret_code;
	
	
 
	$secret_code = "ML6%9t{6W^$2dsaR3/mY9F@";
	
	$get_arr = filter_input_array(INPUT_GET);
	$get_secret_code = isset($get_arr['sc']) ? $get_arr['sc'] : '';
	
	if ($secret_code === $get_secret_code && file_exists('logs.html'))
		include_once 'logs.html';
	else
		echo 'Sorry!';