<?php
    // settings
    header('Access-Control-Allow-Origin: *');
	ini_set("display_errors","Off");

    $realScheduleId = $_POST['realScheduleId'];

    // 数据库
    include("mysqlConnect.php");
	if (!$conn)
	{
		$res['code'] = 0;
		$res['message'] = "连接错误";
	}
	else
	{
		$sql = "DELETE FROM `platform_realSchedule` WHERE id=".$realScheduleId;
		// echo $sql;
		
		$result = mysqli_query($conn, $sql);
		
		if ($result)
		{
			$res['code'] = 200;
			$res['message'] = "任务领取成功";
		}
		else
		{
			$res['code'] = 10;
			$res['message'] = "删除失败";
		}
	}

    echo json_encode($res, JSON_FORCE_OBJECT);
?>