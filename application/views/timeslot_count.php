<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <!-- <meta http-equiv="refresh" content="5;url=index2.php"> -->
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="stylesheet" href="/resource/css/style.css" type="text/css" />
    <script src="/resource/js/echarts.min.js"></script>
    <script src="/resource/js/jquery.min.js"></script>
    <script src="/resource/js/time.js"></script>
    <title>图书馆入馆人数监测</title>
    <style>
        body{
            background:url('/resource/images/E.jpg') no-repeat;
            width:100%;
            height:100%;
            background-size:100% 100%;
            filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='/resource/images/222.jpg)',sizingMethod='scale');
        }
    </style>
</head>
<body onload="getTime();">
	<div style="width:100%;height:30px;z-index:1;position:absolute;"><div id="date1"></div></div>
    <div id="no1"></div>
    <div id="perPercentPie" style="float:left;margin-left:30px"></div>

    <script type="text/javascript">
        height = $(window).height();         //可视窗口的高度
        width = $(window).width();
        $('#perPercentPie').css({'height': height,'width': width - 80});//设置高度属性
        $('#no1').css('width',width - 90);
        
        var deptPercentChart = echarts.init(document.getElementById('perPercentPie'));

		var hour = <?= json_encode($hour);?>;
		var data = <?= json_encode($timeslotData);?>;

        typeoption = option = {
            title: {
                text: '分时段入馆人数',
                x:'center',
                textStyle: {
                    fontSize: 26, //设置字体大小
                    color: '#080808'
                }
            },
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'cross'
                }
            },
            xAxis:  {
                type: 'category',
                boundaryGap: false,
                data: hour,
                axisLabel: {
                    textStyle: {
                        color: '#080808',
                        fontSize: 25,  //设置y轴字体
                        fontWeight: 600
                    }
                },
                axisLine: {
                    lineStyle: {
                        color: '#080808' 
                    }
                }
            },
            yAxis: {
                type: 'value',
                axisLabel: {
                    textStyle: {
                        color: '#080808',
                        fontSize: 25,  //设置y轴字体
                        fontWeight: 600
                    }
                },
                axisPointer: {
                    snap: true
                },
                axisLine: {
                    lineStyle: {
                        color: '#080808' 
                    }
                }
            },
            grid: {
                left: '4%',
                right: '4%',
                bottom: '95',
                containLabel: true
            },
            backgroundColor: 'rgba(0, 0, 0, 0)',
            series: [
                {
                    name:'入馆人数',
                    type:'line',
                    smooth: true,
                    label: {
                        normal: {
                            show:true,
                            position: 'insideTop',
                            offset: [0,-30],
                            textStyle:{
                                color:'#080808',
                                fontSize: 25,
                                fontWeight: 600
                            }, 
                        }
                    },
                    data: data,
                    symbolSize: 10,
                    itemStyle: {
                        normal: {
                            lineStyle: {
                                color: '#ff7f0e',
                                width: 6
                            }
                        }
                    }
                }
            ]
        };
        deptPercentChart.setOption(typeoption);

        $(function () {
            setTimeout(function () {
                location.href = '/index.php/ChartReport/index';
            }, 5000);
            var isOpenWelcome = <?= $this->config->item('is_open_welcome_page') ?>;
            if (isOpenWelcome == 1) {
                setTimeout('refresh()', 2000);
            }
        });
        function refresh(){
            $.ajax({
                type: 'post',
                async: true, //同步执行
                url: '/index.php/ChartReport/getLastVisitTime',
                dataType: 'json',
                success: function(data){
                    if (data.state == 1) {
                        window.location.replace('/index.php/Welcome/index');
                    }
                },
                error: function(){
                    alert('未知错误，请联系管理员！');
                }
            });
        };
    </script>
</body>
</html>