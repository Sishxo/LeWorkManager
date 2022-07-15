<?php
    // settings
    header('Access-Control-Allow-Origin: *');
	ini_set("display_errors","Off");

    $taskId = $_POST['taskId'];
    // $passwd = $_POST['passwd'];
    // $res = [$email, $passwd];

    // 数据库
    include("mysqlConnect.php");
	if (!$conn)
	{
		$res['code'] = 0;
		$res['message'] = "连接错误";
	}
	else
	{
		$sql = "SELECT * FROM `platform_role` WHERE taskId='".$taskId."'";
		// echo $sql;
		
		$result = mysqli_query($conn, $sql);
		
		if ($result)
		{
			$resMessage = array();
			while ($row = mysqli_fetch_assoc($result))
			{
				$res['code'] = 200;
				$resMessage[] = $row;
			}

			if (count($resMessage))
			{
				$res['code'] = 200;
				$res['message'] = $resMessage;
			}
			else
			{
				$res['code'] = 20;
				$res['message'] = "未查询到数据";
			}
		}
		else
		{
			$res['code'] = 10;
			$res['message'] = "查询失败";
		}
	}

    echo json_encode($res, JSON_FORCE_OBJECT);
?>