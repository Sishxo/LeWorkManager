<?php
    // settings
    header('Access-Control-Allow-Origin: *');
	ini_set("display_errors","Off");

    $taskId = $_POST['taskId'];
    $roleId = $_POST['roleId'];
    $userId = $_POST['userId'];
    $planDateTime = $_POST['planDateTime'];
    $childTaskId = $_POST['childTaskId'];
    $planPercentText = $_POST['planPercent'];
    $planText = $_POST['planText'];

    // 字符串处理
    $planPercent = rtrim($planPercentText, "%");

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
            $sql = "INSERT INTO `platform_planSchedule` (taskId, roleId, userId, planDateTime, childTaskId, planPercent, planText) VALUE ("
                    .$taskId.", ".$roleId.", ".$userId.", '".$planDateTime."', ".$childTaskId.", ".$planPercent.", '".$planText."')";
            // $res = $sql;
            
            $result = mysqli_query($conn, $sql);
            
            if ($result)
            {
                $res['code'] = 200;
		        $res['message'] = "计划新增成功";
            }
            else
            {
                $res['code'] = 20;
			    $res['message'] = "计划新增失败";
            }
		}
		else
		{
			$res['code'] = 10;
			$res['message'] = "计划审核信息更新失败";
		}
	}

    echo json_encode($res, JSON_FORCE_OBJECT);
?>