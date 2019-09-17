/**
 * 
 */
$(function(){
	
	var str = '<div class="modal bounceIn animated" id="del" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">' + 
				'<div class="modal-dialog modal-sm">'+
				  '<div class="modal-content">'+
				      '<div class="modal-body modal-del">'+
				        '<div class="post text-center">数据删除无法恢复，确定删除？</div></div>'+
					      '<div class="modal-footer">'+
					        '<button type="button" class="btn btn-primary btn-sm J-del-true">删除</button>'+
					        '<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">取消</button>'+        
					      '</div>'+
				    '</div>'+
				  '</div>'+
				'</div>';

	$("body").append(str);
	
	var id = 0;
	$(".J-del").click(function(){
		id = $(this).attr("data-id");
	})

	$(".J-del-true").click(function(){
		var url = $("#J-del-url").val();
		$.ajax(url,{
			type:"post",
			dataType:"json",
			data:{ id:id },
			success:function(data){
				if(data.status==false){
					$("#myAlert").show().append(data.msg);
				}else{
					window.location.reload();					
				}
			}
		})
	});


	$.ajax({
        url: '/site/get-data',
        // type: 'post',
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
                    } else {
                        content += '<div class="layui-row evemonth_data layui-col-space15">';
                    }
                    content += '<div class="layui-col-md3 evedata" onclick="getchart(\'User\',\'' + i + '\')" date="' + i + '"><div class="wwbdata_con ys1"><p class="wwbdata_con_t">' + v.User.total + '</p><p class="wwbdata_con_m">用户数</p><p class="wwbdata_con_b">'+ v.User.lastMonth +'月新增 ' + v.User.this + '</p></div></div><div class="layui-col-md3 evedata" onclick="getchart(\'Blog\',\'' + i + '\')"><div class="wwbdata_con ys2"><p class="wwbdata_con_t">' + v.Blog.total + '</p><p class="wwbdata_con_m">博客文章数</p><p class="wwbdata_con_b">'+ v.Blog.lastMonth +'月新增 ' + v.Blog.this + '</p></div></div><div class="layui-col-md3 evedata" onclick="getchart(\'Fans\',\'' + i + '\')"><div class="wwbdata_con ys3"><p class="wwbdata_con_t">' + v.Fans.total + '</p><p class="wwbdata_con_m">微信粉丝数</p><p class="wwbdata_con_b">'+ v.Fans.lastMonth +'月新增 ' + v.Fans.this + '</p></div></div><div class="layui-col-md3 evedata" onclick="getchart(\'Link\',\'' + i + '\')"><div class="wwbdata_con ys4"><p class="wwbdata_con_t">' + v.Link.total + '</p><p class="wwbdata_con_m">友情链接数</p><p class="wwbdata_con_b">'+ v.Link.lastMonth +'月新增 ' + v.Link.this + '</p></div></div></div>';
                    j++;
                });
                $(".webdata_card section ul").append(title);
                $(".webdata_card").after(content);
                getchart('User', dates);
                console.log(dates);
                // $('.webdata_card section li').each(function(i) {
                //     $(this).click(function() {
                //         $(this).addClass('active').siblings().removeClass('active')
                //         $('.evemonth_data').eq(i).addClass('active').siblings().removeClass('active')
                //         var date = $('.evemonth_data').eq(i).children('.evedata').eq(0).attr('date');
                //         getchart('User', date);
                //     })
                // })
            }
        }
    })


})


	function getchart(type, date) {
        $.ajax({
            url: '/site/get-chart',
            type: 'post',
            data: {type: type, date: date},
            dataType: 'json',
            success: function(res) {
                if (res.code == 1) {
                    $(".web_data #container").remove();
                    $(".web_data").append('<div id="container"></div>');
                    if (type == 'User') {
                        getUserList(res.dateString, res.dataArr);
                    } else if (type == 'Blog') {
                        getBlogArea(res.dateString, res.dataArr);
                    } else if (type == 'Fans') {
                        getFansArea(res.dateString, res.dataArr);
                    } else if (type == 'Link') {
                        getLinkCount(res.dateString, res.dataArr);
                    }
                }
            }
        })
    }


    function getUserList(dateString, user) {
        $('#container').highcharts({
            title: {
                text: '用户数量报告',
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
                    name: '用户数',
                    data: user
                }
            ]
        });
    }

    //博客文章数
    function getBlogArea(dateString, blog) {
        $('#container').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: '博客数量报告'
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
                    name: '文章数',
                    data: blog
                }
            ]
        });
    }

    //funs数量报告
    function getFansArea(dateString, fans) {
        $('#container').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'fans数量报告'
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
                    name: 'fans数',
                    data: fans
                }
            ]
        });
    }

    //Link数
    function getLinkCount(dateString, link) {
        $('#container').highcharts({
            title: {
                text: 'Link数',
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
                    name: 'Link数',
                    data: link
                }
            ]
        });
    }
