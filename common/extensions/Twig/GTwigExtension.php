<?php
namespace common\extensions\Twig;

use Yii;
use yii\widgets\ActiveForm;
use common\components\Time;
use backend\models\CategoryBack;
use backend\models\CategoryBrand;
use backend\models\Blackboard;
class GTwigExtension extends \Twig_Extension
{
    /**
    * {@inheritdoc}
    */
    public function getName()
    {
        return 'gTwigExtension';
    }
    
     
    /**
    * {@inheritdoc}
    */
    public function getGlobals()
    {
        return array(
            'App' =>Yii::$app->user,
	    'session' => Yii::$app->session,
        );
    }
     
     
    /**
    * {@inheritdoc}
    */
    public function getFunctions()
    {
        return array(
            "widget" => new \Twig_Function_Method($this, 'widget', array('is_safe'=>['html'])),
        );
    }
    /**
    * {@inheritdoc}
    */
    public function getFilters()
    {
        return array(
            "value_callback"=> new \Twig_Filter_Method($this,'eval_string',array()),
            "cut"=> new \Twig_Filter_Method($this,'cutstr',array()),
            "replace_share"=> new \Twig_Filter_Method($this,'replace_share',array()),
            "unserialize"=> new \Twig_Filter_Method($this,'unSeria',array()),
            "getTime"=> new \Twig_Filter_Method($this,'getTime',array()),
            "timeAgo"=> new \Twig_Filter_Method($this,'timeAgo',array()),
        );
    }

    public function eval_string($s){
        return eval('return function($model,$index,$column_data){'.$s.'};');
    }

    public function widget($viewName, array $config) 
    {
        return $viewName::widget($config);
    }
    /**
     * sub str
     * @param  [type] $string [description]
     * @param  [type] $length [description]
     * @param  string $etc    [description]
     * @return [type]         [description]
     */
    function cutstr($string,$length,$etc="..."){
        $result = '';
        $string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
        $strlen = strlen($string);
        for ($i = 0; (($i < $strlen) && ($length > 0)); $i++){
            if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0')){
                if ($length < 1.0){
                    break;
                }
                $result .= substr($string, $i, $number);
                $length -= 1.0;
                $i += $number - 1;
            }else{
                $result .= substr($string, $i, 1);
                $length -= 0.5;
            }
        }
        $result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
        if ($i < $strlen){
            $result .= $etc;
        }
        return $result;
    }

    public function replace_share($message){
        // return  $message.'32424324234';
        // $pattern = "@\[(quote|flash|audio|wma|wmv|rm|media)\](.*?)\[/(quote|flash|audio|wma|wmv|rm|media)\]|\[(?!(attach|img)|\/(attach|img))[^\[\]]{1,}\]@s";
        // // $pattern = "@\[(quote|flash|audio|wma|wmv|rm|media)\](.*?)\[/(quote|flash|audio|wma|wmv|rm|media)\]]{1,}\]@s";
        // // preg_replace('/\[[^\[\]]{1,}\]/','',$message);
        // $message =  preg_replace($pattern,'',$message);
        return preg_replace('/\[attach\](.*)\[\/attach\]/','',$message);
    }
     
     function unSeria($string){
        // return $string;
        $rs =  unserialize($string);
        return $rs;
     }

     public function getTime($open_time){
        $hour=floor((strtotime($open_time)-time())/3600);
        $minute = floor((strtotime($open_time)-time())%3600/60);
        return $hour.'小时'.$minute.'分';
     }

     public function timeAgo($unix_time,$language="zh_cn")
     {
        if (empty($unix_time)) {
            return "No date provided";
        }

        $locales = array(
            'en_us' => array(
                'tmStrings' => array(' second', ' minute', ' hour', ' day', ' week', ' month', ' year', 'decade'),
                'plural' => 's',
                'tense'  => array(' ago', ' from now'),
            ),
            'zh_cn' => array(
                'tmStrings' => array('秒', '分', '小时', '天', '周', '月', '年', '十年'),
                'plural' => false,
                'tense'  => array('前', '之后'),
            ),
        );
        if (!array_key_exists($language, $locales)) {
            $language = 'en_us';
        }
        $periods = $locales[$language]['tmStrings'];

        $lengths = array("60", "60", "24", "7", "4.35", "12");
        $now = time();
        // var_dump($date);exit;
        // $unix_time = strtotime($date);

        if (empty($unix_time)) {
            return "Bad date";
        }

        if ($now > $unix_time) {
            $difference = $now - $unix_time;
            $tense = $locales[$language]['tense'][0];
        } else {
            $difference = $unix_time - $now;
            $tense = $locales[$language]['tense'][1];
        }

        for($j = 0; $difference >= $lengths[$j] && $j < count($lengths) -1; $j++) {
            $difference /= $lengths[$j];
        }
        $difference = round($difference);

        $p = $periods[$j];
        if ($difference != 1 && $locales[$language]['plural']) {
            $p .= $locales[$language]['plural'];
        }

        return $difference.$p.$tense;
     }
}
