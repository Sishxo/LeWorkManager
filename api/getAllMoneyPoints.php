<?php
    // settings
    header('Access-Control-Allow-Origin: *');
	ini_set("display_errors","Off");

    /* 用户信息列表获取 */
    $page = $_POST['page'];
    $length = $_POST['length'];
    $checkScoreCondition = $_POST['checkScoreCondition'];
    $userPermission = $_POST['userPermission'];

	// 计算
    $startNum = ($page - 1) * $length;

    // 数据库
    include("mysqlConnect.php");
	if (!$conn)
	{
		$res['code'] = 0;
		$res['message'] = "连接错误";
	}
    else if($userPermission > 1)
    {
        $res['code'] = 30;
		$res['message'] = "权限不足";
    }
	else
	{
        $sql = "SELECT * FROM `platform_user` WHERE (name LIKE '%".$checkScoreCondition."%') AND (permit < 5) LIMIT ".$startNum.", ".$length;
		// echo $sql;
		
		$result = mysqli_query($conn, $sql);
		
		if ($result)
		{
            $resMessage = array();
			while ($row = mysqli_fetch_assoc($result))
			{
                $resMessage[] = $row;
			}
			
            $sqlNew = "SELECT COUNT(*) AS total FROM `platform_user` WHERE (name LIKE '%".$checkScoreCondition."%') AND (permit < 5)";

            $resultNew = mysqli_query($conn, $sqlNew);
			
			if ($resultNew)
			{
				if ($rowNew = mysqli_fetch_assoc($resultNew))
				{
					$rowLength = $rowNew['total'];
				}
			}

            if (count($resMessage))
            {
                $res['code'] = 200;
                $res['listLength'] = $rowLength;
                $res['message'] = $resMessage;
            }
            else
			{
				$res['code'] = 20;
                $res['listLength'] = 0;
				$res['message'] = "未查询到数据";
			}
		}
		else
		{
			$res['code'] = 10;
            $res['listLength'] = 0;
			$res['message'] = "查询失败";
		}
	}

    echo json_encode($res, JSON_FORCE_OBJECT);
?>