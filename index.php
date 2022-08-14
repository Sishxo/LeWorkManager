<!DOCTYPE html>
<html lang="zh" style="height:100%">

<head>

    <!-- 网页标题 -->
    <title>数字孪生工作平台</title>

    <?php include('module/header.php'); ?>

    <? include('module/headerJs.php'); ?>

</head>

<body id="page-top" style="height:100%">

    <!-- Page Wrapper -->
    <div id="wrapper" style="height:100%">

        <?php // include('module/nav-left.php'); ?>
        <div id="nav-left" style="height:100%"> </div>

        <!-- 主页面 Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php // include('module/nav-top.php'); ?>
                <div id="nav-top"></div>

                <!-- 主页面 未登录 Begin Page Content -->
                <div id="mainPageUnlogin" class="container-fluid" align="center" style="display:block">

                    <!-- Page Heading -->
                    <div style="height:200px"></div>
                    <h1 class="h3 mb-4 text-gray-800">欢迎使用数字孪生工作平台V2.1.1 Alpha！</h1>
                    <h1 class="h3 mb-4 text-gray-800">请先登录数字孪生工作平台，然后继续进行下一步操作，谢谢配合！</h1>
                    <a class="h3 mb-4 text-gray-800" href="logreg/login.html">立即登录</a>

                </div>

                <!-- 主页面 已登录 Begin Page Content -->
                <div id="mainPageLogin" class="container-fluid" style="display:none">
                    <!-- Page Heading -->
                    <div style="height:200px"></div>
                    <div style="text-align:center">
                        <h1 class="h3 mb-4 text-gray-800"><font color="black">正在加载中！</font></h1>
                    </div>
                </div>

            </div>
            <!-- End of Main Content -->

            <?php include('module/footer.php'); ?>

        </div>
        <!-- 主页面结束 End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <?php include('module/goToTop.php'); ?>

    <?php include('module/logout.php'); ?>

    <?php // include('module/footerJs.php'); ?>

</body>

</html>