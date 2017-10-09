<?php if (!defined('THINK_PATH')) exit();?><html lang="zh-CN"><head>
    <meta charset="UTF-8">
    <meta content="麻将" name="description">
    <meta content="" name="author">
    <meta content="webkit" name="renderer">	<meta content="IE=Edge,chrome=1" http-equiv="X-UA-Compatible">	<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <title> 同乐乐麻将 </title>
	
	<link href="<?php echo (CSS_PATH); ?>order.css" rel="stylesheet">
    <link href="<?php echo (CSS_PATH); ?>bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo (CSS_PATH); ?>metisMenu.min.css" rel="stylesheet">
    <link href="<?php echo (CSS_PATH); ?>dataTables.bootstrap.css" rel="stylesheet">
    <link href="<?php echo (CSS_PATH); ?>dataTables.responsive.css" rel="stylesheet">
    <link href="<?php echo (CSS_PATH); ?>sb-admin-2.css" rel="stylesheet">
    <link href="<?php echo (CSS_PATH); ?>font-awesome.min.css" rel="stylesheet">


    <script src="<?php echo (JS_PATH); ?>jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo (JS_PATH); ?>bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo (JS_PATH); ?>metisMenu.min.js" type="text/javascript"></script>
    <script src="<?php echo (JS_PATH); ?>jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="<?php echo (JS_PATH); ?>dataTables.bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo (JS_PATH); ?>sb-admin-2.js" type="text/javascript"></script>
	<script src="__PUBLIC__/My97DatePicker/WdatePicker.js" type="text/javascript"></script> 



</head>
<body>

<nav style="margin-bottom: 0" role="navigation" class="navbar navbar-default navbar-static-top">
    <div class="navbar-header">
        <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a href="#" class="navbar-brand">同乐乐麻将</a>
		<a href="#" class="navbar-brand"><th> 钻石剩余： </th><td><?php echo ($user_cardnum["card_num"]); ?></td></a> 
    </div>

    <ul class="nav navbar-top-links navbar-right">
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="dropdown-toggle">
                <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="#"><i class="fa fa-user fa-fw"></i> <?php echo $_SESSION['username'];?></a>
                </li>
                <!--li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                </li-->
                <li class="divider"></li>
                <li><a href="<?php echo U('Login/logout');?>"><i class="fa fa-sign-out fa-fw"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;退 出</a>
                </li>
            </ul>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>
	
	<div role="navigation" class="navbar-default sidebar">
        <div class="sidebar-nav navbar-collapse">
            <ul id="side-menu" class="nav">
                <li class="sidebar-search">
                    <div class="input-group custom-search-form">
                        <input type="text" placeholder="Search..." class="form-control">
                                <span class="input-group-btn">
                                <button type="button" class="btn btn-default btn-tiny-pad">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                    </div>
                    <!-- /input-group -->
                </li>
				
				<?php if(is_array($show_lanmu)): $i = 0; $__LIST__ = $show_lanmu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sl): $mod = ($i % 2 );++$i;?><li <?php if(($sl["id"]) == $left_css): ?>class="active"<?php endif; ?>>
                    <a href="#"><i class="fa <?php echo ($sl["lanmu_css"]); ?> fa-fw"></i><?php echo ($sl["lanmu_name"]); ?><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse <?php if(($sl["id"]) == $left_css): ?>in<?php endif; ?>">
                        <?php if(is_array($sl["sub"])): $i = 0; $__LIST__ = $sl["sub"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$slsub): $mod = ($i % 2 );++$i;?><li>
                            <a href="<?php echo ($slsub["url"]); ?>"><?php echo ($slsub["lanmu_name"]); ?></a>
                        </li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>
                
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
</nav>	


<script type="text/javascript">
//时间选择
function selecttime(){  
        var endTime = $("#sdate").val();
        if(endTime != ""){
            WdatePicker({dateFmt:'yyyy-MM-dd',maxDate:endTime});
        }else{
            WdatePicker({dateFmt:'yyyy-MM-dd'});
        }    
}

</script>

	<div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="page-header">单日充值明细</h4>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
			<form class="form-horizontal" method="POST" action="" id="addUserForm">
			<div class="form-group">
				<label for="username" class="col-sm-2 control-label">平台</label>
				<div class="col-sm-4">
					<select  name="appId" style="height:30px;">
						<?php if(is_array($gamelist)): $i = 0; $__LIST__ = $gamelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["appId"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
					</select>
				</div>
				<div class="col-sm-4">
					<td>日期:</td>
					<td><input type="text" name="start_time" id="sdate" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})"  size="17" class="date" readonly> </td>
				</div>
			</div>
		<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<button type="submit" id="addUserSubmit" class="btn btn-primary">提 交</button>
				</div>
			</div>
        </form>
			
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="table-responsive">
						
						<table class="table table-striped"  id="tbfirst" style="table-layout:fixed" width="50%">
			
								<thead>
                                <tr>
									 <th width="50"  >充值汇总</th>
									<th width="50" ></th>
									<th width="50" ></th>
									<th width="50" ></th>
									<th width="50" ></th>

                                </tr>
                                </thead>
								<?php if(is_array($totalInfo)): $i = 0; $__LIST__ = $totalInfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr><td>充值总额</td><td><?php echo ($vo["rmb"]); ?></td></tr>
								<tr><td>钻石总额</td><td><?php echo ($vo["cash"]); ?></td></tr>
								<tr><td>笔数</td><td><?php echo ($vo["times"]); ?></td></tr>
								<tr><td>人数</td><td><?php echo ($vo["person"]); ?></td></tr><?php endforeach; endif; else: echo "" ;endif; ?>                               
</table>
						<table class="table table-striped"  id="tb" cellspacing="1" cellpadding="0"
            width="100%" style="table-layout:fixed">
			
								<!-- <thead>
                                <tr>
									<th width="50" colspan="2" >充值汇总</th>
                                </tr>
                                </thead>
								<?php if(is_array($totalInfo)): $i = 0; $__LIST__ = $totalInfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr><td>充值总额</td><td><?php echo ($vo["rmb"]); ?></td></tr>
								<tr><td>钻石总额</td><td><?php echo ($vo["cash"]); ?></td></tr>
								<tr><td>笔数</td><td><?php echo ($vo["times"]); ?></td></tr>
								<tr><td>人数</td><td><?php echo ($vo["person"]); ?></td></tr><?php endforeach; endif; else: echo "" ;endif; ?> -->
								<thead> 
                                <tr>
									<th width="50" >充值时间</th>
									<th width="50" >玩家名称</th>
									<th width="50" >uid</th>
									<th width="50" >充值金额</th>
									<th width="50" >充值钻石</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr> 
									<td><?php echo ($vo["ts"]); ?></td>
									<td><?php echo ($vo["nickName"]); ?></td>
									<td><?php echo ($vo["uid"]); ?></td>
									<td><?php echo ($vo["rmb"]); ?></td>
									<td><?php echo ($vo["cash"]); ?></td>
								</tr><?php endforeach; endif; else: echo "" ;endif; ?>
                                 </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
<script type="text/javascript"> 
jQuery(document).ready(function() {

 	var d = new Date();
    	function addzero(v) {if (v < 10) return '0' + v;return v.toString();}
    	var s = d.getFullYear().toString() + '-'+addzero(d.getMonth() + 1) + '-' +  d.getDate(); 
    	document.getElementById('sdate').value=s;
	//$("#sdate").simpleDatepicker({startdate: 2011, enddate: 2018});
	
	var today = new Date();
	var month = today.getMonth() + 1;
	var year = today.getFullYear();
	var day = today.getDate();
	var defsdate = month + "/" + day + "/" + year;
	$("#sdate").val(defsdate);
});

</script>

</body>
</html>