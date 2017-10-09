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
                <h4 class="page-header">修改密码 - <?php echo ($user['username']); ?></h4>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-primary">
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="" id="editUserForm">

                           <input type="hidden" name="id" value="<?php echo ($user["id"]); ?>">
                            <!--div class="form-group">
                                <label for="old_password" class="col-sm-2 control-label">原始密码</label>
                                <div class="col-sm-4">
                                    <input type="password" class="form-control" id="old_password" name="old_password" placeholder="原始登录密码">
                                </div>
                            </div-->
							<div class="form-group">
                                <label for="username" class="col-sm-2 control-label">所属角色</label>
                                <div class="col-sm-4">
   										<?php if(is_array($user_js)): $i = 0; $__LIST__ = $user_js;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><input type="radio" name="user_js" value="<?php echo ($vo["id"]); ?>" <?php if(($vo["id"]) == $user["user_js"]): ?>checked<?php endif; ?>> <?php echo ($vo["js_name"]); ?> &nbsp;&nbsp;&nbsp;&nbsp;<?php endforeach; endif; else: echo "" ;endif; ?>
	
                                </div>
                            </div>
							
                            <div class="form-group">
                                <label for="password" class="col-sm-2 control-label">登录密码</label>
                                <div class="col-sm-4">
                                    <input type="password" class="form-control" id="password" name="user[password]" placeholder="登录密码">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation" class="col-sm-2 control-label">确认密码</label>
                                <div class="col-sm-4">
                                    <input type="password" class="form-control" id="password_confirmation" name="user[password_confirmation]" placeholder="重复上面登录密码">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" id="editUserSubmit" class="btn btn-primary">提 交</button>
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
	    function build_html(status,info,operation){
		if(status === 1){
            var html = [
                '<div class="modal-header">',
                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>',
                    '<h4 class="modal-title" id="gridSystemModalLabel">' + operation + '</h4>',
                '</div>',
                '<div class="modal-body">',
                    '<div class="container-fluid">',
                        '<div class="row">',
                            '<div class="col-md-2">',
                                '<button type="button" class="btn btn-success btn-circle">',
                                    '<i class="fa fa-check"></i>',
                                '</button>',
                            '</div>',
                            '<div class="col-md-8"> 成功 </div>',
                        '</div>',
                    '</div>',
                '</div>',
            ].join('');
		}
		else{
            var html = [
                '<div class="modal-header">',
                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>',
                    '<h4 class="modal-title" id="gridSystemModalLabel">' + operation + '</h4>',
                '</div>',
                '<div class="modal-body">',
                    '<div class="container-fluid">',
                        '<div class="row">',
                            '<div class="col-md-2">',
                                '<button type="button" class="btn btn-danger btn-circle">',
                                    '<i class="fa fa-times"></i>',
                                '</button>',
                            '</div>',
                            '<div class="col-md-8"> ' + info + ' </div>',
                        '</div>',
                    '</div>',
                '</div>',
            ].join('');
		}
		return html;
	}
    // ajax form拦截提交事件 
	$('#editUserSubmit').click(function(){
		var options = {
			dataType: 'json',
			timeout: 3000,
			success: function (data) {
				var html = build_html(data.status,data.info,data.operation);
				$('.modal-content').html(html);
                $('#postTip').modal('show');

				setTimeout( function(){
                    if(data.status === 1)  //成功
                    {
                        var url = data.url;
                        var hostname = window.location.hostname;
                        var re = url.indexOf(hostname);
                        if(re != -1)
                        {
                            window.location = url;
                        }
                        else{
                            window.location = '<?php echo U('Game/Game');?>';
                        }
                    }
                },3000);

			},
			error: function(){
				var html = build_html(0, '服务器端异常', '操作');
				$('.modal-content').html(html);
                $('#postTip').modal('show');
				//setTimeout("location.reload()",2000);
			}
		};
		$('#editUserForm').ajaxForm(options);

	});
 });
</script>

</body>
</html>