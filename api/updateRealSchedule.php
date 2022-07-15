<?php
    // settings
    header('Access-Control-Allow-Origin: *');
	ini_set("display_errors","Off");

    $realScheduleId = $_POST['realScheduleId'];
    $taskId = $_POST['taskId'];
    $roleId = $_POST['roleId'];
    $userId = $_POST['userId'];
    $realDateTime = $_POST['realDateTime'];
    $childTaskId = $_POST['childTaskId'];
    $realPercentText = $_POST['realPercent'];
    $realText = $_POST['realText'];

    // 字符串处理
    $realPercent = rtrim($realPercentText, "%");

    // echo $planPercent;

    // 数据库
    include("mysqlConnect.php");
	if (!$conn)
	{
		$res['code'] = 0;
		$res['message'] = "连接错误";
	}
	else
	{        
		$sql = "UPDATE `platform_signUp` SET checkPlanSchedule = 0 WHERE taskId = ".$taskId." and roleId = ".$roleId." and userId = ".$userId;
		// $res = $sql;
        // echo $sql;
        
		$result = mysqli_query($conn, $sql);
        // $result = true;
		
		if ($result)
		{
            // 新增
            $sql = "UPDATE `platform_realSchedule` SET taskId = ".$taskId.", roleId = ".$roleId.", userId = ".$userId.", realDateTime = '".$realDateTime."', childTaskId = "
                    .$childTaskId.", realPercent = ".$realPercent.", realText = '".$realText."' WHERE id = ".$realScheduleId;
            // $res = $sql;
            
            $result = mysqli_query($conn, $sql);
            
            if ($result)
            {
                $res['code'] = 200;
		        $res['message'] = "实际进度修改成功";
            }
            else
            {
                $res['code'] = 20;
			    $res['message'] = "实际进度修改失败";
            }
		}
		else
		{
			$res['code'] = 10;
			$res['message'] = "实际进度审核信息更新失败";
		}
	}

    echo json_encode($res, JSON_FORCE_OBJECT);
?>