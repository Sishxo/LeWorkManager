<?php
    // settings
    header('Access-Control-Allow-Origin: *');
	ini_set("display_errors","Off");

    $taskId = $_POST['taskId'];
    $taskMoney = $_POST['taskMoney'];
    $taskPoint = $_POST['taskPoint'];
    $checkPerson = $_POST['userId'];

    // 数据库
    include("mysqlConnect.php");
	if (!$conn)
	{
		$res['code'] = 0;
		$res['message'] = "连接错误";
	}
	else
	{
        // 更新 审核
        $sql = "UPDATE `platform_project` SET adminCheck = 1, checkPerson = ".$checkPerson.", taskMoney = ".$taskMoney.", taskPoint = ".$taskPoint." WHERE id = ".$taskId;
        $result = mysqli_query($conn, $sql);
		
		if ($result)
		{
            $res['code'] = 200;
            $res['message'] = "任务审核成功";
		}
		else
		{
			$res['code'] = 10;
			$res['message'] = "任务审核失败";
		}        
	}
    echo json_encode($res, JSON_FORCE_OBJECT);
?>