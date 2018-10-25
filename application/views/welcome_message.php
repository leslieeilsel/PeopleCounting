<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="/favicon.ico">
    <script src="/resource/js/echarts.min.js"></script>
    <script src="/resource/js/jquery.min.js"></script>
    <title>欢迎页</title>
</head>
<style>
    .main {
        text-align: center;
        /*让div内部文字居中*/
        position: absolute;
        top: 180px;
        left: 0;
        right: 0;
        bottom: 100px;
        font-size: 35px;
    }
    body {
        background-color: #EE2C2C;
    }
    h1 {
        font-size: 120px;
        color: #FFFF00;
    }
    h2 {
        font-size: 60px;
        color: #0F0F0F;
    }
</style>

<body>
    <div class="main">
        <h1>您是今日第<?= $countToday; ?> 位</h1>
        <h2>本年入馆人数 :<?= $countAll; ?>人</h2>
    </div>
</body>

</html>
<script>
    setTimeout(function () {
        location.href = document.referrer;
    }, 5000);
</script>
