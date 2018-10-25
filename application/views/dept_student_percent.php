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
    <div id="typePercentPie" style="float:left"></div>

    <script type="text/javascript">
        height = $(window).height();         //可视窗口的高度
        width = $(window).width();
        $('#perPercentPie').css({'height': height,'width': width * 0.59});//设置高度属性
        $('#typePercentPie').css({'height': height,'width': width * 0.39 - 42});//设置高度属性
        $('#no1').css('width',width - 90);
        
        var deptPercentChart = echarts.init(document.getElementById('perPercentPie'));
        var deptPercentArrCurrent = <?php echo json_encode($deptResultItem); ?>;
        typeoption = {
            title : {
                text: '实时学院入馆人数占比',
                x: 'center',
                textStyle: {
                    fontSize: 35, //设置字体大小
                    color: '#080808'
                },
                top: '40'
            },
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} 人 ({d} %)"
            },
            backgroundColor: 'rgba(0, 0, 0, 0)',
            series : [
                {
                    name: '访问人数',
                    type: 'pie',
                    // minAngle: 10,// 最小角度
                    radius : '53%',
                    animationType: 'scale',
                    animationEasing: 'exponentialInOut',
                    animationDuration: 1500,
                    center: ['50%', '50%'],
                    data: deptPercentArrCurrent,
                    itemStyle: {
                        normal: {
                            color: function (params) {
                                // 每列设置不同的颜色
                                var colorList = [
                                        '#228fbd', '#BA55D3', '#20B2AA', '#e08031', '#c7ceb2', '#7c8489', '#ee827c', '#c8c8a9', '#83af9b',
                                        '#c97586', '#495a80', '#5ca7ba', '#199475', '#e36868', '#376956', '#b57795', '#6e8631', '#94b38f'
                                    ];
                                return colorList[params.dataIndex];
                            }
                        }
                    },
                    label: {
                        normal: {
                            textStyle: {
                                fontSize: 25, //设置字体大小
                                color: '#CD3700',
                                fontWeight: 600
                            },
                            formatter: "{b} {d}%"
                        }
                    },
                    labelLine: {
                        normal: {
                            lineStyle: {
                                color: '#F8F1F0'
                            }
                        }
                    }
                }
            ]
        };
        deptPercentChart.setOption(typeoption);

        var typePercentCurrentChart = echarts.init(document.getElementById('typePercentPie'));
        var typeArr = <?= json_encode($typeArr); ?>;
        var typePercentArr = <?= json_encode($studentResultItem); ?>;

        typepercentoption = {
            title: {
                text: "实时学生入馆人数占比",  
                x: "center",
                textStyle: {
                    fontSize: 35, //设置字体大小
                    color: '#080808'
                },
                top: '40'
            }, 
            color: ['#3398DB'], //可选色：'#86D560', '#AF89D6', '#59ADF3', '#FF999A', '#FFCC67'
            tooltip : {
                trigger: 'axis',
                axisPointer : {
                    type : 'shadow'
                }
            },
            grid: {
                top: '100',
                left: '1%',
                right: '4%',
                bottom: '100',
                containLabel: true
            },
            xAxis : [
                {
                    type : 'category',
                    data : typeArr,
                    axisTick: {
                        alignWithLabel: true
                    },
                    axisLabel: {
                        textStyle: {
                            fontSize: 20, //设置字体大小
                            color: '#CD3700',
                            fontWeight: 600
                        },
                    },
                    axisLine: {
                        lineStyle: {
                            color: '#000' 
                        }
                    }
                }
            ],
            yAxis : [
                {
                    type : 'value',
                    axisLabel: {
                        textStyle: {
                            fontSize: 25, //设置字体大小
                            color: '#CD3700',
                            fontWeight: 600
                        },
                    },
                    axisLine: {
                        lineStyle: {
                            color: '#000' 
                        }
                    }
                }
            ],
            backgroundColor: 'rgba(0, 0, 0, 0)',
            series : [
                {
                    name:'人数',
                    type:'bar',
                    barWidth: '60%',
                    data: typePercentArr,
                    label: {
                        normal: {
                            show: true,
                            position: 'top',
                            textStyle: {
                                color: '#F8F1F0',
                                fontSize: 18,  //设置x轴字体
                                fontWeight: 600
                            }
                        }
                    },
                }
            ]
        };
        typePercentCurrentChart.setOption(typepercentoption);
        
        $(function () {
            setTimeout(function () {
                location.href = '/index.php/ChartReport/timeslot';
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