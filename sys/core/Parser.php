<?php
/**
 * Created by PhpStorm.
 * User: xuehao
 * Date: 2017/4/1
 * Time: 下午4:21
 */
namespace core;

/**
 *  解析
 */
class Parser
{
    private $content;
    function __construct($file)
    {
        $this->content = file_get_contents($file);
        if (!$this->content) {
            exit('Template file read failed');
        }
    }
    //解析普通变量
    private function parVar()
    {
        $patter = '/\{\$([\w]+)\}/';
        $repVar = preg_match($patter,$this->content);
        if ($repVar) {
            $this->content = preg_replace($patter,"<?php echo \$this->_vars['$1']; ?>",$this->content);
        }
    }
   //解析if
    private function parIf(){
        //开头if模式
        $_patternIf = '/\{if\s+\$([\w]+)\}/';
        //结尾if模式
        $_patternEnd = '/\{\/if\}/';
        //else模式
        $_patternElse = '/\{else\}/';
        //判断if是否存在
        if(preg_match($_patternIf, $this->content)){
            //判断是否有if结尾
            if(preg_match($_patternEnd, $this->content)){
                //替换开头IF
                $this->content = preg_replace($_patternIf, "<?php if(\$this->_vars['$1']){ ?>", $this->content);
                //替换结尾IF
                $this->content = preg_replace($_patternEnd, "<?php } ?>", $this->content);
                //判断是否有else
                if(preg_match($_patternElse, $this->content)){
                    //替换else
                    $this->content = preg_replace($_patternElse, "<?php }else{ ?>", $this->content);
                }
            }else{
                exit('ERROR：语句没有关闭！');
            }
        }
    }
    //解析循环数据
    private function parForeach(){
        $_patternForeach = '/\{foreach\s+\$(\w+)\((\w+),(\w+)\)\}/';
        $_patternEndForeach = '/\{\/foreach\}/';
        //foreach里的值
        $_patternVar = '/\{@(\w+)\}/';
        //判断是否存在
        if(preg_match($_patternForeach, $this->content)){
            //判断结束标志
            if(preg_match($_patternEndForeach, $this->content)){
                //content
                $this->content = preg_replace($_patternForeach, "<?php foreach(\$this->_vars['$1'] as \$$2=>\$$3){?>", $this->content);
                //替换结束
                $this->content = preg_replace($_patternEndForeach, "<?php } ?>", $this->content);
                //替换值
                $this->content = preg_replace($_patternVar, "<?php echo \$$1?>", $this->content);
            }else{
                exit('ERROR：Foreach语句没有关闭');
            }
        }
    }
    //解析include
    private function parInclude(){
        $_pattern = '/\{include\s+\"(.*)\"\}/';
        if(preg_match($_pattern, $this->content,$_file)){
            //判断头文件是否存在
            if(!file_exists($_file[1]) || empty($_file[1])){
                exit('ERROR：包含文件不存在！');
            }
            //替换内容
            $this->content = preg_replace($_pattern, "<?php include '$1';?>", $this->content);
        }
    }
    //解析单行PHP注释
    private function parCommon(){
        $_pattern = '/\{#\}(.*)\{#\}/';
        if(preg_match($_pattern, $this->content)){
            $this->content = preg_replace($_pattern, "<?php /*($1) */?>", $this->content);
        }
    }

    //解析系统函数 config.php中的变量
    private function parSys(){
        $_pattern = '/<!--\{(\w+)\}-->/';
        if(preg_match($_pattern, $this->content,$_file)){
            $this->content = preg_replace($_pattern,"<?php echo \$this->_config['$1'] ?>", $this->content);

        }
    }
    //解析function
    private function parFunc(){

    }
      //编译
    public function compile($file_path,$file_name){
        //解析模板变量
        $this->parVar();
        //解析IF
        $this->parIf();
        //解析Foreach
        $this->parForeach();
        //解析include
        $this->parInclude();
        //解析注释
        $this->parCommon();
        //解析系统变量
        $this->parSys();
        if (!file_exists($file_path)) {
            @mkdir($file_path,0777,TRUE);
        }
        $parser_file = $file_path.DS.$file_name;
        file_put_contents($parser_file,$this->content);
    }
}