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
	<div class="wrap">
		<ul class="nav nav-tabs">
			<li class="active"><a >认证申请</a></li>

		</ul>
		<form class="well form-search" method="post" action="<?php echo U('Userauth/index');?>">
		  审核状态：
			<select class="select_2" name="status">
				<option value="">全部</option>
				<option value="0" <?php if($formget["status"] == '0'): ?>selected<?php endif; ?> >处理中</option>
				<option value="1" <?php if($formget["status"] == '1'): ?>selected<?php endif; ?> >审核成功</option>			
				<option value="2" <?php if($formget["status"] == '2'): ?>selected<?php endif; ?> >审核失败</option>			
			</select>
			提交时间：
			<input type="text" name="start_time" class="js-date date" value="<?php echo ($formget["start_time"]); ?>" style="width: 80px;" autocomplete="off">-
			<input type="text" class="js-date date" name="end_time" value="<?php echo ($formget["end_time"]); ?>" style="width: 80px;" autocomplete="off"> &nbsp; &nbsp;
			关键字： 
			<input type="text" name="keyword" style="width: 200px;" value="<?php echo ($formget["keyword"]); ?>" placeholder="请输入会员ID、姓名、手机">
			<input type="submit" class="btn btn-primary" value="搜索">
		</form>				
		<form method="post" class="js-ajax-form" >

		
			<table class="table table-hover table-bordered">
				<thead>
					<tr>
						<th align="center">会员ID</th>
						<th>会员</th>						
						<th>真实姓名</th>
						<th>手机号码</th>

						<th>审核状态</th>
						<th>提交时间</th>
						<th>处理时间</th>

						<th align="center"><?php echo L('ACTIONS');?></th>
					</tr>
				</thead>
				<tbody>
					<?php $status=array("0"=>"处理中","1"=>"审核成功", "2"=>"审核失败"); ?>
					<?php if(is_array($lists)): foreach($lists as $key=>$vo): ?><tr>
						<td align="center"><?php echo ($vo["uid"]); ?></td>
						<td><?php echo ($vo['userinfo']['user_nicename']); ?> </td>	
						<td><?php echo ($vo['real_name']); ?></td>
						<td><?php echo ($vo['mobile']); ?></td>				
						<td><?php echo ($status[$vo['status']]); ?></td>
						<td><?php echo (date("Y-m-d H:i:s",$vo["addtime"])); ?></td>						
						<td>
						 <?php if($vo['status'] == '0'): ?>处理中
						 <?php else: ?>
						     <?php echo (date("Y-m-d H:i:s",$vo["uptime"])); endif; ?>						
						 </td>

						<td align="center">	
						
						    <a href="<?php echo U('Userauth/edit',array('id'=>$vo['uid']));?>" >编辑</a>  
						 
 							
							<!-- <a href="<?php echo U('Cash/del',array('id'=>$vo['uid']));?>" class="js-ajax-dialog-btn" data-msg="您确定要删除吗？">删除</a>  -->
						</td>
					</tr><?php endforeach; endif; ?>
				</tbody>
			</table>
			<div class="pagination"><?php echo ($page); ?></div>

		</form>
	</div>
	<script src="/public/js/common.js"></script>
</body>
</html>