<?php
/**
 * Created by PhpStorm.
 * User: xuehao
 * Date: 2017/5/25
 * Time: 下午3:33
 */
namespace core\traits;
use core\Config;
use core\View; 

trait Jump{
	/**
     * success 成功提示页面
     * @access public
     * @return void
     */
    public static function success($mess="操作成功", $timeout=1, $location=""){
		$location = self::makeLocation($location);
		self::echoTplHtml(true,$mess,$timeout,$location);//直接输出模板	
    }
	/**
     * error 失败提示页面
     * @access public
     * @return void
     */
	public static function error($mess="操作失败", $timeout=3, $location=""){
		$location = Jump::makeLocation($location);
		self::echoTplHtml(false,$mess,$timeout,$location); //直接输出模板
	}
	/** 
		 * 用于在控制器中进行位置重定向
		 * @param	string	$path	用于设置重定向的位置
		 * @param	string	$args 	用于重定向到新位置后传递参数
		 * 
		 * $this->redirect("index")  /当前模块/index
		 * $this->redirect("user/index") /user/index
		 * $this->redirect("user/index", 'page/5') /user/index/page/5
		 */
	public static function redirect($path, $args=""){
		$path=trim($path, "/");
		if($args!="")
			$args="/".trim($args, "/");
		if(strstr($path, "/")){
			$url=$path.$args;
		}else{
			$url=$_GET["m"]."/".$path.$args;
		}

		$uri=DS.Config::get("default_module").DS.$url;
		//使用js跳转前面可以有输出
		echo '<script>';
		echo 'location="'.$uri.'"';
		echo '</script>';
	}
	
	/**
     * makeLocation  对location进行单独处理 
     * @access private
     * @return void
     */
	private static function makeLocation($location){
		if($location==""){
			$location= "window.location.href = document.referrer;";
		}else{
			$path=trim($location, "/");
			if(strstr($path, "/")){
				$url=$path;
			}else{
				$url=$_GET["m"]."/".$path;
			}
			$location=DS.Config::get("default_module").DS.$url;
			$location="window.location='{$location}'";
		}
		return $location;
	}
	/**
     * echoTplHtml  输出模板页面 
     * @access private
     * @return void
     */
	private static function echoTplHtml($mark,$mess,$timeout,$location){
		echo '
		<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>提示消息 - BroPHP</title>

			<style type="text/css">
				body { font: 75% Arail; text-align: center; }
				#notice { width: 300px; background: #FFF; border: 1px solid #BBB; background: #EEE; padding: 3px;
				position: absolute; left: 50%; top: 50%; margin-left: -155px; margin-top: -100px; }
				#notice div { background: #FFF; padding: 30px 0 20px; font-size: 1.2em; font-weight:bold }
				#notice p { background: #FFF; margin: 0; padding: 0 0 20px; }
				a { color: #f00} a:hover { text-decoration: none; }
				
			</style>
		</head>
		<body>
		<div id="notice">
			<div style="width:100%;text-align:left;padding-left:10px;padding-right:10px">'.$mess.'</div>';
			if ($mark){
			echo '
				<p style="font: italic bold 2cm cursive,serif; color:green">
					√ 
				</p>';
			}else{
			echo '
				<p style="font: italic bold 2cm cursive,serif; color:red">
					×
			</p>';
			};
			echo '
			<p>
				 在( <span id="sec" style="color:blue;font-weight:bold">'.$timeout.'</span> )秒后自动跳转，或直接点击 <a href="javascript:'.$location.'">这里</a> 跳转<br>
				 <span style="display:block;text-decoration:underline;cursor:pointer;line-height:25px" onclick="stop(this)">停止</span>
	
			</p>
		 </div>	
			<script>
		 		var seco=document.getElementById("sec");
				var time='.$timeout.';
				var tt=setInterval(function(){
						time--;
						seco.innerHTML=time;	
						if(time<=0){
							'.$location.';
							return;
						}
					}, 1000);
				function stop(obj){
					clearInterval(tt);
					obj.style.display="none";
				}
			</script>
		</body>
		</html>';
		exit;
	}
}