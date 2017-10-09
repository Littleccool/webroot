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

	<div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="page-header">回头率</h4>
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
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th width="100">游戏名称</th>
                                    <th width="50">日期</th>
									<th width="50">dnu</th>
									<th width="50">d1</th>
									<th width="50">d2</th>
									<th width="50">d3</th>
									<th width="50">d4</th>
									<th width="50">d5</th>
									<th width="50">d6</th>
									<th width="50">d7</th>
									<th width="50">d8</th>
									<th width="50">d9</th>
									<th width="50">d10</th>
									<th width="50">d11</th>
									<th width="50">d12</th>
									<th width="50">d13</th>
									<th width="50">d14</th>
									<th width="50">d15</th>
									<th width="50">d16</th>
									<th width="50">d17</th>
									<th width="50">d18</th>
									<th width="50">d19</th>
									<th width="50">d20</th>
									<th width="50">d21</th>
									<th width="50">d22</th>
									<th width="50">d23</th>
									<th width="50">d24</th>
									<th width="50">d25</th>
									<th width="50">d26</th>
									<th width="50">d27</th>
									<th width="50">d28</th>
									<th width="50">d29</th>
									<th width="50">d30</th>
                                </tr>
                                </thead>
                                <tbody>
                                        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                            <td><?php echo ($vo["name"]); ?></td>
                                            <td><?php echo ($vo["date"]); ?></td>
											<td><?php echo ($vo["dnu"]); ?></td>
                                            <td><?php echo ($vo["d1"]); ?></td>
											<td><?php echo ($vo["d2"]); ?></td>
                                            <td><?php echo ($vo["d3"]); ?></td>
                                            <td><?php echo ($vo["d4"]); ?></td>
											<td><?php echo ($vo["d5"]); ?></td>
                                            <td><?php echo ($vo["d6"]); ?></td>
                                            <td><?php echo ($vo["d7"]); ?></td>
											<td><?php echo ($vo["d8"]); ?></td>
                                            <td><?php echo ($vo["d9"]); ?></td>
                                            <td><?php echo ($vo["d10"]); ?></td>
											<td><?php echo ($vo["d11"]); ?></td>
                                            <td><?php echo ($vo["d12"]); ?></td>
                                            <td><?php echo ($vo["d13"]); ?></td>
											<td><?php echo ($vo["d14"]); ?></td>
                                            <td><?php echo ($vo["d15"]); ?></td>
                                            <td><?php echo ($vo["d16"]); ?></td>
											<td><?php echo ($vo["d17"]); ?></td>
                                            <td><?php echo ($vo["d18"]); ?></td>
                                            <td><?php echo ($vo["d19"]); ?></td>
											<td><?php echo ($vo["d20"]); ?></td>
                                            <td><?php echo ($vo["d21"]); ?></td>
                                            <td><?php echo ($vo["d22"]); ?></td>
											<td><?php echo ($vo["d23"]); ?></td>
                                            <td><?php echo ($vo["d24"]); ?></td>
                                            <td><?php echo ($vo["d25"]); ?></td>
                                            <td><?php echo ($vo["d26"]); ?></td>
                                            <td><?php echo ($vo["d27"]); ?></td>
                                            <td><?php echo ($vo["d28"]); ?></td>
                                            <td><?php echo ($vo["d29"]); ?></td>
                                            <td><?php echo ($vo["d30"]); ?></td>
                                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                                 </tbody>
                            </table>
							<div class="showpage"><?php echo ($pageshow); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


</body>
</html>