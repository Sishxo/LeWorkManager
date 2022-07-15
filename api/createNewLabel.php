<?php
    // settings
    header('Access-Control-Allow-Origin: *');
	ini_set("display_errors","Off");

    $taskId = $_POST['taskId'];
    // $labelId = $_POST['labelId'];
    $label = $_POST['label'];
    $adminCheck = $_POST['adminCheck'];

    // 数据库
    include("mysqlConnect.php");
	if (!$conn)
	{
		$res['code'] = 0;
		$res['message'] = "连接错误";
	}
	else
	{        
		// 标签
        $sql = "INSERT INTO `platform_projectLabel` (label, taskId) VALUE ('".$label."', '".$taskId."')";
        // $res = $sql;

        $result = mysqli_query($conn, $sql);
            
        if ($result)
        {
            $res['code'] = 200;
		    $res['message'] = "标签新增成功";
        }
        else
        {
            $res['code'] = 20;
		    $res['message'] = "标签新增失败";
        }
	}

    echo json_encode($res, JSON_FORCE_OBJECT);
?>