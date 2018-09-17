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
			<li> <a href="<?php echo U('user/agent_index');?>">代理</a></li>
			<li class="active"><a >收益统计</a></li>
		</ul>

		<!-- <form class="well form-search" method="post" action="<?php echo U('user/channel_regist');?>">
			
			关键字： 
			<input type="text" name="keyword" style="width: 200px;" value="<?php echo ($formget["keyword"]); ?>" placeholder="请输入代理用户名或ID...">
			<input type="submit" class="btn btn-primary" value="搜索">		
		</form>
		 -->
		<?php if($dateStatus == 'today'): ?>今日
		<?php elseif($dateStatus == 'yesterday'): ?>昨日
		<?php else: ?>本月<?php endif; ?>
		收益总额：<?php echo ($sum); ?>&nbsp;&nbsp;&nbsp;
		<a class="btn" href="<?php echo U('user/agent_index');?>"><?php echo L('BACK');?></a>

		<table class="table table-hover table-bordered" style="margin-top: 20px;">
			<thead>
				<tr>
					<th width="50">ID</th>
					<th>充值用户</th>
					<!-- <th>充值金额</th>
					<th>收益比例</th> -->
					<th>收益金额</th>
					<th>收益类型</th>
					<th>收益时间</th>
				</tr>
			</thead>
			<tbody>
				
				<?php $type=array("1"=>"充值") ?>
				<?php if(is_array($incomelists)): foreach($incomelists as $key=>$vo): ?><tr>
					<td><?php echo ($vo["id"]); ?></td>
					<td><?php echo ($vo["senduser"]); ?>(<?php echo ($vo["senduid"]); ?>)</td>
					<!-- <td><?php echo ($vo["charge_money"]); ?></td>
					<td><?php echo ($vo["percent"]); ?>%</td> -->
					<td><?php echo ($vo["money"]); ?></td>
					<td><?php echo ($type[$vo['type']]); ?></td>
					<td><?php echo (date("Y-m-d H:i:s",$vo["addtime"])); ?></td>
					
				</tr><?php endforeach; endif; ?>
			</tbody>
		</table>
		<div class="pagination"><?php echo ($page); ?></div>
	</div>
	<script src="/public/js/common.js"></script>
</body>
</html>