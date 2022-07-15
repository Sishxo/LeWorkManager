<?php
    // settings
    header('Access-Control-Allow-Origin: *');
	ini_set("display_errors","Off");

    $taskId = $_POST['taskId'];
    $score = $_POST['score'];
    $markText = $_POST['markText'];
    $markPersonId = $_POST['markPersonId'];
    $markedPersonId = $_POST['markedPersonId'];
    $changeScoreTitle = $_POST['changeScoreTitle'];

    // 修改新增判断
    if ($changeScoreTitle == "评价打分")
    {
        // 新增
        $sql = "INSERT INTO `platform_score` (taskId, markPersonId, markedPersonId, score, markText) VALUE ("
                .$taskId.", ".$markPersonId.", ".$markedPersonId.", ".$score.", '".$markText."')";
    }
    else
    {
        // 更新
        $sql = "UPDATE `platform_score` SET score = ".$score.", markText = '".$markText."' WHERE taskId = ".$taskId." and markPersonId = ".$markPersonId." and markedPersonId = ".$markedPersonId;
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
		
		$result = mysqli_query($conn, $sql);
        // $result = true;
		
		if ($result)
		{
            $res['code'] = 200;
		    $res['message'] = "任务基础信息修改成功";
		}
		else
		{
			$res['code'] = 10;
			$res['message'] = "任务基础信息修改失败";
		}
	}

    echo json_encode($res, JSON_FORCE_OBJECT);
?>