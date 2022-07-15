<?php
    // settings
    header('Access-Control-Allow-Origin: *');
	ini_set("display_errors","Off");

    $taskId = $_POST['taskId'];
    $nowDateTime = $_POST['nowDateTime'];

    // 数据库
    include("mysqlConnect.php");
	if (!$conn)
	{
		$res['code'] = 0;
		$res['message'] = "连接错误";
	}
	else
	{
		$sql = "UPDATE `platform_project` SET realEndFlag = 1, realEndTime = '".$nowDateTime."' WHERE id=".$taskId;
		// echo $sql;
		
		$result = mysqli_query($conn, $sql);
		
		if ($result)
		{
			$res['code'] = 200;
			$res['message'] = "任务完成提交成功";
		}
		else
		{
			$res['code'] = 10;
			$res['message'] = "任务完成提交失败";
		}
	}

    echo json_encode($res, JSON_FORCE_OBJECT);
?>