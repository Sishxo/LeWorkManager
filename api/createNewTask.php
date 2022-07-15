<?php
    // settings
    header('Access-Control-Allow-Origin: *');
	ini_set("display_errors","Off");

    $headPerson = $_POST['headPerson'];
    $createTime = $_POST['createTime'];
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
		$sql = "INSERT INTO `platform_project` (proName, projectType, headPerson, introduction, createTime, startTime, endTime, adminCheck, checkPerson, taskMoney, taskPoint) 
        VALUES ('".$taskTitle."', ".$taskType.", ".$headPerson.", '".$taskIntro."', '".$createTime."', '".$beginDateTime."', '".$endDateTime."', ".$adminCheck.", ".$checkPerson.", ".$taskMoney.", ".$taskPoint.")";
		// $res = $sql;
        // echo $sql;
		
		$result = mysqli_query($conn, $sql);
        // $result = true;
		
		if ($result)
		{
            $res['code'] = 200;
			$res['message'] = "新任务发布成功";
		}
		else
		{
			$res['code'] = 10;
			$res['message'] = "新任务数据库写入失败";
		}
	}

    echo json_encode($res, JSON_FORCE_OBJECT);
?>