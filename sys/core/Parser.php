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
            $this->content = preg_replace($patter,"<?php echo \$this->vars['$1']; ?>",$this->content);
        }
    }

    //private function parIf()
      //编译
    public function compile($file_path,$file_name){
        $this->parVar();
        if (!file_exists($file_path)) {
            @mkdir($file_path,0777,TRUE);
        }
        $parser_file = $file_path.DS.$file_name;
        file_put_contents($parser_file,$this->content);
    }
}