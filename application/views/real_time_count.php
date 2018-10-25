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
    <div id="perCountBar" style="float:left;margin-left:30px"></div>

    <script type="text/javascript">
        height = $(window).height();                // 可视窗口的高度
        width = $(window).width();
        $('#perCountBar').css('height',height - 38);// 设置高度属性
        $('#perCountBar').css('width',width - 80);
        $('#no1').css('width',width - 90);
        
        var deptCountChart = echarts.init(document.getElementById('perCountBar'));

        var deptArr = <?= json_encode($deptName); ?>;
        var deptCountArr = <?= json_encode($deptValue); ?>;
        
        var deptoption = {
            title : {
                text: '实时入馆人数',
                x:'center',
                textStyle: {
                    fontSize: 35, //设置字体大小
                    color: '#080808'
                }
            },
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow'
                },
            },
            grid: {
                left: '4%',
                right: '4%',
                bottom: '55',
                containLabel: true
            },
            yAxis: [{
                type: 'category',
                data: deptArr,
                axisLabel: {
                    textStyle: {
                        color: '#1e1e1e',
                        fontSize: 25,  //设置y轴字体
                        fontWeight: 600
                    }
                },
                axisTick: {
                    alignWithLabel: true
                },
                axisLine: {
                    lineStyle: {
                        color: '#F8F1F0' 
                    }
                }
            }],
            xAxis: [{
                type: 'value',
                axisLabel: {
                    show: true,
                    textStyle: {
                        color: '#F8F1F0',
                        fontSize:18,  //设置y轴字体
                        fontWeight: 600
                    }
                },
                axisLine: {
                    lineStyle: {
                        color: '#F8F1F0' 
                    }
                }
            }],
            backgroundColor: 'rgba(150, 150, 150, 0.0)',
            series: [{
                name: '访问人数',
                type: 'bar',
                animationDuration: 1500,
                animationEasing: 'exponentialInOut',
                data: deptCountArr,
                label: {
                    normal: {
                        show: true,
                        position: 'insideRight',
                        textStyle: {
                            color: 'white',
                            fontSize: 25
                        }
                    }
                },
                itemStyle: {
                    normal: {
                        color: function (params) {
                            // 每列设置不同的颜色
                            var colorList = [
                                '#18569d', '#1a5d9d', '#1d649d', '#1f6b9d', '#22729e', '#24799e', '#27809e', '#29879f', '#2c8e9f',
                                '#2e959f', '#319c9f', '#33a3a0', '#36aaa0', '#38b1a0', '#3bb8a1', '#3dbfa1', '#40c6a1', '#43cea2'
                            ];
                            return colorList[params.dataIndex];
                        }
                    }
                }
            }]
        }
        deptCountChart.setOption(deptoption);
        
        $(function () {
            setTimeout(function () {
                location.href = '/index.php/ChartReport/percent';
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