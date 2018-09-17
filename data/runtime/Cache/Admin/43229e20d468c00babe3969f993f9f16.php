<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<!-- Set render engine for 360 browser -->
	<meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- HTML5 shim for IE8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <![endif]-->

	<link href="/public/simpleboot/themes/<?php echo C('SP_ADMIN_STYLE');?>/theme.min.css" rel="stylesheet">
    <link href="/public/simpleboot/css/simplebootadmin.css" rel="stylesheet">
    <link href="/public/js/artDialog/skins/default.css" rel="stylesheet" />
    <link href="/public/simpleboot/font-awesome/4.4.0/css/font-awesome.min.css"  rel="stylesheet" type="text/css">
    <style>
		.length_3{width: 180px;}
		form .input-order{margin-bottom: 0px;padding:3px;width:40px;}
		.table-actions{margin-top: 5px; margin-bottom: 5px;padding:0px;}
		.table-list{margin-bottom: 0px;}
	</style>
	<!--[if IE 7]>
	<link rel="stylesheet" href="/public/simpleboot/font-awesome/4.4.0/css/font-awesome-ie7.min.css">
	<![endif]-->
<script type="text/javascript">
//全局变量
var GV = {
    DIMAUB: "/",
    JS_ROOT: "public/js/",
    TOKEN: ""
};
</script>
<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/public/js/jquery.js"></script>
    <script src="/public/js/wind.js"></script>
    <script src="/public/simpleboot/bootstrap/js/bootstrap.min.js"></script>
<?php if(APP_DEBUG): ?><style>
		#think_page_trace_open{
			z-index:9999;
		}
	</style><?php endif; ?>
</head>
<body>
<style>
input{
  width:500px;
}
.form-horizontal textarea{
 width:500px;
}

.nav-tabs>.current>a{
    color: #95a5a6;
    cursor: default;
    background-color: #fff;
    border: 1px solid #ddd;
    border-bottom-color: transparent;
}
.nav li
{
	cursor:pointer
}
.nav li:hover
{
	cursor:pointer
}
.hide{
	display:none;
}
</style>


	<div class="wrap js-check-wrap">
		<!-- <ul class="nav nav-tabs">
			<li class="active"><a href="<?php echo U('Configprivate/index');?>">设置</a></li>
			<li><a href="<?php echo U('Configprivate/lists');?>">管理</a></li>
			<li><a href="<?php echo U('Configprivate/add');?>">添加</a></li>
		</ul>
		<div class="form-actions">
			<span style="color:#ff0000">提示：新加设置请清空下缓存！</span>
		</div> -->
		<ul class="nav nav-tabs js-tabs-nav">
			<li><a>基本设置</a></li>
			<li><a>登录配置</a></li>
			<li><a>直播配置</a></li>
			<li><a>提现配置</a></li>
			<li><a>推送配置</a></li>
			<li><a>支付管理</a></li>
			<li><a>分销配置</a></li>
		</ul>
		
		<form method="post" class="form-horizontal js-ajax-form" action="<?php echo U('Configprivate/set_post');?>">
		  <input type="hidden" name="post['id']" value="1">
			
			<div class="js-tabs-content">
				<!-- 基本配置 -->
				<div>
					<fieldset>
						<div class="control-group">
							<label class="control-label">缓存开关</label>
							<div class="controls">				
								<label class="radio inline"><input type="radio" value="0" name="post[cache_switch]" <?php if(($config['cache_switch']) == "0"): ?>checked="checked"<?php endif; ?>>关闭</label>
								<label class="radio inline"><input type="radio" value="1" name="post[cache_switch]" <?php if(($config['cache_switch']) == "1"): ?>checked="checked"<?php endif; ?>>开启</label>
								<label class="checkbox inline"></label>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">缓存时间</label>
							<div class="controls">				
								<input type="text" name="post[cache_time]" value="<?php echo ($config['cache_time']); ?>">网站数据的缓存时间
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">用户列表请求间隔</label>
							<div class="controls">				
								<input type="text" name="post[userlist_time]" value="<?php echo ($config['userlist_time']); ?>"> 直播间用户列表刷新间隔时间
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">弹幕费用</label>
							<div class="controls">				
								<input type="text" name="post[barrage_fee]" value="<?php echo ($config['barrage_fee']); ?>"> 每条弹幕的价格（整数）
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">注册奖励</label>
							<div class="controls">				
								<input type="text" name="post[reg_reward]" value="<?php echo ($config['reg_reward']); ?>"> 新用户注册奖励（整数）
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">家族控制</label>
							<div class="controls">				
								<label class="radio inline"><input type="radio" value="0" name="post[family_switch]" <?php if(($config['family_switch']) == "0"): ?>checked="checked"<?php endif; ?>>关闭</label>
								<label class="radio inline"><input type="radio" value="1" name="post[family_switch]" <?php if(($config['family_switch']) == "1"): ?>checked="checked"<?php endif; ?>>开启</label>
								<label class="checkbox inline">家族是否开启</label>
							</div>
						</div>
					</fieldset>
				</div>
				<!-- 登录配置 -->
				<div>
					<fieldset>
						<div class="control-group">
							<label class="control-label">登录奖励开关</label>
							<div class="controls">				
								<label class="radio inline"><input type="radio" value="0" name="post[bonus_switch]" <?php if(($config['bonus_switch']) == "0"): ?>checked="checked"<?php endif; ?>>关闭</label>
								<label class="radio inline"><input type="radio" value="1" name="post[bonus_switch]" <?php if(($config['bonus_switch']) == "1"): ?>checked="checked"<?php endif; ?>>开启</label>
								<label class="checkbox inline"></label>
							</div>
						</div>
						<!-- <div class="control-group">
							<label class="control-label">PC 微信登录appid</label>
							<div class="controls">				
									<input type="text" name="post[login_wx_pc_appid]" value="<?php echo ($config['login_wx_pc_appid']); ?>"> PC 微信登录appid								</div>
						</div>
						<div class="control-group">
							<label class="control-label">PC 微信登录appsecret</label>
							<div class="controls">				
									<input type="text" name="post[login_wx_pc_appsecret]" value="<?php echo ($config['login_wx_pc_appsecret']); ?>"> PC 微信登录appsecret								</div>
						</div><div class="control-group">
							<label class="control-label">PC微博登陆akey</label>
							<div class="controls">				
								<input type="text" name="post[login_sina_pc_akey]" value="<?php echo ($config['login_sina_pc_akey']); ?>"> PC微博登陆akey
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">PC新浪微博skey</label>
							<div class="controls">				
								<input type="text" name="post[login_sina_pc_skey]" value="<?php echo ($config['login_sina_pc_skey']); ?>"> PC新浪微博skey	
							</div>
						</div> -->
						<div class="control-group">
							<label class="control-label">微信公众平台Appid</label>
							<div class="controls">				
								<input type="text" name="post[login_wx_appid]" value="<?php echo ($config['login_wx_appid']); ?>"> 微信公众平台Appid	
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">微信公众平台AppSecret</label>
							<div class="controls">				
								<input type="text" name="post[login_wx_appsecret]" value="<?php echo ($config['login_wx_appsecret']); ?>"> 微信公众平台AppSecret	
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">互亿无线APIID</label>
							<div class="controls">				
								<input type="text" name="post[ihuyi_account]" value="<?php echo ($config['ihuyi_account']); ?>"> 短信验证码   http://www.ihuyi.com/  互亿无线后台-》验证码、短信通知-》账号及签名->APIID
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">互亿无线key</label>
							<div class="controls">				
								<input type="text" name="post[ihuyi_ps]" value="<?php echo ($config['ihuyi_ps']); ?>"> 短信验证码 互亿无线后台-》验证码、短信通知-》账号及签名->APIKEY
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">短信验证码IP限制开关</label>
							<div class="controls">				
								<label class="radio inline"><input type="radio" value="0" name="post[iplimit_switch]" <?php if(($config['iplimit_switch']) == "0"): ?>checked="checked"<?php endif; ?>>关闭</label>
								<label class="radio inline"><input type="radio" value="1" name="post[iplimit_switch]" <?php if(($config['iplimit_switch']) == "1"): ?>checked="checked"<?php endif; ?>>开启</label>
								<label class="checkbox inline">短信验证码IP限制开关</label>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">短信验证码IP限制次数</label>
							<div class="controls">				
								<input type="text" name="post[iplimit_times]" value="<?php echo ($config['iplimit_times']); ?>"> 同一IP每天可以发送验证码的最大次数
							</div>
						</div>
					</fieldset>
				</div>
				<!-- 直播配置 -->
				<div>
					<fieldset>
						<div class="control-group">
							<label class="control-label">认证限制</label>
							<div class="controls">				
								<label class="radio inline"><input type="radio" value="0" name="post[auth_islimit]" <?php if(($config['auth_islimit']) == "0"): ?>checked="checked"<?php endif; ?>>关闭</label>
								<label class="radio inline"><input type="radio" value="1" name="post[auth_islimit]" <?php if(($config['auth_islimit']) == "1"): ?>checked="checked"<?php endif; ?>>开启</label>
								<label class="checkbox inline">主播开播是否需要身份认证</label>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">直播等级控制</label>
							<div class="controls">				
								<label class="radio inline"><input type="radio" value="0" name="post[level_islimit]" <?php if(($config['level_islimit']) == "0"): ?>checked="checked"<?php endif; ?>>关闭</label>
								<label class="radio inline"><input type="radio" value="1" name="post[level_islimit]" <?php if(($config['level_islimit']) == "1"): ?>checked="checked"<?php endif; ?>>开启</label>
								<label class="checkbox inline">直播等级控制是否开启</label>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label">限制等级</label>
							<div class="controls">				
								<input type="text" name="post[level_limit]" value="<?php echo ($config['level_limit']); ?>"> 直播等级限制开启时，最低开播等级
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label">连麦限制等级</label>
							<div class="controls">				
								<input type="text" name="post[linkmic_limit]" value="<?php echo ($config['linkmic_limit']); ?>"> 连麦限制等级
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">聊天服务器带端口</label>
							<div class="controls">				
								<input type="text" name="post[chatserver]" value="<?php echo ($config['chatserver']); ?>"> 格式：http://域名(:端口) 或者 http://IP(:端口)
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">禁言时长</label>
							<div class="controls">				
								<input type="text" name="post[shut_time]" value="<?php echo ($config['shut_time']); ?>">秒 直播间禁言时长
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">踢出时长</label>
							<div class="controls">				
								<input type="text" name="post[kick_time]" value="<?php echo ($config['kick_time']); ?>">秒 直播间踢出时长
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">CDN</label>
							<div class="controls" id="cdn">				
								<!-- <label class="radio inline"><input type="radio" value="1" name="post[cdn_switch]" <?php if(($config['cdn_switch']) == "1"): ?>checked="checked"<?php endif; ?>>阿里云</label> -->
								<label class="radio inline"><input type="radio" value="2" name="post[cdn_switch]" <?php if(($config['cdn_switch']) == "2"): ?>checked="checked"<?php endif; ?>>腾讯云</label>
								<!-- <label class="radio inline"><input type="radio" value="3" name="post[cdn_switch]" <?php if(($config['cdn_switch']) == "3"): ?>checked="checked"<?php endif; ?>>七牛云</label>
								<label class="radio inline"><input type="radio" value="4" name="post[cdn_switch]" <?php if(($config['cdn_switch']) == "4"): ?>checked="checked"<?php endif; ?>>网宿</label>
								<label class="radio inline"><input type="radio" value="5" name="post[cdn_switch]" <?php if(($config['cdn_switch']) == "5"): ?>checked="checked"<?php endif; ?>>网易</label>
								<label class="radio inline"><input type="radio" value="6" name="post[cdn_switch]" <?php if(($config['cdn_switch']) == "6"): ?>checked="checked"<?php endif; ?>>奥点云</label>
								<label class="checkbox inline">其他（可联系商务定制开发）</label> -->
							</div>
						</div>
						<div>
							<div id="cdn_switch_1" class="hide" <?php if(($config['cdn_switch']) == "1"): ?>style="display:block;"<?php endif; ?>>
								<div class="control-group">
									<label class="control-label">阿里云直播鉴权KEY</label>
									<div class="controls">
										<input type="text" name="post[auth_key]" value="<?php echo ($config['auth_key']); ?>"> 直播鉴权KEY 留空表示不启用
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">阿里云播流鉴权有效时长</label>
									<div class="controls">
										<input type="text" name="post[auth_length]" value="<?php echo ($config['auth_length']); ?>"> 播流鉴权有效时长（秒）
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">阿里云推流服务器地址</label>
									<div class="controls">
										<input type="text" name="post[push_url]" value="<?php echo ($config['push_url']); ?>"> 格式：域名(:端口) 或者 IP(:端口)
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">阿里云播流服务器地址</label>
									<div class="controls">				
										<input type="text" name="post[pull_url]" value="<?php echo ($config['pull_url']); ?>"> 格式：域名(:端口) 或者 IP(:端口)
									</div>
								</div>
							</div>
							<div id="cdn_switch_2" class="hide" <?php if(($config['cdn_switch']) == "2"): ?>style="display:block;"<?php endif; ?>>
								<div class="control-group">
									<label class="control-label">直播appid</label>
									<div class="controls">
										<input type="text" name="post[tx_appid]" value="<?php echo ($config['tx_appid']); ?>"> 
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">直播bizid</label>
									<div class="controls">				
										<input type="text" name="post[tx_bizid]" value="<?php echo ($config['tx_bizid']); ?>"> 
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">直播推流防盗链Key</label>
									<div class="controls">				
										<input type="text" name="post[tx_push_key]" value="<?php echo ($config['tx_push_key']); ?>">
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label">直播API鉴权key</label>
									<div class="controls">				
										<input type="text" name="post[tx_api_key]" value="<?php echo ($config['tx_api_key']); ?>">
									</div>
								</div>
							</div>
							<div id="cdn_switch_3" class="hide" <?php if(($config['cdn_switch']) == "3"): ?>style="display:block;"<?php endif; ?>>
								<div class="control-group">
									<label class="control-label">七牛云AccessKey</label>
									<div class="controls">				
										<input type="text" name="post[qn_ak]" value="<?php echo ($config['qn_ak']); ?>"> 
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">七牛云SecretKey</label>
									<div class="controls">				
										<input type="text" name="post[qn_sk]" value="<?php echo ($config['qn_sk']); ?>"> 
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">七牛云直播空间名称</label>
									<div class="controls">				
										<input type="text" name="post[qn_hname]" value="<?php echo ($config['qn_hname']); ?>"> 
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">七牛云推流地址</label>
									<div class="controls">				
										<input type="text" name="post[qn_push]" value="<?php echo ($config['qn_push']); ?>"> 七牛云直播云域名管理中RTMP推流域名
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">七牛云播流地址</label>
									<div class="controls">				
										<input type="text" name="post[qn_pull]" value="<?php echo ($config['qn_pull']); ?>"> 七牛云直播云域名管理中RTMP播流域名
									</div>
								</div>
							</div>
							<div id="cdn_switch_4" class="hide" <?php if(($config['cdn_switch']) == "4"): ?>style="display:block;"<?php endif; ?>>
								<div class="control-group">
									<label class="control-label">网宿推流地址</label>
									<div class="controls">				
										<input type="text" name="post[ws_push]" value="<?php echo ($config['ws_push']); ?>"> 
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">网宿播流地址</label>
									<div class="controls">				
										<input type="text" name="post[ws_pull]" value="<?php echo ($config['ws_pull']); ?>"> 
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">网宿AppName</label>
									<div class="controls">				
										<input type="text" name="post[ws_apn]" value="<?php echo ($config['ws_apn']); ?>"> 
									</div>
								</div>
							</div>
							<div id="cdn_switch_5" class="hide" <?php if(($config['cdn_switch']) == "5"): ?>style="display:block;"<?php endif; ?>>
								<div class="control-group">
									<label class="control-label">网易cdn Appkey</label>
									<div class="controls">				
										<input type="text" name="post[wy_appkey]" value="<?php echo ($config['wy_appkey']); ?>">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">网易cdn AppSecret</label>
									<div class="controls">				
										<input type="text" name="post[wy_appsecret]" value="<?php echo ($config['wy_appsecret']); ?>">
									</div>
								</div>
							</div> 
							
							<div id="cdn_switch_6" class="hide" <?php if(($config['cdn_switch']) == "6"): ?>style="display:block;"<?php endif; ?>>
								<div class="control-group">
									<label class="control-label">奥点云推流地址</label>
									<div class="controls">				
										<input type="text" name="post[ady_push]" value="<?php echo ($config['ady_push']); ?>"> 
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">奥点云播流地址</label>
									<div class="controls">				
										<input type="text" name="post[ady_pull]" value="<?php echo ($config['ady_pull']); ?>"> 
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">奥点云HLS播流地址</label>
									<div class="controls">				
										<input type="text" name="post[ady_hls_pull]" value="<?php echo ($config['ady_hls_pull']); ?>"> 
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">奥点云AppName</label>
									<div class="controls">				
										<input type="text" name="post[ady_apn]" value="<?php echo ($config['ady_apn']); ?>"> 
									</div>
								</div>
							</div>
						</div>
					</fieldset>
					<script>
					(function(){
						$("#cdn label.radio").on('click',function(){
							var v=$("input",this).val();
							var b=$("#cdn_switch_"+v);
							b.siblings().hide();
							b.show();
						})
					})()
					</script>
				</div>
				<!-- 提现配置 -->
				<div>
					<fieldset>
						<div class="control-group">
							<label class="control-label">提现比例</label>
							<div class="controls">				
								<input type="text" name="post[cash_rate]" value="<?php echo ($config['cash_rate']); ?>"> 提现一元人名币需要的票数	
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">提现最低额度（元）</label>
							<div class="controls">				
								<input type="text" name="post[cash_min]" value="<?php echo ($config['cash_min']); ?>"> 可提现的最小额度，低于该额度无法体现
							</div>
						</div>
					</fieldset>
				</div>
				<!-- 三方配置 -->
				<div>
					<fieldset>
						<div class="control-group">
							<label class="control-label">极光推送模式</label>
							<div class="controls">				
								<label class="radio inline"><input type="radio" value="0" name="post[jpush_sandbox]" <?php if(($config['jpush_sandbox']) == "0"): ?>checked="checked"<?php endif; ?>>开发</label>
								<label class="radio inline"><input type="radio" value="1" name="post[jpush_sandbox]" <?php if(($config['jpush_sandbox']) == "1"): ?>checked="checked"<?php endif; ?>>生产</label>
								<label class="checkbox inline">极光推送模式</label>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">极光推送APP_KEY</label>
							<div class="controls">				
								<input type="text" name="post[jpush_key]" value="<?php echo ($config['jpush_key']); ?>">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">极光推送master_secret</label>
							<div class="controls">				
								<input type="text" name="post[jpush_secret]" value="<?php echo ($config['jpush_secret']); ?>">
							</div>
						</div>
					</fieldset>
				</div>
				<!-- 支付管理 -->
				<div>
					<fieldset>
						<div class="control-group">
							<label class="control-label">支付宝APP</label>
							<div class="controls">				
								<label class="radio inline"><input type="radio" value="0" name="post[aliapp_switch]" <?php if(($config['aliapp_switch']) == "0"): ?>checked="checked"<?php endif; ?>>关闭</label>
								<label class="radio inline"><input type="radio" value="1" name="post[aliapp_switch]" <?php if(($config['aliapp_switch']) == "1"): ?>checked="checked"<?php endif; ?>>开启</label>
								<label class="checkbox inline">支付宝APP支付是否开启</label>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">合作者身份ID</label>
							<div class="controls">				
								<input type="text" name="post[aliapp_partner]" value="<?php echo ($config['aliapp_partner']); ?>">支付宝合作者身份ID
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">支付宝帐号</label>
							<div class="controls">				
								<input type="text" name="post[aliapp_seller_id]" value="<?php echo ($config['aliapp_seller_id']); ?>">支付宝登录账号
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">支付宝安卓密钥</label>
							<div class="controls">				
									<textarea name="post[aliapp_key_android]"><?php echo ($config['aliapp_key_android']); ?></textarea>支付宝安卓密钥
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">支付宝苹果密钥</label>
							<div class="controls">				
								<textarea name="post[aliapp_key_ios]"><?php echo ($config['aliapp_key_ios']); ?></textarea>支付宝苹果密钥
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">支付宝校验码</label>
							<div class="controls">				
								<input type="text" name="post[aliapp_check]" value="<?php echo ($config['aliapp_check']); ?>"> 支付宝校验码（扫码支付）
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">苹果支付模式</label>
							<div class="controls">				
								<label class="radio inline"><input type="radio" value="0" name="post[ios_sandbox]" <?php if(($config['ios_sandbox']) == "0"): ?>checked="checked"<?php endif; ?>>沙盒</label>
								<label class="radio inline"><input type="radio" value="1" name="post[ios_sandbox]" <?php if(($config['ios_sandbox']) == "1"): ?>checked="checked"<?php endif; ?>>生产</label>
								<label class="checkbox inline">苹果支付模式</label>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">支付宝PC</label>
							<div class="controls">				
								<label class="radio inline"><input type="radio" value="0" name="post[aliapp_pc]" <?php if(($config['aliapp_pc']) == "0"): ?>checked="checked"<?php endif; ?>>关闭</label>
								<label class="radio inline"><input type="radio" value="1" name="post[aliapp_pc]" <?php if(($config['aliapp_pc']) == "1"): ?>checked="checked"<?php endif; ?>>开启</label>
								<label class="checkbox inline">支付宝PC扫码支付是否开启</label>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">微信支付PC</label>
							<div class="controls">				
								<label class="radio inline"><input type="radio" value="0" name="post[wx_switch_pc]" <?php if(($config['wx_switch_pc']) == "0"): ?>checked="checked"<?php endif; ?>>关闭</label>
								<label class="radio inline"><input type="radio" value="1" name="post[wx_switch_pc]" <?php if(($config['wx_switch_pc']) == "1"): ?>checked="checked"<?php endif; ?>>开启</label>
								<label class="checkbox inline">微信支付PC 是否开启</label>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">微信支付</label>
							<div class="controls">				
								<label class="radio inline"><input type="radio" value="0" name="post[wx_switch]" <?php if(($config['wx_switch']) == "0"): ?>checked="checked"<?php endif; ?>>关闭</label>
								<label class="radio inline"><input type="radio" value="1" name="post[wx_switch]" <?php if(($config['wx_switch']) == "1"): ?>checked="checked"<?php endif; ?>>开启</label>
								<label class="checkbox inline">微信支付开关</label>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">开放平台账号AppID</label>
							<div class="controls">				
								<input type="text" name="post[wx_appid]" value="<?php echo ($config['wx_appid']); ?>">微信开放平台账号AppID
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">微信应用appsecret</label>
							<div class="controls">				
								<input type="text" name="post[wx_appsecret]" value="<?php echo ($config['wx_appsecret']); ?>">微信应用appsecret
							</div>
						</div
						><div class="control-group">
							<label class="control-label">微信商户号mchid</label>
							<div class="controls">				
								<input type="text" name="post[wx_mchid]" value="<?php echo ($config['wx_mchid']); ?>">微信商户号mchid
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">微信密钥key</label>
							<div class="controls">				
								<input type="text" name="post[wx_key]" value="<?php echo ($config['wx_key']); ?>">微信密钥key
							</div>
						</div>
					</fieldset>
				</div>
				<!-- 分销配置 -->
				<div>
					<fieldset>
						<div class="control-group">
							<label class="control-label">分销开关</label>
							<div class="controls">				
								<label class="radio inline"><input type="radio" value="0" name="post[agent_switch]" <?php if(($config['agent_switch']) == "0"): ?>checked="checked"<?php endif; ?>>关闭</label>
								<label class="radio inline"><input type="radio" value="1" name="post[agent_switch]" <?php if(($config['agent_switch']) == "1"): ?>checked="checked"<?php endif; ?>>开启</label>
								<label class="checkbox inline">分销开关</label>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">分销一级分成</label>
							<div class="controls">				
								<input type="text" name="post[distribut1]" value="<?php echo ($config['distribut1']); ?>">% 分销一级分成(整数)
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">分销二级分成</label>
							<div class="controls">				
								<input type="text" name="post[distribut2]" value="<?php echo ($config['distribut2']); ?>">% 分销二级分成(整数)
							</div>
						</div>
						<!-- <div class="control-group">
							<label class="control-label">分销三级分成</label>
							<div class="controls">				
								<input type="text" name="post[distribut3]" value="<?php echo ($config['distribut3']); ?>">% 分销三级分成(整数)
							</div>
						</div> -->
					</fieldset>
				</div>
			</div>
			<div class="form-actions">
				<button type="submit" class="btn btn-primary js-ajax-submit"><?php echo L('SAVE');?></button>
			</div>
		</form>
	</div>
	<script src="/public/js/common.js"></script>
</body>
</html>