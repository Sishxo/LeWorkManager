<?php
    // settings
    header('Access-Control-Allow-Origin: *');
	ini_set("display_errors","Off");

    /* 用户信息列表获取 */
    $allUsersTasksStatisticsCondition = $_POST['allUsersTasksStatisticsCondition'];
    $page = $_POST['page'];
	$length = $_POST['length'];
    $startDateTime = $_POST['startDateTime'];
    $endDateTime = $_POST['endDateTime'];

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
		if ($page != 0)
		{
			$sql = "SELECT * FROM `platform_user` WHERE permit <= 4 and name LIKE '%".$allUsersTasksStatisticsCondition."%' LIMIT ".$startNum.", ".$length;
		}
		else
		{
			$sql = "SELECT * FROM `platform_user` WHERE permit <= 4 and name LIKE '%".$allUsersTasksStatisticsCondition."%'";
		}
		// echo $sql;
		
		$result = mysqli_query($conn, $sql);
		
		if ($result)
		{
            $resMessage = array();
			while ($row = mysqli_fetch_assoc($result))
			{
                // 查询用户领取的任务数量
                $sqlInSelect = "SELECT taskId FROM `platform_signUp` WHERE ((userId = ".$row['id'].") AND (signUpTime >= '"
                    .$startDateTime."') AND (signUpTime <= '".$endDateTime."'))";
                $sqlNew = "SELECT COUNT(*) AS total FROM `platform_project` WHERE (id IN (".$sqlInSelect."))";
                // echo $sqlNew;

                $resultNew = mysqli_query($conn, $sqlNew);

                if ($resultNew)
                {
                    if ($rowNew = mysqli_fetch_assoc($resultNew))
                    {
                        // echo $rowNew;
                        $row['joinedTasksCount'] = $rowNew['total'];
                    }
                    else
                    {
                        $row['joinedTasksCount'] = 0;
                    }
                }
                else
                {
                    $row['joinedTasksCount'] = 0;
                }

                // 查询用户完成的任务数量
                $sqlNew = "SELECT COUNT(*) AS total FROM `platform_project` WHERE ((id IN (".$sqlInSelect.")) AND (realEndFlag = 1))";

                $resultNew = mysqli_query($conn, $sqlNew);

                if ($resultNew)
                {
                    if ($rowNew = mysqli_fetch_assoc($resultNew))
                    {
                        // echo $rowNew;
                        $row['finishedTasksCount'] = $rowNew['total'];
                    }
                    else
                    {
                        $row['finishedTasksCount'] = 0;
                    }
                }
                else
                {
                    $row['finishedTasksCount'] = 0;
                }

                // 查询用户复核的积分
                $sqlInSelectInSelent = "SELECT id AS taskId FROM `platform_project` WHERE (realEndFlag = 1) AND (realEndTime > '".$startDateTime
                    ."') AND (realEndTime < '".$endDateTime."')";
                $sqlInSelect = "SELECT taskId FROM `platform_signUp` WHERE (taskId IN (".$sqlInSelectInSelent.")) AND userId = ".$row['id'];
                $sqlNew = "SELECT taskMoney, taskPoint FROM `platform_moneyPointConfirm` WHERE (userId = ".$row['id'].") AND (taskId IN (".$sqlInSelect."))";

                /*
                $sqlNew = "SELECT taskMoney, taskPoint FROM `platform_moneyPointConfirm` WHERE (insertDateTime > '".$startDateTime."') AND (insertDateTime < '"
                    .$endDateTime."') AND (userId = ".$row['id'].") AND (taskId IN (".$sqlInSelect."))";
                */

                // echo $sqlNew;
                
                $resultNew = mysqli_query($conn, $sqlNew);

                if ($resultNew)
                {
                    $rowNewArr = array();
                    while ($rowNew = mysqli_fetch_assoc($resultNew))
                    {
                        $rowNewArr[] = $rowNew;                        
                    }

                    if (count($rowNewArr))
                    {
                        $row['reCheckSum'] = count($rowNewArr);
                        $row['moneySum'] = array_sum(array_column($rowNewArr, 'taskMoney'));
                        $row['pointSum'] = array_sum(array_column($rowNewArr, 'taskPoint'));
                    }
                    else
                    {
                        $row['reCheckSum'] = 0;
                        $row['moneySum'] = 0;
                        $row['pointSum'] = 0;
                    }
                }
                else
                {
                    $row['reCheckSum'] = 0;
                    $row['moneySum'] = 0;
                    $row['pointSum'] = 0;
                }

                $resMessage[] = $row;
			}
			
			$sqlNew = "SELECT COUNT(*) AS total FROM `platform_user` WHERE permit <= 4 and name LIKE '%".$allUsersTasksStatisticsCondition."%'";
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