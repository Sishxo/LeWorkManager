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
		$sql = "UPDATE `platform_realSchedule` SET adminCheck = 1 WHERE id = ".$realScheduleId;
		// $res = $sql;
        // echo $sql;
        
		$result = mysqli_query($conn, $sql);
        // $result = true;
		
		if ($result)
		{
            $res['code'] = 200;
		    $res['message'] = "实际进度真实性性确认成功";
		}
		else
		{
			$res['code'] = 10;
			$res['message'] = "实际进度真实性确认失败";
		}
	}

    echo json_encode($res, JSON_FORCE_OBJECT);
?>