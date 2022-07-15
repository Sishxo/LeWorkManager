<?php
    // settings
    header('Access-Control-Allow-Origin: *');
	ini_set("display_errors","Off");

    $taskId = $_POST['taskId'];
    $taskTitle = $_POST['taskTitle'];
    $taskType = $_POST['taskType'];
    $taskMoney = $_POST['taskMoney'];
    $taskPoint = $_POST['taskPoint'];
    $adminCheck = $_POST['adminCheck'];
    $taskIntro = $_POST['taskIntro'];
    $beginDateTime = $_POST['beginDateTime'];
    $endDateTime = $_POST['endDateTime'];

    // $res = [$headPerson, $createTime, $taskTitle, $taskType, $taskLabel, $adminCheck, $phone, $taskIntro, $beginDateTime, $endDateTime];

    // 复核人判断
    if ($adminCheck == 1)
    {
        // 无需复核
        $checkPerson = -1;
    }
    else
    {
        // 需要复核
        $checkPerson = 0;
    }

    // 数据库
    include("mysqlConnect.php");
	if (!$conn)
	{
		$res['code'] = 0;
		$res['message'] = "连接错误";
	}
	else
	{
        // 先查询 泺研币 和 泺学分 是否被修改
        $sql = "SELECT * FROM `platform_project` WHERE id = ".$taskId;
        $result = mysqli_query($conn, $sql);
		
		if ($result)
		{
            if ($row = mysqli_fetch_assoc($result))
            {
                if (($row['taskMoney'] == $taskMoney) && ($row['taskPoint'] == $taskPoint))
                {
                    $sql = "UPDATE `platform_project` SET proName = '".$taskTitle."', projectType = ".$taskType.
                    ", introduction = '".$taskIntro."', startTime = '".$beginDateTime."', endTime = '".$endDateTime.
                    "' WHERE id = ".$taskId;
                }
                else
                {
                    $sql = "UPDATE `platform_project` SET proName = '".$taskTitle."', projectType = ".$taskType.", adminCheck = ".$adminCheck.
                    ", introduction = '".$taskIntro."', startTime = '".$beginDateTime."', endTime = '".$endDateTime.
                    "', checkPerson = ".$checkPerson.", taskMoney = ".$taskMoney.", taskPoint = ".$taskPoint." WHERE id = ".$taskId;
                }
                
                $result = mysqli_query($conn, $sql);
                
                if ($result)
                {
                    $res['code'] = 200;
                    $res['message'] = "任务基础信息修改成功";
                }
                else
                {
                    $res['code'] = 20;
                    $res['message'] = "任务基础信息修改失败";
                }
            }
            else
            {
                $res['code'] = 10;
			    $res['message'] = "任务基础信息查询失败";
            }
		}
		else
		{
			$res['code'] = 10;
			$res['message'] = "任务基础信息查询失败";
		}        
	}
    echo json_encode($res, JSON_FORCE_OBJECT);
?>