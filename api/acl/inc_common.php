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

	//$header = "Content-type: text/xml";//����content-typeΪxml
	$ch = curl_init(); //��ʼ��curl
	curl_setopt($ch, CURLOPT_URL, $url);//��������
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//�����Ƿ񷵻���Ϣ
	//curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//����HTTPͷ
	curl_setopt($ch, CURLOPT_POST, 1);//����ΪPOST��ʽ
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//POST����
	$response = curl_exec($ch);//���շ�����Ϣ
	if(curl_errno($ch)){//��������ʾ������Ϣ
		$response =  curl_error($ch);
	}
	curl_close($ch); //�ر�curl����

	return $response;
}


function aculearn_get($url){
	$ch = curl_init($url) ;  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // ��ȡ���ݷ���  
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // ������ CURLOPT_RETURNTRANSFER ʱ�򽫻�ȡ���ݷ���  
	$response = curl_exec($ch);  
	return $response;
}







?>