<?php
namespace common\components;

/**
 * Class SubPages
 * @package common\components
 */
class SubPages
{

    /**
     * @var int 每页显示的条目数
     */
    private $each_disNums;
    /**
     * @var int 总条目数
     */
    private $nums;
    /**
     * @var int 当前被选中的页
     */
    private $current_page;
    /**
     * @var int 每次显示的页数
     */
    private $sub_pages;
    /**
     * @var float 总页数
     */
    private $pageNums;
    /**
     * @var array 用来构造分页的数组
     */
    private $page_array = [];
    /**
     * @var string 每个分页的链接
     */
    private $subPage_link;
    /**
     * @var int 显示分页的类型
     */
    private $subPage_type;


    /**
     * __construct是SubPages的构造函数，用来在创建类的时候自动运行.
     * 当@subPage_type=1的时候为普通分页模式
     * example：   共4523条记录,每页显示10条,当前第1/453页 [首页] [上页] [下页] [尾页]
     * 当@subPage_type=2的时候为经典分页样式
     * example：   当前第1/453页 [首页] [上页] 1 2 3 4 5 6 7 8 9 10 [下页] [尾页]
     * @param $each_disNums int 每页显示的条目数
     * @param $nums  int 总条目数
     * @param $current_page int 当前被选中的页
     * @param $sub_pages int 每次显示的页数
     * @param $subPage_link string 每个分页的链接
     * @param $subPage_type int 显示分页的类型
     */
    public function __construct($each_disNums, $nums, $current_page, $sub_pages, $subPage_link, $subPage_type)
    {
        $this->each_disNums = intval($each_disNums);
        $this->nums = intval($nums);
        if (!$current_page) {
            $this->current_page = 1;
        } else {
            $this->current_page = intval($current_page);
        }
        $this->sub_pages = intval($sub_pages);
        $this->pageNums = ceil($nums / $each_disNums);
        $this->subPage_link = $subPage_link;
        //$this->show_SubPages($subPage_type);   
        //echo $this->pageNums."--".$this->sub_pages;  
    }


    /**
     * show_SubPages函数用在构造函数里面。而且用来判断显示什么样子的分页
     * @param $subPage_type int 样式
     * @return string
     */
    public function show_SubPages($subPage_type, $suffix=null, $type=null)
    {
        if ($subPage_type == 1) {
            return $this->subPageCss1($suffix);
        } elseif ($subPage_type == 2) {
            return $this->subPageCss2();
        } elseif ($subPage_type == 3) {
            return $this->subPageCss3($type);
        }
    }


    /**
     * 用来给建立分页的数组初始化的函数。
     * @return array
     */
    public function initArray()
    {
        for ($i = 0; $i < $this->sub_pages; $i++) {
            $this->page_array[$i] = $i;
        }

        return $this->page_array;
    }


    /**
     * construct_num_Page该函数使用来构造显示的条目
     * 即使：[1][2][3][4][5][6][7][8][9][10]
     * @return array
     */
    public function construct_num_Page()
    {
        if ($this->pageNums < $this->sub_pages) {
            $current_array = array();
            for ($i = 0; $i < $this->pageNums; $i++) {
                $current_array[$i] = $i + 1;
            }
        } else {
            $current_array = $this->initArray();
            if ($this->current_page <= 3) {
                for ($i = 0; $i < count($current_array); $i++) {
                    $current_array[$i] = $i + 1;
                }
            } elseif ($this->current_page <= $this->pageNums && $this->current_page > $this->pageNums - $this->sub_pages + 1) {
                for ($i = 0; $i < count($current_array); $i++) {
                    $current_array[$i] = ($this->pageNums) - ($this->sub_pages) + 1 + $i;
                }
            } else {
                for ($i = 0; $i < count($current_array); $i++) {
                    $current_array[$i] = $this->current_page - 2 + $i;
                }
            }
        }

        return $current_array;
    }

    public function changeLink($link, $page, $type)
    {
        $new_link = '';
        $links = explode("-", $link);
        if (count($links)>3) {
            $new_link = $links[0]."-".$links[1]."-" . $page."-".$links[3] . ".html".$type;
        }else{
            $new_link = $link.$page. ".html".$type;
        }
        return $new_link;
    }

    /**
     * 构造经典模式的分页
     * 当前第1/453页 [首页] [上页] 1 2 3 4 5 6 7 8 9 10 [下页] [尾页]
     * @return string
     */
    public function subPageCss3($type)
    {
        $subPageCss2Str = "";
        $subPageCss2Str.='<div class="fr paging_page">';
        if ($this->current_page > 1) {
            $firstPageUrl = $this->changeLink($this->subPage_link , "1", $type);
            $prewPageUrl = $this->changeLink($this->subPage_link, ($this->current_page - 1), $type);
            $subPageCss2Str .= "<a href='$firstPageUrl'>首页</a> ";
            $subPageCss2Str .= "<a href='$prewPageUrl'>上一页</a> ";
        } else {
            //$subPageCss2Str ='';
        }

        $a = $this->construct_num_Page();
        if (count($a) > 1) {
            for ($i = 0; $i < count($a); $i++) {
                $s = $a[$i];
                if ($s == $this->current_page) {
                    $subPageCss2Str .= "<span class='paging_number'>" . $s . "</span>";
                } else {
                    $url = $this->subPage_link . $s;
                    $subPageCss2Str .= "<a class='paging_number' href='$url'>" . $s . "</a>";
                }
            }
            $key = count($a) - 1;
            $maxnum = $a[$key];
            if ($maxnum < $this->pageNums) {
                $subPageCss2Str .= "<a class='paging_number'>" . '...' . "</a>";
            }
        }
        if ($this->current_page < $this->pageNums) {
            $lastPageUrl = $this->changeLink($this->subPage_link , $this->pageNums, $type);
            $nextPageUrl = $this->changeLink($this->subPage_link , ($this->current_page + 1), $type);
            $subPageCss2Str .= " <a href='$nextPageUrl'>下一页</a> ";
            $subPageCss2Str .= "<a href='$lastPageUrl'>尾页</a>";
        } else {

        }

        $subPageCss2Str.='</div>';
        return $subPageCss2Str;
    }

    /**
     * 构造经典模式的分页
     * 当前第1/453页 [首页] [上页] 1 2 3 4 5 6 7 8 9 10 [下页] [尾页]
     * @return string
     */
    public function subPageCss2()
    {
        $subPageCss2Str = "";
        // $subPageCss2Str.='<div class="fr paging_page">';
        if ($this->current_page > 1) {
            $firstPageUrl = $this->subPage_link . "1";
            $prewPageUrl = $this->subPage_link . ($this->current_page - 1);
            //$subPageCss2Str .= "<a href='$firstPageUrl'>首页</a> ";
            $subPageCss2Str .= "<a href='$prewPageUrl'>上一页</a> ";
        } else {
            //$subPageCss2Str ='';
        }

        $a = $this->construct_num_Page();
        if (count($a) > 1) {
            for ($i = 0; $i < count($a); $i++) {
                $s = $a[$i];
                if ($s == $this->current_page) {
                    $subPageCss2Str .= "<span class='paging_number'>" . $s . "</span>";
                } else {
                    $url = $this->subPage_link . $s;
                    $subPageCss2Str .= "<a class='paging_number' href='$url'>" . $s . "</a>";
                }
            }
            $key = count($a) - 1;
            $maxnum = $a[$key];
            if ($maxnum < $this->pageNums) {
                $subPageCss2Str .= "<a class='paging_number'>" . '...' . "</a>";
            }
        }
        if ($this->current_page < $this->pageNums) {
            $lastPageUrl = $this->subPage_link . $this->pageNums;
            $nextPageUrl = $this->subPage_link . ($this->current_page + 1);
            $subPageCss2Str .= " <a href='$nextPageUrl'>下一页</a> ";
//            $subPageCss2Str .= "<a href='$lastPageUrl'>尾页</a>";
        } else {

        }

        // $subPageCss2Str.='</div>';
        return $subPageCss2Str;
    }


    /**
     * 构造经典模式的分页
     * 知道样式
     * @return string
     */
    public function subPageCss1($suffix=null)
    {
        $suffix = $suffix!="no_suffix"?'.html':"";
        $subPageCss1Str = "";
        if ($this->current_page > 1) {
            $prewPageUrl = $this->subPage_link . ($this->current_page - 1).$suffix;
            $nextPageUrl = $this->subPage_link . ($this->current_page + 1).$suffix;
            $lastPageUrl = $this->subPage_link . $this->pageNums.$suffix;
            $subPageCss1Str .= "<a class='up' href='$prewPageUrl'><img src='/images/icon3.png'>上一页</a> ";
            $subPageCss1Str .= "<a class='now'>当前$this->current_page</a>";
            if ($this->current_page == $this->pageNums) {
                $subPageCss1Str .= "<a class='down' href='$lastPageUrl'>下一页<img src='/images/icon4.png'></a> ";
            } else {
                $subPageCss1Str .= "<a class='down' href='$nextPageUrl'>下一页<img src='/images/icon4.png'></a> ";
            }
        } else {
            $firstPageUrl = $this->subPage_link . "1".$suffix;
            $nextPageUrl = $this->subPage_link . ($this->current_page + 1).$suffix;
            $subPageCss1Str .= "<a class='up' href='$firstPageUrl'><img src='/images/icon3.png'>上一页</a> ";
            $subPageCss1Str .= "<a class='now'>当前$this->current_page</a>";
            $subPageCss1Str .= "<a class='down' href='$nextPageUrl'>下一页<img src='/images/icon4.png'></a> ";
        }
        return $subPageCss1Str;
    }

    /**
     * __destruct析构函数，当类不在使用的时候调用，该函数用来释放资源。
     */
    public function __destruct()
    {
        unset($this->each_disNums);
        unset($this->nums);
        unset($this->current_page);
        unset($this->sub_pages);
        unset($this->pageNums);
        unset($this->page_array);
        unset($this->subPage_link);
        unset($this->subPage_type);
    }
}

