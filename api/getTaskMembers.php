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
                        $row['userRealName'] = "暂无";
                    }
                }
                else
                {
                    $row['userRealName'] = "暂无";
                }

                // 判断是否已经评价
                $sqlNew = "SELECT * FROM `platform_score` WHERE taskId = ".$taskId." and markPersonId = ".$userId." and markedPersonId = ".$row['userId'];
                $resultNew = mysqli_query($conn, $sqlNew);
                if ($resultNew)
                {
                    if ($rowNew = mysqli_fetch_assoc($resultNew))
                    {
                        $row['hasScored'] = "已评价";
                        $row['score'] = $rowNew['score'];
                        $row['markText'] = $rowNew['markText'];
                        
                    }
                    else
                    {
                        $row['hasScored'] = "未评价";
                        $row['score'] = "暂无";
                        $row['markText'] = "暂无";
                    }
                }
                else
                {
                    $row['hasScored'] = "未评价";
                    $row['score'] = "暂无";
                    $row['markText'] = "暂无";
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