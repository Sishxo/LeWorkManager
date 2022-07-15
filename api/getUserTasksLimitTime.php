<?php
    // settings
    header('Access-Control-Allow-Origin: *');
	ini_set("display_errors","Off");

    // 传参
    $userId = $_POST['userId'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];
	$page = $_POST['page'];
	$length = $_POST['length'];
	$allUsersTasksInfoCondition = $_POST['allUsersTasksInfoCondition'];

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
        // echo $nowDateTime;
		$sqlInSelect = "SELECT taskId FROM `platform_signUp` WHERE ((userId = ".$userId.") AND (signUpTime >= '".$startTime."') AND (signUpTime <= '".$endTime."'))";
		$sql = "SELECT * FROM `platform_project` WHERE ((id IN (".$sqlInSelect.")) and (proName LIKE '%"
			.$allUsersTasksInfoCondition."%')) ORDER BY createTime DESC LIMIT ".$startNum.", ".$length;
		// echo $sql;
		
		$result = mysqli_query($conn, $sql);
		
		if ($result)
		{
            $resMessage = array();
			while ($row = mysqli_fetch_assoc($result))
			{
				// 读取最高进度
				$maxPercent = 0;
				
				$sqlNew = "SELECT * FROM `platform_realSchedule` WHERE taskId = ".$row['id']." AND childTaskId = 0";

				$resultNew = mysqli_query($conn, $sqlNew);

				if ($resultNew)
				{
					while ($rowNew = mysqli_fetch_assoc($resultNew))
					{
						if ($rowNew['realPercent'] > $maxPercent)
						{
							$maxPercent = $rowNew['realPercent'];
						}
					}
				}
				
				if ($row['realEndFlag'] == 1)
				{
					$row['maxPercent'] = 100;
				}
				else
				{
					$row['maxPercent'] = $maxPercent;
				}

				// 读取复审信息
				if ($row['scoreCheck'] == 1)
				{
					$sqlNew = "SELECT * FROM `platform_moneyPointConfirm` WHERE taskId = ".$row['id'];
					$resultNew = mysqli_query($conn, $sqlNew);

					if ($resultNew)
					{
						if ($rowNew = mysqli_fetch_assoc($resultNew))
						{
							// 用真实姓名替换Id
							$sqlNewNew = "SELECT name FROM `platform_user` WHERE id = ".$rowNew['checkAdminId'];
					
							$resultNewNew = mysqli_query($conn, $sqlNewNew);

							if ($resultNewNew)
							{
								if ($rowNewNew = mysqli_fetch_assoc($resultNewNew))
								{
									$rowNew['checkAdminName'] = $rowNewNew['name'];
								}
								else
								{
									$rowNew['checkAdminName'] = "暂无";
								}
							}
							else
							{
								$row['checkAdminName'] = "暂无";
							}
							
							$row['scoreCheckInfo'] = $rowNew;
						}
					}
				}

				$resMessage[] = $row;
			}

			$sqlNew = "SELECT COUNT(*) AS total FROM `platform_project` WHERE ((id IN (".$sqlInSelect.")) and (proName LIKE '%"
				.$allUsersTasksInfoCondition."%'))";
			// echo $sqlNew;

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