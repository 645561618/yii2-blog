<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = '后台首页';
$this->params['menu_name'] = '';
$this->params['three_menu'] = '';
$this->params['sidebar_name'] = '';

?>
<style>
	#main{
	}
</style>
<?php if($id==1){ ?>
        <div class="col-sm-3" style="margin-bottom:20px;">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-primary pull-right">系统管理</span>
                    <h5>后台用户</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><a href="/manager/admin"><?=$userNums?></a></h1>
                    <small>位</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3" style="margin-bottom:20px;">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-danger pull-right">文章管理</span>
                    <h5>博客文章</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><a href="/blog/article-list"><?=$BlogArticleNums?></a></h1>
                    <small>篇</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3" style="margin-bottom:20px;">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-primary pull-right">微信粉丝</span>
                    <h5>关注粉丝</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><a href="/wechat/user-info"><?=$WxFollowNums?></a></h1>
                    <small>位</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3" style="margin-bottom:20px;">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-danger pull-right">博客友链</span>
                    <h5>友情链接</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><a href="/blog/links-list"><?=$LinksNums?></a></h1>
                    <small>个</small>
                </div>
            </div>
        </div>
	
	<!-- 为 ECharts 准备一个具备大小（宽高）的 DOM -->
        <div id="main" style="width: 600px;height:400px;"></div>

<?php }else{?>

<div class="site-index">

    <div class="jumbotron">
        <h1>后台管理系统!</h1>



    </div>


</div>
<?php }?>

<script src="https://cdn.bootcss.com/echarts/4.2.0-rc.2/echarts.min.js"></script>

<script type="text/javascript">
	function fetchData(cb) {
    		// 通过 setTimeout 模拟异步加载
    		setTimeout(function () {
        		cb({
            			categories: ["衬衫","羊毛衫","雪纺衫","裤子","高跟鞋","袜子"],
            			data: [5, 20, 36, 10, 10, 20]
        		});
    		}, 1000);
	}


        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('main'));

        // 指定图表的配置项和数据
	option = {
	    title: {
		text: '博客文章每天浏览数',
	    },
	    tooltip: {
		trigger: 'axis'
	    },
	    toolbox: {
		show: true,
		feature: {
		    saveAsImage: {}
		}
	    },
	    xAxis:  {
		//type: 'category',
		//boundaryGap: false,
		//data: ['周一','周二','周三','周四','周五','周六','周日']
		data:[]
	    },
	    yAxis: {
		//type: 'value',
		//axisLabel: {
		//    formatter: '{value} '
		//},
		splitNumber:3//间隔
	    },
	    series: [
		{
		    name:'浏览数',
		    type:'line',
		    smooth:true,
		    //data:[3, 9, 3, 6, 11, 18, 10],
		    data:[],
		    itemStyle: {  
			normal: {   //颜色渐变函数 前四个参数分别表示四个位置依次为左、下、右、上
			    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{ 

				offset: 0,
				color: 'rgba(80,141,255,0.39)'
			    }, {
				offset: .34,
				color: 'rgba(56,155,255,0.25)'
			    },{
				offset: 1,
				color: 'rgba(38,197,254,0.00)'
			    }]),
			    lineStyle: {// 系列级个性化折线样式  
				width: 2,  
				type: 'solid',  
				 color: "#4fd6d2"
			    }
			},  
		    },//线条样式 
		    areaStyle: {
				
		    }
		}
	    ]
	};	

        // 使用刚指定的配置项和数据显示图表。
        //myChart.setOption(option);
	fetchData(function (data) {
	    myChart.setOption({
		xAxis: {
		    data: data.categories
		},
		series: [{
		    // 根据名字对应到相应的系列
		    name: '浏览数',
		    data: data.data
		}]
	    });
	});
    </script>
