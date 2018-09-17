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
	<div class="wrap js-check-wrap">
		<ul class="nav nav-tabs">
			<li class="active"><a href="<?php echo U('user/channel_index');?>">渠道</a></li>
			<?php if($_SESSION['ADMIN_status'] != 0): ?><li><a href="<?php echo U('user/add_channel',array('source'=>'channel_index'));?>">渠道添加</a></li><?php endif; ?>

		</ul>

		<div>渠道总数：<?php echo ($count); ?></div><br />

		<form class="well form-search" method="post" action="<?php echo U('user/channel_index');?>">
			
			关键字： 
			<input type="text" name="keyword" style="width: 200px;" value="<?php echo ($formget["keyword"]); ?>" <?php if($_SESSION['ADMIN_status'] == 0): ?>placeholder="请输入渠道用户名或ID或代理ID..." <?php else: ?> placeholder="请输入渠道用户名或ID..."<?php endif; ?>>
			<input type="submit" class="btn btn-primary" value="搜索">
		</form>	

		<table class="table table-hover table-bordered">
			<thead>
				<tr>
					<th width="50">ID</th>
					<th><?php echo L('USERNAME');?></th>
					<th>用户身份</th>
					<?php if($_SESSION['ADMIN_status'] == 0): ?><th>所属代理</th><?php endif; ?>
					<th><?php echo L('LAST_LOGIN_IP');?></th>
					<th><?php echo L('LAST_LOGIN_TIME');?></th>
					<th><?php echo L('EMAIL');?></th>
					<th>分成比例</th>
					<th>注册用户数</th>
					<th>注册量(人)</th>
					<th>充值金额(元)</th>
					<th>收益(元)</th>
					<th>结算</th>
					<th><?php echo L('STATUS');?></th>
					<th width="120"><?php echo L('ACTIONS');?></th>
				</tr>
			</thead>
			<tbody>
				<?php $user_statuses=array("0"=>L('USER_STATUS_BLOCKED'),"1"=>L('USER_STATUS_ACTIVATED'),"2"=>L('USER_STATUS_UNVERIFIED')); ?>
				<?php $user_identity=array("0"=>"管理员","1"=>"渠道","2"=>"代理"); ?>
				<?php if(is_array($users)): foreach($users as $key=>$vo): ?><tr>
					<td><?php echo ($vo["id"]); ?></td>
					<td><?php echo ($vo["user_login"]); ?></td>
					<td><?php echo ($user_identity[$vo['status']]); ?></td>
					<?php if($_SESSION['ADMIN_status'] == 0): ?><td><?php echo ($vo["uppername"]); ?>(<?php echo ($vo["upper"]); ?>)</td><?php endif; ?>
					<td><?php echo ($vo["last_login_ip"]); ?></td>
					<td>
						<?php if($vo['last_login_time'] == 0): echo L('USER_HAVENOT_LOGIN');?>
						<?php else: ?>
							<?php echo ($vo["last_login_time"]); endif; ?>
					</td>
					<td><?php echo ($vo["user_email"]); ?></td>
					<td><?php echo ($vo["income_percent"]); ?>%</td>
					<td><?php echo ($vo["userNum"]); ?></td>
					<td>
						<a href='<?php echo U("user/channel_regist",array("id"=>$vo["id"],"date"=>"today"));?>'>今日【<?php echo ($vo["todayRegNum"]); ?>】</a>,
						<a href='<?php echo U("user/channel_regist",array("id"=>$vo["id"],"date"=>"yesterday"));?>'>昨日【<?php echo ($vo["yesterdayRegNum"]); ?>】</a>,
						<a href='<?php echo U("user/channel_regist",array("id"=>$vo["id"],"date"=>"localmonth"));?>'>本月【<?php echo ($vo["localMonthRegNum"]); ?>】</a>
					</td>
					<td>
						<a href='<?php echo U("user/channel_charge",array("id"=>$vo["id"],"date"=>"today"));?>'>今日【<?php echo ($vo["todayMoney"]); ?>】</a>,
						<a href='<?php echo U("user/channel_charge",array("id"=>$vo["id"],"date"=>"yesterday"));?>'>昨日【<?php echo ($vo["yesterdayMoney"]); ?>】</a>,
						<a href='<?php echo U("user/channel_charge",array("id"=>$vo["id"],"date"=>"localmonth"));?>'>本月【<?php echo ($vo["localmonthMoney"]); ?>】</a>
					</td>
					<td>
						<a href='<?php echo U("user/channel_income",array("id"=>$vo["id"],"date"=>"today"));?>'>今日【<?php echo ($vo["todayInCome"]); ?>】</a>,
						<a href='<?php echo U("user/channel_income",array("id"=>$vo["id"],"date"=>"yesterday"));?>'>昨日【<?php echo ($vo["yesterdayInCome"]); ?>】</a>,
						<a href='<?php echo U("user/channel_income",array("id"=>$vo["id"],"date"=>"localmonth"));?>'>本月【<?php echo ($vo["localmonthInCome"]); ?>】</a>
					</td>
					<td>
						总收益：<?php echo ($vo['incomeMsg']['total_income']); ?>元<br />
						已结算：<?php echo ($vo['incomeMsg']['settled']); ?>元<br />
						未结算：<?php echo ($vo['incomeMsg']['unsettled']); ?>元<br />

					</td>
					<td><?php echo ($user_statuses[$vo['user_status']]); ?></td>
					<td>
						
							<a href='<?php echo U("user/channel_edit",array("id"=>$vo["id"]));?>'><?php echo L('EDIT');?></a> | 
							<a class="js-ajax-dialog-btn" href="<?php echo U('user/channel_delete',array('id'=>$vo['id']));?>" data-msg="该渠道下所有用户将一并归属平台。您确定要删除该渠道吗？"><?php echo L('DELETE');?></a> | 
							<?php if($vo['user_status'] == 1): ?><a href="<?php echo U('user/channel_ban',array('id'=>$vo['id']));?>" class="js-ajax-dialog-btn" data-msg="<?php echo L('BLOCK_USER_CONFIRM_MESSAGE');?>"><?php echo L('BLOCK_USER');?></a> 
							<?php else: ?>
								<a href="<?php echo U('user/channel_cancelban',array('id'=>$vo['id']));?>" class="js-ajax-dialog-btn" data-msg="<?php echo L('ACTIVATE_USER_CONFIRM_MESSAGE');?>"><?php echo L('ACTIVATE_USER');?></a><?php endif; ?>

							<a href='<?php echo U("user/channel_settle",array("channelid"=>$vo["id"]));?>'>收益结算</a> |
							<a href='<?php echo U("user/channel_settle_lists",array("channelid"=>$vo["id"]));?>'>收益结算清单</a>
							
					</td>
				</tr><?php endforeach; endif; ?>
			</tbody>
		</table>
		<div class="pagination"><?php echo ($page); ?></div>
	</div>
	<script src="/public/js/common.js"></script>
</body>
</html>