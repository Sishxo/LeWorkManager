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
		$sql = "SELECT * FROM `platform_childTasks` WHERE taskId='".$taskId."'";
		// echo $sql;
		
		$result = mysqli_query($conn, $sql);
		
		if ($result)
		{
			$resMessage = array();
			while ($row = mysqli_fetch_assoc($result))
			{
				// 查找最大实际完成度
				$sql2 = "SELECT * FROM `platform_realSchedule` WHERE childTaskId = ".$row['id'];
				// echo $sql2;
				$result2 = mysqli_query($conn, $sql2);

				if ($result2)
				{
					// 查阅实际进度
					$maxPercent = 0;
					$maxPercentTime = 0;
					$maxPercentUserId = 0;
					while ($row2 = mysqli_fetch_assoc($result2))
					{
						if ($maxPercent < $row2['realPercent'])
						{
							$maxPercent = $row2['realPercent'];
							$maxPercentTime = $row2['realDateTime'];
							$maxPercentUserId = $row2['userId'];
						}
					}

					if ($maxPercent == 0)
					{
						$row['maxPercent'] = 0;
						$row['maxPercentTime'] = "未开始";
						$row['maxUser'] = "--";

					}
					else
					{
						// 获取用户真实姓名
						$sql3 = "SELECT * FROM `platform_user` WHERE id = ".$maxPercentUserId;

						$result3 = mysqli_query($conn, $sql3);

						if ($result3)
						{
							if ($row3 = mysqli_fetch_assoc($result3))
							{
								$row['maxUser'] = $row3['name'];
							}
						}

						$row['maxPercent'] = $maxPercent;
						$row['maxPercentTime'] = $maxPercentTime;
					}
				}
				else
				{
					$row['maxPercent'] = 0;
					$row['maxPercentTime'] = "未开始";
					$row['maxUser'] = "--";
				}

				// 查找最大计划完成度
				$sql2 = "SELECT * FROM `platform_planSchedule` WHERE childTaskId = ".$row['id'];
				// echo $sql2;
				$result2 = mysqli_query($conn, $sql2);

				if ($result2)
				{
					// 查阅计划进度
					$maxPercent = 0;
					$maxPercentTime = 0;
					$maxPercentUserId = 0;
					while ($row2 = mysqli_fetch_assoc($result2))
					{
						if ($maxPercent < $row2['planPercent'])
						{
							$maxPercent = $row2['planPercent'];
							$maxPercentTime = $row2['planDateTime'];
						}
					}

					if ($maxPercent == 0)
					{
						$row['maxPlanPercentTime'] = "未上报计划";
					}
					else
					{
						$row['maxPlanPercentTime'] = $maxPercentTime;
					}
				}
				else
				{
					$row['maxPlanPercentTime'] = "未上报计划";
				}

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