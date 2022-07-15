<?php
    // settings
    header('Access-Control-Allow-Origin: *');
	ini_set("display_errors","Off");

    $taskId = $_POST['taskId'];
    $roleId = $_POST['roleId'];
    $userId = $_POST['userId'];
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
		$sql = "SELECT * FROM `platform_realSchedule` WHERE taskId=".$taskId." and roleId = ".$roleId." and userId = ".$userId;
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
                $sql = "SELECT checkRealSchedule FROM `platform_signUp` WHERE taskId=".$taskId." and roleId = ".$roleId." and userId = ".$userId;
                $result = mysqli_query($conn, $sql);

                if ($result)
                {
                    if ($row = mysqli_fetch_assoc($result))
                    {
                        $res['code'] = 200;
				        $res['message'] = $resMessage;
						$res['checkRealSchedule'] = $row['checkRealSchedule'];
                    }
                    else
                    {
                        $res['code'] = 30;
				        $res['message'] = "查询审核数据失败";
                    }
                }
                else
                {
                    $res['code'] = 30;
				    $res['message'] = "查询审核数据失败";
                }
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