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

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"  id="postTip">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">

            </div>
        </div>
    </div>

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="page-header">添加角色</h4>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-primary">
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="" id="addUserForm">
							<input type="hidden" name="permissions" value="all">
                            <div class="form-group">
                                <label for="username" class="col-sm-2 control-label">角色名称</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="js_name" name="js_name" placeholder="角色名称">
                                </div>
                            </div>
							<div class="form-group">
                                <label for="username" class="col-sm-2 control-label">可管理金币</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="jinbi" name="jinbi" placeholder="可管理金币" value="0">
                                </div>
                            </div>
							<div class="form-group">
                                <label for="username" class="col-sm-2 control-label">可管理钻石</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="diamond" name="diamond" placeholder="可管理钻石" value="0">
                                </div>
                            </div>
							<div class="form-group">
                                <label for="username" class="col-sm-2 control-label">可管理存款</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="deposit" name="deposit" placeholder="可管理存款" value="0">
                                </div>
                            </div>
							<div class="form-group">
                                <label for="username" class="col-sm-2 control-label">角色类型</label>
                                <div class="col-sm-4">
                                    <input type="radio" name="js_flag" value="0" checked="checked">普通管理员 
									&nbsp;&nbsp;<input type="radio" name="js_flag" value="1" >超级管理员(拥有所有权限)
                                </div>
                            </div>
							
							<?php if(is_array($lanmu)): $k = 0; $__LIST__ = $lanmu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo1): $mod = ($k % 2 );++$k;?><div class="form-group">
                                <label for="username" class="col-sm-2 control-label"><?php if($k == 1): ?>权限管理<?php endif; ?></label>
                                <div class="col-sm-4">
                                    <input type="checkbox" name="<?php echo ($vo1["id"]); ?>" value="1"><?php echo ($vo1["lanmu_name"]); ?>
									&nbsp;&nbsp;
                                </div>
                            </div>
								<?php if(is_array($vo1['sub'])): $i = 0; $__LIST__ = $vo1['sub'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo11): $mod = ($i % 2 );++$i;?><div class="form-group">
									<label for="username" class="col-sm-2 control-label"></label>
									<div class="col-sm-4">
										&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="<?php echo ($vo11["id"]); ?>" id="<?php echo ($vo11["id"]); ?>" onclick="subsel('<?php echo ($vo11["id"]); ?>')"  value="1">--<?php echo ($vo11["lanmu_name"]); ?>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<?php if(is_array($caozuo)): $i = 0; $__LIST__ = $caozuo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?>&nbsp;&nbsp;<input type="checkbox" name="<?php echo ($vo11["id"]); ?>_power[]" value="<?php echo ($vo2["power_do"]); ?>" ><?php echo ($vo2["power_name"]); endforeach; endif; else: echo "" ;endif; ?>
									</div>
								</div><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" id="addUserSubmit" class="btn btn-primary">提 交</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

	    	

            
    <script src="<?php echo (JS_PATH); ?>jquery.form.js"></script>
    <script type="text/javascript">
	$(document).ready(function(){
		 $('input:radio[name="js_flag"]').change(function() { 
			if ($('input:radio[name="js_flag"]:checked').val()=="1"){
				$("input:checkbox").each(function() {  
					$(this).prop("checked", true);
				});  
			}else{
				$("input:checkbox").each(function() {  
					$(this).prop("checked",false);
				});  
			}
		});	
		
		
	});
	function subsel(id){
		showid = "#"+id;
		showname = id + "_power";
		flag = $(showid).prop('checked');
		if (flag){
			$("input:checkbox[name^="+showname+"]").each(function() {  
				$(this).prop("checked", true);
			});  
		}else{
			$("input:checkbox[name^="+showname+"]").each(function() {  
				$(this).prop("checked", false);
			});  
		}
	}
	</script>

</body>
</html>