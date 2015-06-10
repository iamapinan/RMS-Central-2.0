<?php


function checkAmKey($key){
	try{
		if(strlen($key)==0){
			return false;
		}else{
			$s = "";
			for($i=0;$i<strlen($key);$i++){
				if($i % 2 == 1)
				 $s .= substr($key,$i,1);
			}
			
			$s = substr($s,0,strlen($key)/4);
			$s = strrev($s);

			$d = time() - $s;

			if($d<60){
				return true;
			}else{
				return false;
			}
		}
	}catch(Exception $e){
		return false;
	}
}

function generateAmKey(){

	$str = time();
	$str = strrev($str);
	$rnd = randomkeys(3 * strlen($str));
	$rnd = strrev($rnd);

	$s = "";

	for($i=0;$i<strlen($rnd);$i++){
		 $s .= substr($rnd,$i,1);
		 if($i<strlen($str)){
			$s .= substr($str,$i,1);
		 }
	}
	
	return $s;
}


function randomkeys($length){
	 $pattern='1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
	 $key = "";
	 for($i=0;$i<$length;$i++)
	 {
	   $key .= $pattern{mt_rand(0,35)};
	 }
	 return $key;
}


function aculearn_post($url, $data){

	//$header = "Content-type: text/xml";//定义content-type为xml
	$ch = curl_init(); //初始化curl
	curl_setopt($ch, CURLOPT_URL, $url);//设置链接
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
	//curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//设置HTTP头
	curl_setopt($ch, CURLOPT_POST, 1);//设置为POST方式
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//POST数据
	$response = curl_exec($ch);//接收返回信息
	if(curl_errno($ch)){//出错则显示错误信息
		$response =  curl_error($ch);
	}
	curl_close($ch); //关闭curl链接

	return $response;
}


function aculearn_get($url){
	$ch = curl_init($url) ;  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回  
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
	$response = curl_exec($ch);  
	return $response;
}







?>