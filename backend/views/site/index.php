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
	<div class="page-content" style="padding: 20px;">
	    <div class="index_right_con">
	        <div class="situation all_title">
	            <p class="title"><?php //echo $this->cyd->name_cn; ?>平台概况</p>
	            <div class="layui-row web_data box-shaodw">
	                <div class="webdata_card">
	                    <p class="evetwidtit layui-clear">数据统计 <span class="titmore"></span></p>
	                    <section>
	                        <ul>
	
	                        </ul>
	                    </section>
	                </div>
	            </div>
	        </div>
	
	    </div>
	</div>
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


<script src="/js/highcharts.js"></script>
<script src="/js/jquery.min.js"></script>
<script type="text/javascript">

    function getchart(type, date) {
        $.ajax({
            url: '/site/getchart',
            type: 'post',
            data: {type: type, date: date},
            dataType: 'json',
            success: function(res) {
                if (res.code == 1) {
                    $(".web_data #container").remove();
                    $(".web_data").append('<div id="container"></div>');
                    if (type == 'inquiry') {
                        getinquiryList(res.dateString, res.dataArr);
                    } else if (type == 'product') {
                        getproductArea(res.dateString, res.dataArr);
                    } else if (type == 'company') {
                        getindexCount(res.dateString, res.dataArr);
                    }
                }
            }
        })
    }

    $.ajax({
        url: '/site/getsitedata',
        type: 'post',
        dataType: 'json',
        success: function(res) {
            if (res.code) {
                var title = '';
                var content = '';
                var dates = '';
                var j = 0;
                $.each(res.site, function(i, v) {
                    if (j == 0) {
                        dates = i;
                        content += '<div class="layui-row evemonth_data active layui-col-space15">';
                        // title += '<li class="active">' + i + '</li>';
                    } else {
                        content += '<div class="layui-row evemonth_data layui-col-space15">';
                        // title += '<li>' + i + '</li>';
                    }
                    content += '<div class="layui-col-md3 evedata" onclick="getchart(\'inquiry\',\'' + i + '\')" date="' + i + '"><div class="wwbdata_con ys1"><p class="wwbdata_con_t">' + v.inquiry.total + '</p><p class="wwbdata_con_m">询盘数</p><p class="wwbdata_con_b">'+ v.inquiry.lastMonth +'月新增 ' + v.inquiry.this + '</p></div></div><div class="layui-col-md3 evedata" onclick="getchart(\'product\',\'' + i + '\')"><div class="wwbdata_con ys2"><p class="wwbdata_con_t">' + v.product.total + '</p><p class="wwbdata_con_m">产品数</p><p class="wwbdata_con_b">'+ v.product.lastMonth +'月新增 ' + v.product.this + '</p></div></div><div class="layui-col-md3 evedata" onclick="getchart(\'company\',\'' + i + '\')"><div class="wwbdata_con ys4"><p class="wwbdata_con_t">' + v.company.total + '</p><p class="wwbdata_con_m">入驻企业数</p><p class="wwbdata_con_b">'+ v.company.lastMonth +'月新增 ' + v.company.this + '</p></div></div></div>';
                    j++;
                });
                $(".webdata_card section ul").append(title);
                $(".webdata_card").after(content);
                getchart('inquiry', dates);
                $('.webdata_card section li').each(function(i) {
                    $(this).click(function() {
                        $(this).addClass('active').siblings().removeClass('active')
                        $('.evemonth_data').eq(i).addClass('active').siblings().removeClass('active')
                        var date = $('.evemonth_data').eq(i).children('.evedata').eq(0).attr('date');
                        getchart('inquiry', date);
                    })
                })
            }
        }
    })

    //询盘页数据切换
    $('.analyze_conchose span').each(function  (i) {
        $(this).click(function  () {
            $(this).addClass('active').siblings().removeClass('active')
            $('.analyze_item').eq(i).addClass('active').siblings('.analyze_item').removeClass('active')
        })
    })
    function getinquiryList(dateString, inquiry) {
        $('#container').highcharts({
            title: {
                text: '询盘数量报告',
                x: -20 //center
            },
            xAxis: {
                categories: dateString
            },
            yAxis: {
                min: 0,
                title: {
                    text: '',
                    x: -10,
                    rotation: 90
                },
                plotLines: [
                    {
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }
                ]
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: true
                },
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [
                {
                    name: '询盘数',
                    data: inquiry
                }
            ]
        });
    }

    //产品数量
    function getproductArea(dateString, product) {
        $('#container').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: '产品数量报告'
            },
            xAxis: {
                categories: dateString
            },
            yAxis: {
                min: 0,
                title: {
                    text: '',
                    x: -10,
                    rotation: 90
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y} 个</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [
                {
                    name: '产品数',
                    data: product
                }
            ]
        });
    }

    //企业入驻报告
    function getindexCount(dateString, indexIncPage) {
        $('#container').highcharts({
            title: {
                text: '企业入驻报告',
                x: -20 //center
            },
            xAxis: {
                categories: dateString
            },
            yAxis: {
                min: 0,
                title: {
                    text: '',
                    x: -10,
                    rotation: 90
                },
                plotLines: [
                    {
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }
                ]
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: true
                },
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [
                {
                    name: '企业入驻数',
                    data: indexIncPage
                }
            ]
        });
    }



</script>

<link rel="stylesheet" type="text/css" href="/home.css" />
<link rel="stylesheet" type="text/css" href="/layui/css/layui.css" />

