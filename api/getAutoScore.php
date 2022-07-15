<?php
    // settings
    header('Access-Control-Allow-Origin: *');
	ini_set("display_errors","Off");

    /* 成员互评 */
    $taskId = $_POST['taskId'];
    $userId = $_POST['userId'];

    // 数据库
    include("mysqlConnect.php");
	if (!$conn)
	{
		$res['code'] = 0;
		$res['message'] = "连接错误";
	}
	else
	{
		$sql = "SELECT * FROM `platform_signUp` WHERE taskId='".$taskId."'";
		// echo $sql;
		
		$result = mysqli_query($conn, $sql);
		
		if ($result)
		{
            $resMessage = array();
			while ($row = mysqli_fetch_assoc($result))
			{
                // 替换姓名
                $sqlNew = "SELECT name FROM `platform_user` WHERE id = ".$row['userId'];
                $resultNew = mysqli_query($conn, $sqlNew);
                if ($resultNew)
                {
                    if ($rowNew = mysqli_fetch_assoc($resultNew))
                    {
                        $row['userRealName'] = $rowNew['name'];
                    }
                    else
                    {
                        $row['userRealName'] = $resultNew;
                    }
                }
                else
                {
                    $row['userRealName'] = "暂无";
                }

                // 获取评价的人数
                $sqlNew = "SELECT COUNT(*) FROM `platform_score` WHERE taskId = ".$taskId." and markedPersonId = ".$row['userId'];
                $resultNew = mysqli_query($conn, $sqlNew);
                if ($resultNew)
                {
                    if ($rowNew = mysqli_fetch_assoc($resultNew))
                    {
                        $row['markPersonNumber'] = $rowNew['COUNT(*)'];
                    }
                    else
                    {
                        $row['markPersonNumber'] = $resultNew;
                    }
                }
                else
                {
                    $row['markPersonNumber'] = 0;
                }

                // 平均分
                $sqlNew = "SELECT score FROM `platform_score` WHERE taskId = ".$taskId." and markedPersonId = ".$row['userId'];
                $resultNew = mysqli_query($conn, $sqlNew);
                if ($resultNew)
                {
                    // 分情况
                    $totalScore = 0;
                    if (intval($row['markPersonNumber']) == 0)
                    {
                        // 不能除 0
                        $row['avarageScore'] = 0;
                    }
                    else if (intval($row['markPersonNumber']) <= 2)
                    {
                        // 太少，没得可去
                        // 平均
                        while ($rowNew = mysqli_fetch_assoc($resultNew))
                        {
                            $totalScore = (int)$totalScore + intval($rowNew['score']);
                        }

                        $row['avarageScore'] = (int)$totalScore / intval($row['markPersonNumber']);
                    }
                    else
                    {
                        // 去掉最低最高
                        $highestScore = 0;
                        $leastScore = 100;
                        while ($rowNew = mysqli_fetch_assoc($resultNew))
                        {
                            if (intval($rowNew['score']) > $highestScore)
                            {
                                $highestScore = intval($rowNew['score']);
                            }

                            if (intval($rowNew['score']) < $leastScore)
                            {
                                $leastScore = intval($rowNew['score']);
                            }
                            
                            $totalScore = (int)$totalScore + intval($rowNew['score']);
                        }
                        
                        $row['avarageScore'] = ((int)$totalScore - (int)$highestScore - (int)$leastScore) / (intval($row['markPersonNumber']) - 2);
                    }
                }
                else
                {
                    $row['avarageScore'] = 0;
                }

				$resMessage[] = $row;
			}
			
            if (count($resMessage))
            {
                // 计算泺研币和泺学分
                // 获取总的泺研币和泺学分
                $sqlNew = "SELECT * FROM `platform_project` WHERE id = ".$taskId;
                $resultNew = mysqli_query($conn, $sqlNew);
                if ($resultNew)
                {
                    if ($rowNew = mysqli_fetch_assoc($resultNew))
                    {
                        $taskMoneyAll = $rowNew['taskMoney'];
                        $taskPointAll = $rowNew['taskPoint'];
                    }
                    else
                    {
                        $taskMoneyAll = 0;
                        $taskPointAll = 0;
                    }
                }
                else
                {
                    $taskMoneyAll = 0;
                    $taskPointAll = 0;
                }

                // 计算总分
                $totalScore = 0;
                foreach ($resMessage as $key => $value) {
                    $totalScore = $totalScore + (float)$value['avarageScore'];
                }
                // $resMessage['taskMoneyAll'] = $taskMoneyAll;
                // $resMessage['taskPointAll'] = $taskPointAll;

                // 按比例分配
                foreach ($resMessage as $key => $value) {
                    $resMessage[$key]['autoTaskMoney'] = round((float)$taskMoneyAll * (float)$value['avarageScore'] / (float)$totalScore);
                    $resMessage[$key]['autoTaskPoint'] = round((float)$taskPointAll * (float)$value['avarageScore'] / (float)$totalScore);
                }

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