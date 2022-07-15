<?php
    // settings
    header('Access-Control-Allow-Origin: *');
	ini_set("display_errors","Off");

    $taskId = $_POST['taskId'];
    $roleId = $_POST['roleId'];
    $userId = $_POST['userId'];

    // 数据库
    include("mysqlConnect.php");
	if (!$conn)
	{
		$res['code'] = 0;
		$res['message'] = "连接错误";
	}
	else
	{        
		$sql = "UPDATE `platform_signUp` SET checkPlanSchedule = 1 WHERE taskId = ".$taskId." and roleId = ".$roleId." and userId = ".$userId;
		// $res = $sql;
        // echo $sql;
        
		$result = mysqli_query($conn, $sql);
        // $result = true;
		
		if ($result)
		{
            $res['code'] = 200;
		    $res['message'] = "计划完整性确认成功";
		}
		else
		{
			$res['code'] = 10;
			$res['message'] = "计划完整性确认失败";
		}
	}

    echo json_encode($res, JSON_FORCE_OBJECT);
?>