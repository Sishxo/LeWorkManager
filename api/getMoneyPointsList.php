<?php
    // settings
    header('Access-Control-Allow-Origin: *');
	ini_set("display_errors","Off");

    /* 用户信息列表获取 */
    $moneyCondition = $_POST['moneyCondition'];
    $page = $_POST['page'];
	$length = $_POST['length'];
    $startDateTime = $_POST['startDateTime'];
    $endDateTime = $_POST['endDateTime'];
    $userId = $_POST['userId'];

	// 计算
    $startNum = ($page - 1) * $length;

    // 数据库
    include("mysqlConnect.php");
	if (!$conn)
	{
		$res['code'] = 0;
		$res['message'] = "连接错误";
	}
	else
	{
        $sqlInSelect = "SELECT id FROM `platform_project` WHERE proName LIKE '%".$moneyCondition."%'";
		$sql = "SELECT * FROM `platform_moneyPointConfirm` WHERE ((userId = ".$userId.") AND (insertDateTime >= '"
            .$startDateTime."') AND (insertDateTime <= '".$endDateTime."' AND (taskId IN (".$sqlInSelect.")))) ORDER BY insertDateTime DESC LIMIT ".$startNum.", ".$length;
		// echo $sql;
		
		$result = mysqli_query($conn, $sql);
		
		if ($result)
		{
            $resMessage = array();
			while ($row = mysqli_fetch_assoc($result))
			{
                // 获取任务信息
                $sqlNew = "SELECT * FROM `platform_project` WHERE id = ".$row['taskId'];
                $resultNew = mysqli_query($conn, $sqlNew);

                if ($resultNew)
                {
                    if ($rowNew = mysqli_fetch_assoc($resultNew))
                    {
                        $row['proName'] = $rowNew['proName'];
                        if ($rowNew['realEndTime'] == '0000-00-00 00:00:00')
                        {
                            $row['realEndTime'] = '任务未完成';
                        }
                        else
                        {
                            $row['realEndTime'] = $rowNew['realEndTime'];
                        }
                    }
                    else
                    {
                        $row['proName'] = '任务不存在';
                        $row['realEndTime'] = '--';
                    }
                }
                else
                {
                    $row['proName'] = '任务不存在';
                    $row['realEndTime'] = '--';
                }

                // 获取任务信息
                $sqlNew = "SELECT * FROM `platform_user` WHERE id = ".$row['checkAdminId'];
                $resultNew = mysqli_query($conn, $sqlNew);

                if ($resultNew)
                {
                    if ($rowNew = mysqli_fetch_assoc($resultNew))
                    {
                        $row['adminName'] = $rowNew['name'];
                    }
                    else
                    {
                        $row['adminName'] = '复核人不存在';
                    }
                }
                else
                {
                    $row['adminName'] = '复核人不存在';
                }

                $resMessage[] = $row;
			}
			
            $sqlNew = "SELECT COUNT(*) AS total FROM `platform_moneyPointConfirm` WHERE ((userId = ".$userId.") AND (insertDateTime >= '"
                .$startDateTime."') AND (insertDateTime <= '".$endDateTime."' AND (taskId IN (".$sqlInSelect."))))";

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