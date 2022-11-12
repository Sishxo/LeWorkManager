<?php
    // settings
    header('Access-Control-Allow-Origin: *');
	ini_set("display_errors","Off");

    $PersonId = $_POST['PersonId'];
    $taskMoney = $_POST['taskMoney'];
    $taskPoint = $_POST['taskPoint'];

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
        $sql = "UPDATE `platform_user` SET  taskMoney = ".$taskMoney.", taskPoint = ".$taskPoint." WHERE id = ".$PersonId;
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