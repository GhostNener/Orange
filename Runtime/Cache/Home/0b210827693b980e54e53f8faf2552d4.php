<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta name="description" content="贵州财经大学-二手交易-以物换物-服务平台-c2c">
	<meta name="author" content="橘子团队">
	<title>大橘子-贵财二手交易平台</title>
	<!-- Bootstrap Core CSS -->
	<link href="/Orange/Public/css/bootstrap.min.css" rel="stylesheet">
	<link href="/Orange/Public/css/normalize.css" rel="stylesheet">
	<!-- Custom CSS -->
	<link href="/Orange/Public/css/juzi.css" rel="stylesheet">
	<link rel="shortcut icon" href="/Orange/Public/Img/favicon.png"
	type="image/x-icon" />
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>

<body>
	<!-- 页身 -->
	<div id="wrap">
		<!-- 导航 -->
		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="container">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="<?php echo U('Home/Index/index');?>">logo 大橘子</a>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li class="active">
							<a href="<?php echo U('Home/Index/index');?>">首页</a>
						</li>
						<li>
							<a href="<?php echo U('Home/Goods/index');?>">商品管理</a>
						</li>
						<li>
							<a href="#">发现</a>
						</li>
						<li>
							<a href="#">
								礼品
								<span class="navbar-new"></span>
							</a>
						</li>
					</ul>
					<form class="navbar-form navbar-left" role="search" action="<?php echo U('Home/Index/searchgoods');?>" method="post">
						<div class="input-group">
							<input type="text" class="form-control " id="text" placeholder="搜索商品" name="text" value="<?php echo ($test); ?>">
							<div class="input-group-btn">
								<button type="submit" class="btn btn-default btn-expend">
									<span class="glyphicon glyphicon-search"></span>
								</button>
							</div>
							<!-- /btn-group -->
						</div>
						<!-- /input-group -->
					</form>

					<!-- 未登录状态 -->
					<?php if($usermodel == null): ?><ul class="nav navbar-nav navbar-right">
							<li>
								<a href="<?php echo U('Usercenter/User/index');?>" >登录</a>
							</li>
							<li>
								<a href="<?php echo U('Usercenter/User/regist');?>">注册</a>
							</li>
						</ul>
						<?php else: ?>
						<!-- 登录状态 -->
						<ul class="nav navbar-nav navbar-right">
							<li class="dropdown">
								<a class="dropdown-toggle" data-toggle="dropdown" href="#">
									<img src="http://hhhhold.com/18x18">
									&nbsp<?php echo ($usermodel['Nick']); ?>&nbsp
									<span class="caret"></span>
									&nbsp
									<span class="badge">3</span>
								</a>
								<ul class="dropdown-menu">
									<li>
										<a href="#">
											<span class="badge pull-right">3</span>
											未读消息
										</a>
									</li>
									<li>
										<a href="<?php echo U('Usercenter/UserCnter/index');?>">个人中心</a>
									</li>
									<li>
										<a href="#">心愿单</a>
									</li>
									<li>
										<a href="#">充值</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="#">退出用户</a>
									</li>
								</ul>
							</li>
						</ul><?php endif; ?>
				</div>
				<!-- /.navbar-collapse -->
			</div>
			<!-- /.container-fluid -->
		</nav>
		<!-- 内容{__CONTENT__} -->
		

<div id="main" class="container">
	<div class="text-center">
		<h1>发布商品</h1>
	</div>
	<br>

	<!-- 分类管理-->
	<form class="form-horizontal" action="" role="form" method="post"
		multiple="true">
		<input type="hidden" class="form-control " id="imgcount"
			name="imgcount" value="0" Readonly>
		<input type="hidden"
			class="form-control " id="keyid" name="keyid" value="0" Readonly>
		<div class="form-group">
			<label for="Title" class="col-sm-2 control-label">Title</label>
			<div class="col-sm-10">
				<input type="text" class="form-control " id="Title"
					placeholder=" Title  " name="Title"></div>
		</div>
		<div class="form-group">
			<label for="Price" class="col-sm-2 control-label">Price</label>
			<div class="col-sm-10">
				<input type="number" class="form-control " id="Price"
					placeholder=" Price  " name="Price"></div>
		</div>
		<div class="form-group">
			<label for="CostPrice" class="col-sm-2 control-label">CostPrice</label>
			<div class="col-sm-10">
				<input type="number" class="form-control " id="CostPrice"
					placeholder=" CostPrice  " name="CostPrice"></div>
		</div>
		<div class="form-group">
			<label for="Presentation" class="col-sm-2 control-label">Presentation</label>
			<div class="col-sm-10">
				<textarea class="form-control " name="Presentation"
					id="Presentation" cols="30" rows="10"></textarea>
			</div>
		</div>
		<div class="form-group">
			<label for="Category" class="col-sm-2 control-label">Category</label>
			<div class="col-sm-10">
				<select class="form-control " name="Category" id="Category">
					<?php if(is_array($clist)): foreach($clist as $key=>$v): ?><option value="<?php echo ($v['Id']); ?>"><?php echo ($v['Title']); ?></option><?php endforeach; endif; ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="Address" class="col-sm-2 control-label">Address</label>
			<div class="col-sm-8">
				<select class="form-control " name="Address" id="Address">
					<?php if(is_array($alist)): foreach($alist as $key=>$v): ?><option value="<?php echo ($v['Id']); ?>" address="<?php echo ($v['Address']); ?>"><?php echo ($v['Tel']); ?>&nbsp;&nbsp;<?php echo ($v['Address']); ?></option><?php endforeach; endif; ?>
				</select>
			</div>
			<div class="col-sm-2">
				<div class="pull-left">
					<a id="refreshadd" class="btn btn-default" data-toggle="tooltip"
						data-placement="top" title="刷新地址">
						<span
						class="glyphicon glyphicon-refresh"></span>
					</a>
					<a href="<?php echo U('Usercenter/Address/addaddress');?>" target="_blank"
						class="btn btn-default" data-toggle="tooltip" data-placement="top"
						title="添加地址">
						<span class="glyphicon glyphicon-plus"></span>
					</a>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="TradeWay" class="col-sm-2 control-label">TradeWay</label>
			<div class="col-sm-10">
				<select class="form-control " name="TradeWay" id="TradeWay">
					<option value="1">线上</option>
					<option value="2">线下</option>
					<option value="3">线上/线下</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="Server" class="col-sm-2 control-label">Server</label>
			<div class="col-sm-10">
				<div id="Server">
					<?php if(is_array($slist)): foreach($slist as $key=>$v): ?><label
						class="checkbox-inline">
							<input type="checkbox"
						value="<?php echo ($v['Id']); ?>"><?php echo ($v['Title']); ?></label><?php endforeach; endif; ?>
				</div>
			</div>
		</div>
		<div class="form-group">
			<input type="hidden" class="form-control " id="url"
				publicurl="/Orange/Public" appurl="/Orange/Home/Goods" rooturl="/Orange" gid="0"
				saveurl="<?php echo U('Home/Goods/save');?>" urlindex="<?php echo U('Home/Goods/index');?>" Readonly>
			<label
				for="file_upload" class="col-sm-2 control-label">file_upload</label>
			<div class="col-sm-10">
				<input id="file_upload" name="file_upload" type="file"
					multiple="true">
				<div id="image" class=" col-sm-12"></div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="button" id="submitsave" class="btn btn-default">保存</button>
				<a class="btn btn-default" href="<?php echo U('Home/Index/index');?>">返回</a>
			</div>
		</div>
	</form>
</div>

		<!-- 内容{__CONTENT__} -->

	</div>
	页脚
	<footer>
		<div class="container">
			<div class="row hidden-xs">
				<div class="col-md-5">
					<p> <b>介绍</b>
					</p>
					<p>Logo design by 某某某</p>
					<p>Powerd by ThinkPHP</p>
					<p>Copyright &copy; 2014, 指尖科技-橘子团队</p>
					<p>
						<a href="http://www.miibeian.gov.cn/">黔ICP备14004869号-1</a>
					</p>
				</div>
				<div class="col-md-5">
					<p> <b>关于</b>
					</p>
					<p>
						<a href="#">橘子团队</a>
					</p>
					<p>
						<a href="#">问题反馈</a>
					</p>
					<p>
						<a href="#">联系我们</a>
					</p>
					<p>
						<a href="#">免责声明</a>
					</p>
				</div>
				<div class="col-md-2">
					<p>
						<b>客户端下载(android)</b>
					</p>
					<img src="http://hhhhold.com/110x110" alt=""></div>
			</div>
			<div class="row visible-xs-inline">
				<div class="col-md-12 text-center">
					<p>
						<a href="<?php echo U('Admin/Index/index');?>">Copyright &copy; 2014, 指尖科技-橘子团队</a>
					</p>
					<p>
						<a href="http://www.miibeian.gov.cn/">黔ICP备14004869号-1</a>
					</p>
				</div>
			</div>
		</div>
	</footer>
	<script src="/Orange/Public/js/jquery-1.11.0.js"></script>
	<script src="/Orange/Public/js/bootstrap.min.js"></script>
	<script src="/Orange/Public/js/juzi.js"></script>
	<!-- 特殊js -->
	
	
	<script src="/Orange/Public/js/jquery.uploadify.min.js?<?php echo time();?>"></script>
<link rel="stylesheet" href="/Orange/Public/css/uploadify.css">
<script type="text/javascript">
	/*删除图片  第一个参数为图片父控件的Id  第二个参数为图片相对路径*/
	function del(div_id,imgId){
    	var _pdivid='#'+div_id;
		var _url=$('#url').attr('appurl')+'/delimg';		
         $.post(_url,{'Id':parseInt(imgId)},function(data){		
        	 if(data.info==1){
        		 $(_pdivid).html(data.info);
        		 $(_pdivid).remove();	
                 var _imgcount=parseInt($('#imgcount').val());
                 if((_imgcount-1)<=0){
                	 _imgcount=0;
                 }else{_imgcount=_imgcount-1;}
                 $('#imgcount').val((_imgcount));
        	 }else{
        		 alert("删除失败\n"+data.info);
        	 }
        },'json');	
	}
		$(document).ready(function(){
				$('#file_upload').uploadify({
			'fileTypeDesc' : 'Image Files',	
			'overrideEvents': [ 'onSelectError' ],	
			'formData'  : {'sname':'{$sname}','sid':'<?php echo ($sid); ?>',                    
			'cid' : '<?php echo ($cid); ?>',
			'ckey' : '<?php echo ($ckey); ?>'},/*FF302解决参数*/			
			//'removeCompleted' : false,    //是否自动消失
       		'fileTypeExts' : '*.gif; *.jpg; *.png',		//允许类型
       		'fileSizeLimit' : '4MB',					//允许上传最大值
			'swf'      : $('#url').attr('publicurl')+'/Img/uploadify.swf',	//加载swf
			'uploader' : $('#url').attr('appurl')+'/uploadify',					//上传路径
			'buttonText' :'添加图片',	
			'uploadLimit':30,
			'queueSizeLimi':5,
			'debug':false,
			'preventCaching':true,/*防止缓存*/
			'fileObjName ':'Filedata',/*File数组参数名*/
			'progressData':'percentage',
			'removeTimeout':0,
			'onSelectError':function(file, errorCode, errorMsg){  
				var msgText = "上传失败\n";
                switch(errorCode) {  
                    case -100:  
                        msgText += "每次最多上传 " + this.settings.queueSizeLimit + "个文件";;  
                        break;  
                    case -110:  
                        msgText += "文件大小超过限制( " + this.settings.fileSizeLimit + " )"; 
                        break;  
                    case -120:  
                      msgText += "文件["+file.name+"]大小为0"; 
                        break;  
                    case -130:  
                         msgText += "文件["+file.name+"]格式不正确，仅限 " + this.settings.fileTypeExts;
                        break;  
                    default:
              		    msgText += "错误代码：" + errorCode + "\n" + errorMsg;
                } 
                alert(msgText); 
            },


			'onUploadSuccess' : function(file, data, response) {
				if(!data){alert('上传失败！');
				return;
			}
			data=$.parseJSON(data);
			/*data[0] 图片ID*/
			/*data[1] 图片相对url*/
			if(!($.isNumeric(data[0]))||!data[0]){
				alert("上传失败！\n"+data[1]);
				return;
			}
			var _gid=$('#url').attr('gid');
			var _postimgurl=$('#url').attr('appurl')+'/saveimg';
			$.post(_postimgurl,{
				'_imgid':data[0],
				'_gid':_gid
			},function(datainfo){
				if(parseInt(datainfo.status)==1){
					/*成功上传返回*/
					$('#url').attr('gid',datainfo.info);
					var _imgId='_'+(parseInt(Math.random() *100)+(new Date()).valueOf());
					var _tempPath=$('#url').attr('rooturl');
					/*插入到image标签内，显示图片的缩略图 */
					$('#image').append('<div id="'+_imgId+'" class="photo" style="float: left; margin-right: 3px;margin-left: 3px" ><img src="'+_tempPath+data[1]+'"  height=100 width=100 /><div class="del" style="text-align: center;"><a class="a_imgdel" href="javascript:void(0)"onclick=del("'+_imgId+'","'+data[0]+'");return false; imgurl="'+data[1]+'">删除</a></div></div>');
					var _imgcount=parseInt($('#imgcount').val());
					$('#imgcount').val((_imgcount+1));
						return;
					}else{
						alert('上传失败！');
						return;
					}
				});
			}
		});
	});
	$(document).ready(function () {
		/*刷新地址*/
		$('#refreshadd').click(function(e){
			var _url=$('#url').attr('appurl')+'/refreshadd';
			$.post(_url,{'':''},function(data){
				if(data.status==1){
					if(!data.info){return;}
					var _arr=$.parseJSON(data.info);
					if(!_arr){return;}
					$('#Address').children('option').remove();
					$(_arr).each(function(i,v){
						$('#Address').append("<option value="+v['Id']+" address="+v['address']+" >"+v['Tel']+"&nbsp;&nbsp;"+v['Address']+"</option>");
					});
					return;
				}
			});		
		});
    	/*标题焦点离开事件  获取分类*/
    	$('#Title').blur(function(e){
    		var _Title=$.trim($('#Title').val());
    		if(!_Title){
    			return;
    		}
    		$.post($('#url').attr('appurl')+'/getcategory',{
    			'Title':_Title
    		},function(data){
    			/*收索失败*/
    			if(data.status==0||!data.status){
    				var _arr=$.parseJSON(data.info);
    				/*新的关键字*/
    				if(_arr[1]>1||parseInt(_arr[1])>1){
    					$('#keyid').val(_arr[1]);
    				}
    				return;
    			}
    			/*搜索成功 返回结果*/
    			if(data.status==1||data.status){
    				var _arr=$.parseJSON(data.info);
    				/*返回一个结果的时候 选中返回项*/
    				if(parseInt(_arr[0])==1){
    					$('#Category').children('option').each(function(){
    						if(parseInt($(this).val())==parseInt(_arr[1])){
    							$(this).attr('selected',true);
    						}
    					});
    					$('#keyid').val(0);
    					return;
    				}
    				/*返回多个结果  需要先清空 下拉列表 然后重新生成  生成按搜索顺序+剩下的分类顺序  木人选中第一项*/
    				if(parseInt(_arr[0])==2){
    					var _arr=$.parseJSON(data.info);
    					$('#Category').children('option').remove();
    					$(_arr[1]).each(function(i,v){
    						$('#Category').append("<option value="+v['Id']+">"+v['Title']+"</option>");
    					});
    					$('#keyid').val(0);
    					return;
    				}
    			}
    		},'json');});
		/*提交按钮*/
    	$('#submitsave').click(function(e){

    		    		var _Server=new Array();
			$('#Server').children('label').each(function(){

				if($(this).children('input').attr('checked')){
					_Server.push($(this).children('input').val());
				}
			});
			_Server=_Server.join('|');
			if(!_Server){
				_Server=0;
			}

    	/*	判断是否填写的title*/
    		var _Title=$.trim($('#Title').val());
    		if(!_Title){
    			$('#Title').focus();
    			return;
    		}
    		/*判断是否填写价格*/
    		var _Price=$.trim($('#Price').val());
    		if(!$.isNumeric(_Price)||parseInt(_Price)<=0||parseInt(_Price)>99999){
    			$('#Price').val('');
    			$('#Price').focus();
    			return;
    		}
    		/*判断是否填写原价*/
    		var _CostPrice=$.trim($('#CostPrice').val());
    		if(!$.isNumeric(_CostPrice)||parseInt(_CostPrice)<=0||parseInt(_CostPrice)>99999){
    			$('#CostPrice').val('');
    			$('#CostPrice').focus();
    			return;
    		}
    		if(parseInt(_CostPrice)<parseInt(_Price)){
    			$('#Price').focus();
    			return;
    		}
    		/*简介*/
    		var _Presentation=$.trim($('#Presentation').val());
    		var _keyid=$('#keyid').val();
    		if($.isNumeric(_keyid)){
    			_keyid=parseInt(_keyid);
    		}else{
    			_keyid=0;
    		}
    		/*分类*/
    		var _Category=$('#Category').val();
    		var _Address=$('#Address').val();
    		if(!$.isNumeric(_Address)||parseInt(_Address)<=0){
    			$('#Address').focus();
    			return;
    		}
    		$('#Address').children('option').each(function(){    			
    			var tempadd=$.trim($(this).attr('address'));
    			var tempval=parseInt($(this).val());
    			if(tempval==parseInt(_Address)){
    				if(!tempadd){
    					alert("所选的地址为空！");
    					_Address=0;
    					$('#Address').focus();
    					return;
    				}
    			}
    		});
    		if(!_Address){
    			$('#Address').focus();
    			return;
    		}
    		var _GoodsId=$('#url').attr('gid');
    		var _TradeWay=$('#TradeWay').val();  		
    		    		/*判断是否传了图*/
    		var _imgcount=$('#imgcount').val();
    		if(parseInt(_imgcount)<=0){
    			alert('至少上传一张图片！');
    			return;
    		}
    		var btn_txt=$(this).val();
    		$(this).attr('disabled',"true");
    		$(this).val('....');
    		$.post($('#url').attr('saveurl'),{
    			'GoodsId':_GoodsId,
    			'imgcount':_imgcount,
    			'Title':_Title,
    			'Price':_Price,
    			'CostPrice':_CostPrice,
    			'Presentation':_Presentation,
    			'CategoryId':_Category,
    			'AddressId':_Address,
    			'TradeWay':_TradeWay,
    			'Server':_Server,
    			'keyid':_keyid
    		},function(data){
    			if(data.info==1){
    				/*alert('Ok!');*/
    				location.href=$('#url').attr('urlindex');
    			}else{
    				alert(data.info);
    			}
    		});
    		$(this).val(btn_txt);
    		$(this).removeAttr('disabled');   		  		
    	});
    })
</script>

	<!-- 特殊css -->
	
</body>
</html>