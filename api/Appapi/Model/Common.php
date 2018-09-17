<?php

class Model_Common extends PhalApi_Model_NotORM {
	/* Redis链接 */
	public function connectionRedis(){
		$REDIS_HOST= DI()->config->get('app.REDIS_HOST');
		$REDIS_AUTH= DI()->config->get('app.REDIS_AUTH');
		$REDIS_PORT= DI()->config->get('app.REDIS_PORT');
		$redis = new Redis();
		$redis -> pconnect($REDIS_HOST,$REDIS_PORT);
		$redis -> auth($REDIS_AUTH);

		return $redis;
	}
	/* 设置缓存 */
	public function setcache($key,$info){
		$config=$this->getConfigPri();
		if($config['cache_switch']!=1){
			return 1;
		}

		DI()->redis->set($key,json_encode($info));
		DI()->redis->setTimeout($key, $config['cache_time']); 

		return 1;
	}	
	/* 设置缓存 可自定义时间*/
	public function setcaches($key,$info,$time){
		DI()->redis->set($key,json_encode($info));
		DI()->redis->setTimeout($key, $time); 
		return 1;
	}
	/* 获取缓存 */
	public function getcache($key){
		$config=$this->getConfigPri();

		if($config['cache_switch']!=1){
			$isexist=false;
		}else{
			$isexist=DI()->redis->Get($key);
		}

		return json_decode($isexist,true);
	}		
	/* 获取缓存 不判断后台设置 */
	public function getcaches($key){

		$isexist=DI()->redis->Get($key);
		
		return json_decode($isexist,true);
	}
	/* 删除缓存 */
	public function delcache($key){
		$isexist=DI()->redis->delete($key);
		return 1;
	}	
	/* 同系统函数 array_column   php版本低于5.5.0 时用  */
	public function array_column2($input, $columnKey, $indexKey = NULL){
		$columnKeyIsNumber = (is_numeric($columnKey)) ? TRUE : FALSE;
		$indexKeyIsNull = (is_null($indexKey)) ? TRUE : FALSE;
		$indexKeyIsNumber = (is_numeric($indexKey)) ? TRUE : FALSE;
		$result = array();
 
		foreach ((array)$input AS $key => $row){ 
			if ($columnKeyIsNumber){
				$tmp = array_slice($row, $columnKey, 1);
				$tmp = (is_array($tmp) && !empty($tmp)) ? current($tmp) : NULL;
			}else{
				$tmp = isset($row[$columnKey]) ? $row[$columnKey] : NULL;
			}
			if (!$indexKeyIsNull){
				if ($indexKeyIsNumber){
					$key = array_slice($row, $indexKey, 1);
					$key = (is_array($key) && ! empty($key)) ? current($key) : NULL;
					$key = is_null($key) ? 0 : $key;
				}else{
					$key = isset($row[$indexKey]) ? $row[$indexKey] : 0;
				}
			}
			$result[$key] = $tmp;
		}
		return $result;
	}
	
	/* 密码检查 */
	public function passcheck($user_pass) {
		$num = preg_match("/^[a-zA-Z]+$/",$user_pass);
		$word = preg_match("/^[0-9]+$/",$user_pass);
		$check = preg_match("/^[a-zA-Z0-9]{6,12}$/",$user_pass);
		if($num || $word ){
			return 2;
		}else if(!$check){
			return 0;
		}		
		return 1;
	}
	
	/* 密码加密 */
	public function setPass($pass){
		$authcode='rCt52pF2cnnKNB3Hkp';
		$pass="###".md5(md5($authcode.$pass));
		return $pass;
	}	
	
	/* 公共配置 */
	public function getConfigPub() {
		$key='getConfigPub';
		$config=$this->getcaches($key);
		$config=false;
		if(!$config){
			$config= DI()->notorm->config
					->select('*')
					->where(" id ='1'")
					->fetchOne();
			if($config['live_time_coin']){
				$config['live_time_coin']=preg_split('/,|，/',$config['live_time_coin']);
			}else{
				$config['live_time_coin']=array();
			}
				
			if($config['login_type']){
				$config['login_type']=preg_split('/,|，/',$config['login_type']);
			}else{
				$config['login_type']=array();
			}
			
			if($config['share_type']){
				$config['share_type']=preg_split('/,|，/',$config['share_type']);
			}else{
				$config['share_type']=array();
			}
			
			if($config['live_type']){
				$live_type=preg_split('/,|，/',$config['live_type']);
				foreach($live_type as $k=>$v){
					$live_type[$k]=preg_split('/;|；/',$v);
				}
				$config['live_type']=$live_type;
			}else{
				$config['live_type']=array();
			}
			$config['privatelive_switch']= '0';
			foreach($config['live_type'] as $k => $t){
				if($t[0] == '6'){
					$config['privatelive_switch'] = '1';
				}
			}
			
			
			$this->setcaches($key,$config,60);
		}
        
		return 	$config;
	}		
	
	/* 私密配置 */
	public function getConfigPri() {
		$key='getConfigPri';
		$config=$this->getcaches($key);
		$config=false;
		if(!$config){
			$config= DI()->notorm->config_private
					->select('*')
					->where(" id ='1'")
					->fetchOne();
	
			$this->setcaches($key,$config,60);
		}
		return 	$config;
	}		
	
	/**
	 * 返回带协议的域名
	 */
	public function get_host(){
		//$host=$_SERVER["HTTP_HOST"];
	//	$protocol=$this->is_ssl()?"https://":"http://";
		//return $protocol.$host;
		$config=$this->getConfigPub();
		return $config['site'];
	}	
	
	/**
	 * 转化数据库保存的文件路径，为可以访问的url
	 */
	public function get_upload_path($file){
		if(strpos($file,"http")===0){
			return $file;
		}else if(strpos($file,"/")===0){
			$filepath= $this->get_host().$file;
			return $filepath;
		}else{
			$space_host= DI()->config->get('app.Qiniu.space_host');
			$filepath=$space_host."/".$file;
			return $filepath;
		}
	}
	
	/* 判断是否关注 */
	public function isAttention($uid,$touid) {
		$isexist=DI()->notorm->users_attention
					->select("*")
					->where('uid=? and touid=?',$uid,$touid)
					->fetchOne();
		if($isexist){
			return  1;
		}else{
			return  0;
		}			 
	}
	/* 是否黑名单 */
	public function isBlack($uid,$touid) {	
		$isexist=DI()->notorm->users_black
				->select("*")
				->where('uid=? and touid=?',$uid,$touid)
				->fetchOne();
		if($isexist){
			return 1;
		}else{
			return 0;					
		}
	}	
	
	/* 判断权限 */
	public function isAdmin($uid,$liveuid) {
		if($uid==$liveuid){
			return 50;
		}
		$isuper=$this->isSuper($uid);
		if($isuper){
			return 60;
		}
		$isexist=DI()->notorm->users_livemanager
					->select("*")
					->where('uid=? and liveuid=?',$uid,$liveuid)
					->fetchOne();
		if($isexist){
			return  40;
		}
		
		return  30;
			
	}	
	/* 判断账号是否超管 */
	public function isSuper($uid){
		$isexist=DI()->notorm->users_super
					->select("*")
					->where('uid=?',$uid)
					->fetchOne();
		if($isexist){
			return 1;
		}			
		return 0;
	}	
	/* 判断token */
	public function checkToken($uid,$token) {
		$userinfo=$this->getCache("token_".$uid);
		if(!$userinfo){
			$userinfo=DI()->notorm->users
						->select('token,expiretime')
						->where('id = ? and user_type="2"', $uid)
						->fetchOne();	
			$this->setCache("token_".$uid,$userinfo);								
		}

		if($userinfo['token']!=$token || $userinfo['expiretime']<time()){
			return 700;				
		}else{
			return 	0;				
		} 		
	}	
	
	/* 用户基本信息 */
	public function getUserInfo($uid) {
		$info=$this->getCache("userinfo_".$uid);
		if(!$info){
			$info=DI()->notorm->users
					->select('id,user_nicename,avatar,coin,avatar_thumb,sex,signature,consumption,votestotal,province,city,birthday,issuper')
					->where('id=? and user_type="2"',$uid)
					->fetchOne();	
			if($info){
				$info['level']=$this->getLevel($info['consumption']);
				$info['level_anchor']=$this->getLevelAnchor($info['votestotal']);
				$info['avatar']=$this->get_upload_path($info['avatar']);
				$info['avatar_thumb']=$this->get_upload_path($info['avatar_thumb']);
				
				$info['vip']=$this->getUserVip($uid);
				$info['liang']=$this->getUserLiang($uid);
				
				$this->setCache("userinfo_".$uid,$info);						
			}					
		}
		return 	$info;		
	}
	
	/* 会员等级 */
	public function getLevel($experience){
		$levelid=1;
		$key='level';
		$level=$this->getCache($key);
		if(!$level){
			$level=DI()->notorm->experlevel
					->select("levelid,level_up")
					->order("level_up asc")
					->fetchAll();
			$this->setCache($key,$level);			 
		}

		foreach($level as $k=>$v){
			if( $v['level_up']>=$experience){
				$levelid=$v['levelid'];
				break;
			}else{
				$level_a = $v['levelid'];
			}
		}
		$levelid = $levelid < $level_a ? $level_a:$levelid;
		return $levelid;
	}
	/* 主播等级 */
	public function getLevelAnchor($experience){
		$levelid=1;
		$key='levelanchor';
		$level=$this->getCache($key);
		if(!$level){
			$level=DI()->notorm->experlevel_anchor
					->select("levelid,level_up")
					->order("level_up asc")
					->fetchAll();
			$this->setCache($key,$level);			 
		}

		foreach($level as $k=>$v){
			if( $v['level_up']>=$experience){
				$levelid=$v['levelid'];
				break;
			}else{
				$level_a = $v['levelid'];
			}
		}
		$levelid = $levelid < $level_a ? $level_a:$levelid;
		return $levelid;
	}
	
	/* 等级区间限额 */
	public function getLevelSection($level){
		$key='experlevel_limit';
		$experlevel_limit=$this->getCache($key);
		if(!$experlevel_limit){
			$experlevel_limit=DI()->notorm->experlevel_limit
						 ->select("withdraw,level_up")
						 ->order("level_up asc")
						 ->fetchAll();
			$this->setCache($key,$experlevel_limit);			 
		}
		
		foreach($experlevel_limit as $k=>$v){
			if($v['level_up']>=$level){
				$withdraw=$v['withdraw'];
				break;
			}
			
		}
		return $withdraw;		 
	}	
	/* 统计 直播 */
	public function getLives($uid) {
		/* 直播中 */
		$count1=DI()->notorm->users_live
				->where('uid=? and islive="1"',$uid)
				->count();
		/* 回放 */
		$count2=DI()->notorm->users_liverecord
					->where('uid=? ',$uid)
					->count();
		return 	$count1+$count2;
	}		
	
	/* 统计 关注 */
	public function getFollows($uid) {
		$count=DI()->notorm->users_attention
				->where('uid=? ',$uid)
				->count();
		return 	$count;
	}			
	
	/* 统计 粉丝 */
	public function getFans($uid) {
		$count=DI()->notorm->users_attention
				->where('touid=? ',$uid)
				->count();
		return 	$count;
	}		
	/**
	*  @desc 根据两点间的经纬度计算距离
	*  @param float $lat 纬度值
	*  @param float $lng 经度值
	*/
	public function getDistance($lat1, $lng1, $lat2, $lng2){
		$earthRadius = 6371000; //近似地球半径 单位 米
		 /*
		   Convert these degrees to radians
		   to work with the formula
		 */

		$lat1 = ($lat1 * pi() ) / 180;
		$lng1 = ($lng1 * pi() ) / 180;

		$lat2 = ($lat2 * pi() ) / 180;
		$lng2 = ($lng2 * pi() ) / 180;


		$calcLongitude = $lng2 - $lng1;
		$calcLatitude = $lat2 - $lat1;
		$stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);  $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
		$calculatedDistance = $earthRadius * $stepTwo;
		
		$distance=$calculatedDistance/1000;
		if($distance<10){
			$rs=round($distance,2);
		}else if($distance > 1000){
			$rs='>1000';
		}else{
			$rs=round($distance);
		}
		return $rs.'km';
	}
	/* 判断账号是否禁用 */
	public function isBan($uid){
		$status=DI()->notorm->users
					->select("user_status")
					->where('id=?',$uid)
					->fetchOne();
		if(!$status || $status['user_status']==0){
			return 0;
		}
		return 1;
	}
	/* 是否认证 */
	public function isAuth($uid){
		$status=DI()->notorm->users_auth
					->select("status")
					->where('uid=?',$uid)
					->fetchOne();
		if($status && $status['status']==1){
			return 1;
		}

		return 0;
	}
	/* 过滤字符 */
	public function filterField($field){
		$configpri=$this->getConfigPri();
		
		$sensitive_field=$configpri['sensitive_field'];
		
		$sensitive=explode(",",$sensitive_field);
		$replace=array();
		$preg=array();
		foreach($sensitive as $k=>$v){
			if($v){
				$re='';
				$num=mb_strlen($v);
				for($i=0;$i<$num;$i++){
					$re.='*';
				}
				$replace[$k]=$re;
				$preg[$k]='/'.$v.'/';
			}else{
				unset($sensitive[$k]);
			}
		}
		
		return preg_replace($preg,$replace,$field);
	}
	/* 时间差计算 */
	public function datetime($time){
		$cha=time()-$time;
		$iz=floor($cha/60);
		$hz=floor($iz/60);
		$dz=floor($hz/24);
		/* 秒 */
		$s=$cha%60;
		/* 分 */
		$i=floor($iz%60);
		/* 时 */
		$h=floor($hz/24);
		/* 天 */
		
		if($cha<60){
			return $cha.'秒前';
		}else if($iz<60){
			return $iz.'分钟前';
		}else if($hz<24){
			return $hz.'小时前';
		}else if($dz<30){
			return $dz.'天前';
		}else{
			return date("Y-m-d",$time);
		}
	}		
	/* 时长格式化 */
	public function getSeconds($cha){		 
		$iz=floor($cha/60);
		$hz=floor($iz/60);
		$dz=floor($hz/24);
		/* 秒 */
		$s=$cha%60;
		/* 分 */
		$i=floor($iz%60);
		/* 时 */
		$h=floor($hz/24);
		/* 天 */
		
		if($cha<60){
			return $cha.'秒';
		}else if($iz<60){
			return $iz.'分'.$s.'秒';
		}else if($hz<24){
			return $hz.'小时'.$i.'分'.$s.'秒';
		}else if($dz<30){
			return $dz.'天'.$h.'小时'.$i.'分'.$s.'秒';
		}
	}	
	
	/* 数字格式化 */
	public function NumberFormat($num){
		if($num<10000){

		}else if($num<1000000){
			$num=round($num/10000,2).'万';
		}else if($num<100000000){
			$num=round($num/10000,1).'万';
		}else if($num<10000000000){
			$num=round($num/100000000,2).'亿';
		}else{
			$num=round($num/100000000,1).'亿';
		}
		return $num;
	}

	/**
	*  @desc 获取推拉流地址
	*  @param string $host 协议，如:http、rtmp
	*  @param string $stream 流名,如有则包含 .flv、.m3u8
	*  @param int $type 类型，0表示播流，1表示推流
	*/
	public function PrivateKeyA($host,$stream,$type){
		$configpri=$this->getConfigPri();
		$cdn_switch=$configpri['cdn_switch'];
		//$cdn_switch=3;
		switch($cdn_switch){
			case '1':
				$url=$this->PrivateKey_ali($host,$stream,$type);
				break;
			case '2':
				$url=$this->PrivateKey_tx($host,$stream,$type);
				break;
			case '3':
				$url=$this->PrivateKey_qn($host,$stream,$type);
				break;
			case '4':
				$url=$this->PrivateKey_ws($host,$stream,$type);
				break;
			case '5':
				$url=$this->PrivateKey_wy($host,$stream,$type);
				break;
			case '6':
				$url=$this->PrivateKey_ady($host,$stream,$type);
				break;
		}

		
		return $url;
	}
	
	/**
	*  @desc 阿里云直播A类鉴权
	*  @param string $host 协议，如:http、rtmp
	*  @param string $stream 流名,如有则包含 .flv、.m3u8
	*  @param int $type 类型，0表示播流，1表示推流
	*/
	public function PrivateKey_ali($host,$stream,$type){
		$configpri=$this->getConfigPri();
		$key=$configpri['auth_key'];
		if($type==1){
			$domain=$host.'://'.$configpri['push_url'];
			$time=time() +60*60*10;
		}else{
			$domain=$host.'://'.$configpri['pull_url'];
			$time=time() - 60*30 + $configpri['auth_length'];
		}
		
		$filename="/5showcam/".$stream;
		if($key!=''){
			$sstring = $filename."-".$time."-0-0-".$key;
			$md5=md5($sstring);
			$auth_key="auth_key=".$time."-0-0-".$md5;
		}
		if($type==1){
			if($auth_key){
				$auth_key='&'.$auth_key;
			}
			$url=$domain.$filename.'?vhost='.$configpri['pull_url'].$auth_key;
		}else{
			if($auth_key){
				$auth_key='?'.$auth_key;
			}
			$url=$domain.$filename.$auth_key;
		}
		
		return $url;
	}
	
	/**
	*  @desc 腾讯云推拉流地址
	*  @param string $host 协议，如:http、rtmp
	*  @param string $stream 流名,如有则包含 .flv、.m3u8
	*  @param int $type 类型，0表示播流，1表示推流
	*/
	public function PrivateKey_tx($host,$stream,$type){
		$configpri=$this->getConfigPri();
		$bizid=$configpri['tx_bizid'];
		$push_url_key=$configpri['tx_push_key'];
		
		$stream_a=explode('.',$stream);
		$streamKey = $stream_a[0];
		$ext = $stream_a[1];
		
		$live_code = $bizid . "_" .$streamKey;      	
		$now_time = time() + 3*60*60;
		$txTime = dechex($now_time);

		$txSecret = md5($push_url_key . $live_code . $txTime);
		$safe_url = "&txSecret=" .$txSecret."&txTime=" .$txTime;		

		if($type==1){
			//$push_url = "rtmp://" . $bizid . ".livepush2.myqcloud.com/live/" .  $live_code . "?bizid=" . $bizid . "&record=flv" .$safe_url;	可录像
			$url = "rtmp://" . $bizid .".livepush2.myqcloud.com/live/" . $live_code . "?bizid=" . $bizid . "" .$safe_url;	
		}else{
			$url = 'http://'. $bizid .".liveplay.myqcloud.com/live/" . $live_code . ".flv";
		}
		
		return $url;
	}

	/**
	*  @desc 七牛云直播
	*  @param string $host 协议，如:http、rtmp
	*  @param string $stream 流名,如有则包含 .flv、.m3u8
	*  @param int $type 类型，0表示播流，1表示推流
	*/
	public function PrivateKey_qn($host,$stream,$type){
		
		$configpri=$this->getConfigPri();
		$ak=$configpri['qn_ak'];
		$sk=$configpri['qn_sk'];
		$hubName=$configpri['qn_hname'];
		$push=$configpri['qn_push'];
		$pull=$configpri['qn_pull'];
		$stream_a=explode('.',$stream);
		$streamKey = $stream_a[0];
		$ext = $stream_a[1];

		if($type==1){
			$time=time() +60*60*10;
			//RTMP 推流地址
			$url = \Qiniu\Pili\RTMPPublishURL($push, $hubName, $streamKey, $time, $ak, $sk);
		}else{
			if($ext=='flv'){
				$pull=str_replace('pili-live-rtmp','pili-live-hdl',$pull);
				//HDL 直播地址
				$url = \Qiniu\Pili\HDLPlayURL($pull, $hubName, $streamKey);
			}else if($ext=='m3u8'){
				$pull=str_replace('pili-live-rtmp','pili-live-hls',$pull);
				//HLS 直播地址
				$url = \Qiniu\Pili\HLSPlayURL($pull, $hubName, $streamKey);
			}else{
				//RTMP 直播放址
				$url = \Qiniu\Pili\RTMPPlayURL($pull, $hubName, $streamKey);
			}
		}
				
		return $url;
	}
	
	/**
	*  @desc 网宿推拉流
	*  @param string $host 协议，如:http、rtmp
	*  @param string $stream 流名,如有则包含 .flv、.m3u8
	*  @param int $type 类型，0表示播流，1表示推流
	*/
	public function PrivateKey_ws($host,$stream,$type){
		$configpri=$this->getConfigPri();
		if($type==1){
			$domain=$host.'://'.$configpri['ws_push'];
			//$time=time() +60*60*10;
		}else{
			$domain=$host.'://'.$configpri['ws_pull'];
			//$time=time() - 60*30 + $configpri['auth_length'];
		}
		
		$filename="/".$configpri['ws_apn']."/".$stream;

		$url=$domain.$filename;
		
		return $url;
	}
	
	/**网易cdn获取拉流地址**/
	public function PrivateKey_wy($host,$stream,$type)
	{
		$configpri=$this->getConfigPri();
		$info=DI()->notorm->users_live
				->select("uid,stream,pull,wy_cid")
				->where('stream=?',$stream)
				->fetchOne();
		$url=$info['pull'];
		$cid=$info['wy_cid'];
		$appkey=$configpri['wy_appkey'];
		$appSecret=$configpri['wy_appsecret'];
		$nonce =rand(1000,9999);
		$curTime=time();
		$var=$appSecret.$nonce.$curTime;
		$checkSum=sha1($appSecret.$nonce.$curTime);
		$paramarr = array(
			"cid"  =>$cid,
		);
		$paramarr=json_encode($paramarr);
		$header =array(
			"Content-Type:application/json;charset=utf-8",
			"AppKey:".$appkey,
			"Nonce:" .$nonce,
			"CurTime:".$curTime,
			"CheckSum:".$checkSum,
		);
		$curl=curl_init();
		curl_setopt($curl,CURLOPT_URL, 'https://vcloud.163.com/app/address');
		curl_setopt($curl,CURLOPT_HEADER, 0);
		curl_setopt($curl,CURLOPT_HTTPHEADER, $header); 
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
		curl_setopt($curl,CURLOPT_POST, 1);
		curl_setopt($curl,CURLOPT_POSTFIELDS, $paramarr);
		$data = curl_exec($curl);
		curl_close($curl);
		$url=json_decode($data,1);
		$url=$url['ret']["rtmpPullUrl"];
		return $url;
	}
	
	/**
	*  @desc 奥点云推拉流
	*  @param string $host 协议，如:http、rtmp
	*  @param string $stream 流名,如有则包含 .flv、.m3u8
	*  @param int $type 类型，0表示播流，1表示推流
	*/
	public function PrivateKey_ady($host,$stream,$type){
		$configpri=$this->getConfigPri();
		$stream_a=explode('.',$stream);
		$streamKey = $stream_a[0];
		$ext = $stream_a[1];

		if($type==1){
			$domain=$host.'://'.$configpri['ady_push'];
			//$time=time() +60*60*10;
			$filename="/".$configpri['ady_apn'].'/'.$stream;
			$url=$domain.$filename;
		}else{
			if($ext=='m3u8'){
				$domain=$host.'://'.$configpri['ady_hls_pull'];
				//$time=time() - 60*30 + $configpri['auth_length'];
				$filename="/".$configpri['ady_apn']."/".$stream;
				$url=$domain.$filename;
			}else{
				$domain=$host.'://'.$configpri['ady_pull'];
				//$time=time() - 60*30 + $configpri['auth_length'];
				$filename="/".$configpri['ady_apn']."/".$stream;
				$url=$domain.$filename;
			}
		}
				
		return $url;
	}
	
	/**
	*  @desc 登录奖励
	*/
	public function LoginBonus($uid,$token){
		$rs=array(
			'bonus_switch'=>'0',
			'bonus_day'=>'0',
			'bonus_list'=>array(),
		);
		$configpri=$this->getConfigPri();
		if(!$configpri['bonus_switch']){
			return $rs;
		}
		$rs['bonus_switch']=$configpri['bonus_switch'];
		$iftoken=$this->checkToken($uid,$token);
		if($iftoken){
			return $iftoken;
		}
		
		/* 获取登录设置 */
		$list=DI()->notorm->loginbonus
					->select("day,coin")
					->fetchAll();
		$rs['bonus_list']=$list;
		$bonus_coin=array();
		foreach($list as $k=>$v){
			$bonus_coin[$v['day']]=$v['coin'];
		}
		
		/* 登录奖励 */
		$userinfo=DI()->notorm->users
					->select("bonus_day,bonus_time")
					->where('id=?',$uid)
					->fetchOne();
		$nowtime=time();
		if($nowtime>$userinfo['bonus_time']){
			//更新
			$bonus_time=strtotime(date("Ymd",$nowtime))+60*60*24;
			$bonus_day=$userinfo['bonus_day'];
			if($bonus_day>6){
				$bonus_day=0;
			}
			$bonus_day++;
			
			$rs['bonus_day']=$bonus_day;
			$coin=$bonus_coin[$bonus_day];
			DI()->notorm->users
				->where('id=?',$uid)
				->update(array("bonus_time"=>$bonus_time,"bonus_day"=>$bonus_day,"coin"=>new NotORM_Literal("coin + {$coin}") ));
			/* 记录 */
			$insert=array("type"=>'income',"action"=>'loginbonus',"uid"=>$uid,"touid"=>$uid,"giftid"=>$bonus_day,"giftcount"=>'0',"totalcoin"=>$coin,"showid"=>'0',"addtime"=>$nowtime );
			$isup=DI()->notorm->users_coinrecord->insert($insert);
		}
		
		return $rs;
		
	}
	
	/* 获取用户VIP */
	public function getUserVip($uid){
		$rs=array(
			'type'=>'0',
		);
		$nowtime=time();
		$isexist=DI()->notorm->users_vip
					->select("*")
					->where('uid=? and endtime>?',$uid,$nowtime)
					->fetchOne();
		if($isexist){
			$rs['type']=$isexist['type'];
		}
		
		return $rs;
	}

	/* 获取用户坐骑 */
	public function getUserCar($uid){
		$rs=array(
			'id'=>'0',
			'swf'=>'',
			'swftime'=>'0',
			'words'=>'',
		);
		$nowtime=time();
		$isexist=DI()->notorm->users_car
					->select("*")
					->where('uid=? and endtime>? and status=1',$uid,$nowtime)
					->fetchOne();
		if($isexist){
			$info=DI()->notorm->car
					->select("*")
					->where('id=?',$isexist['carid'])
					->fetchOne();
			if($info){
				$rs['id']=$info['id'];
				$rs['swf']=$this->get_upload_path($info['swf']) ;
				$rs['swftime']=$info['swftime'];
				$rs['words']=$info['words'];
				
			}
			
		}
		
		return $rs;
	}

	/* 获取用户靓号 */
	public function getUserLiang($uid){
		$rs=array(
			'name'=>'0',
		);
		$nowtime=time();
		$isexist=DI()->notorm->liang
					->select("*")
					->where('uid=? and status=1 and state=1',$uid)
					->fetchOne();
		if($isexist){
			$rs['name']=$isexist['name'];
		}
		
		return $rs;
	}
	
	/* 三级分销 */
	public function setAgentProfit($uid,$total){
				/* 分销 */
		$distribut1=0;
		$distribut2=0;
		$distribut3=0;
		$configpri=$this->getConfigPri();
		if($configpri['agent_switch']==1){
			$agent=DI()->notorm->users_agent
				->select("*")
				->where('uid=?',$uid)
				->fetchOne();
			$isinsert=0;
			/* 一级 */
			if($agent['one_uid'] && $configpri['distribut1']){
				$distribut1=$total*$configpri['distribut1']*0.01;
				$profit=DI()->notorm->users_agent_profit
					->select("*")
					->where('uid=?',$agent['one_uid'])
					->fetchOne();
				if($profit){
					DI()->notorm->users_agent_profit
						->where('uid=?',$agent['one_uid'])
						->update(array('one_profit' => new NotORM_Literal("one_profit + {$distribut1}")));
				}else{
					DI()->notorm->users_agent_profit
						->insert(array('uid'=>$agent['one_uid'],'one_profit' =>$distribut1 ));
				}
				DI()->notorm->users
						->where('id=?',$agent['one_uid'])
						->update(array('votes' => new NotORM_Literal("votes + {$distribut1}")));
				$isinsert=1;
			}
			/* 二级 */
			if($agent['two_uid'] && $configpri['distribut2']){
				$distribut2=$total*$configpri['distribut2']*0.01;
				$profit=DI()->notorm->users_agent_profit
					->select("*")
					->where('uid=?',$agent['two_uid'])
					->fetchOne();
				if($profit){
					DI()->notorm->users_agent_profit
						->where('uid=?',$agent['two_uid'])
						->update(array('two_profit' => new NotORM_Literal("two_profit + {$distribut2}")));
				}else{
					DI()->notorm->users_agent_profit
						->insert(array('uid'=>$agent['two_uid'],'two_profit' =>$distribut2 ));
				}
				DI()->notorm->users
						->where('id=?',$agent['two_uid'])
						->update(array('votes' => new NotORM_Literal("votes + {$distribut2}")));
				$isinsert=1;
			}
			/* 三级 */
			/* if($agent['three_uid'] && $configpri['distribut3']){
				$distribut3=$total*$configpri['distribut3']*0.01;
				$profit=DI()->notorm->users_agent_profit
					->select("*")
					->where('uid=?',$agent['three_uid'])
					->fetchOne();
				if($profit){
					DI()->notorm->users_agent_profit
						->where('uid=?',$agent['three_uid'])
						->update(array('three_profit' => new NotORM_Literal("three_profit + {$distribut3}")));
				}else{
					DI()->notorm->users_agent_profit
						->insert(array('uid'=>$agent['three_uid'],'three_profit' =>$distribut3 ));
				}
				DI()->notorm->users
						->where('id=?',$agent['three_uid'])
						->update(array('votes' => new NotORM_Literal("votes + {$distribut3}")));
				$isinsert=1;
			} */
			
			if($isinsert==1){
				$data=array(
					'uid'=>$uid,
					'total'=>$total,
					'one_uid'=>$agent['one_uid'],
					'two_uid'=>$agent['two_uid'],
					'three_uid'=>$agent['three_uid'],
					'one_profit'=>$distribut1,
					'two_profit'=>$distribut2,
					'three_profit'=>$distribut3,
					'addtime'=>time(),
				);
				
				DI()->notorm->users_agent_profit_recode->insert( $data );
				
			}
		}
		return 1;
		
	}
	
	/* ip限定 */
	public function ip_limit(){
		$configpri=$this->getConfigPri();
		if($configpri['iplimit_switch']==0){
			return 0;
		}
		$date = date("Ymd");
		$ip= ip2long($_SERVER["REMOTE_ADDR"]) ; 
		
		$isexist=DI()->notorm->getcode_limit_ip
				->select('ip,date,times')
				->where(' ip=? ',$ip) 
				->fetchOne();
		if(!$isexist){
			$data=array(
				"ip" => $ip,
				"date" => $date,
				"times" => 1,
			);
			$isexist=DI()->notorm->getcode_limit_ip->insert($data);
			return 0;
		}elseif($date == $isexist['date'] && $isexist['times'] > $configpri['iplimit_times'] ){
			return 1;
		}else{
			if($date == $isexist['date']){
				$isexist=DI()->notorm->getcode_limit_ip
						->where(' ip=? ',$ip) 
						->update(array('times'=> new NotORM_Literal("times + 1 ")));
				return 0;
			}else{
				$isexist=DI()->notorm->getcode_limit_ip
						->where(' ip=? ',$ip) 
						->update(array('date'=> $date ,'times'=>1));
				return 0;
			}
		}	
	}	
	public function birthday_to_age($birthday){ 
		if($birthday ==''){
			return '年龄未知';
		}
		$age = strtotime($birthday); 
		if($age === false){ 
			return false; 
		} 
		list($y1,$m1,$d1) = explode("-",date("Y-m-d",$age)); 
		$now = strtotime("now"); 
		list($y2,$m2,$d2) = explode("-",date("Y-m-d",$now)); 
		$age = $y2 - $y1; 
		if((int)($m2.$d2) < (int)($m1.$d1)) 
		$age -= 1; 
		return $age.'岁'; 
	} 
}