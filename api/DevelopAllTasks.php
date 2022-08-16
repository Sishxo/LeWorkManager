<?php
    // settings
    header('Access-Control-Allow-Origin: *');
	ini_set("display_errors","Off");

    // 页码和每页显示几条
    $page = $_POST['page'];
    $length = $_POST['length'];
	$DevelopCondition = $_POST['DevelopCondition'];

    // 计算
    $startNum = ($page - 1) * $length;
    // $endNum = $page * $length - 1;

    // 数据库
    include("mysqlConnect.php");
	if (!$conn)
	{
		$res['code'] = 0;
		$res['message'] = "连接错误";
	}
	else
	{
        $nowDateTime = date('Y-m-d H:i:s');
        // echo $nowDateTime;
		$sqlInSelect = "SELECT taskId FROM `platform_signUp`";
		// echo $sqlInSelect;
		$sql = "SELECT * FROM `platform_project` WHERE ((adminCheck = 1) and (signUpEndTime <> '0000-00-00 00:00:00') and ((signUpEndTime >= '".$nowDateTime.
			"') OR (id NOT IN (".$sqlInSelect."))) and proName LIKE '%".$DevelopCondition."%' and realEndFlag = 0 and projectType = 10) ORDER BY createTime DESC LIMIT ".$startNum.", ".$length;
		// echo $sql;
		
		$result = mysqli_query($conn, $sql);
		
		if ($result)
		{
            $resMessage = array();
			while ($row = mysqli_fetch_assoc($result))
			{
				// 用真实姓名去换userId
				if ($row['checkPerson'] == -1)
				{
					$row['checkPerson'] = "无需审核";
				}
				else if ($row['checkPerson'] == 0)
				{
					$row['checkPerson'] = "暂无";
				}
				else
				{
					$sqlNew = "SELECT name FROM `platform_user` WHERE id = ".$row['checkPerson'];
					
					$resultNew = mysqli_query($conn, $sqlNew);

					if ($resultNew)
					{
						if ($rowNew = mysqli_fetch_assoc($resultNew))
						{
							$row['checkPerson'] = $rowNew['name'];
						}
						else
						{
							$row['checkPerson'] = "暂无";
						}
					}
					else
					{
						$row['checkPerson'] = "暂无";
					}
				}

				// 读取Label
				$sqlNew = "SELECT label FROM `platform_projectLabel` WHERE taskId = ".$row['id'];
				$resultNew = mysqli_query($conn, $sqlNew);

				if ($resultNew)
				{
					$labels = array();
					while ($rowNew = mysqli_fetch_assoc($resultNew))
					{
						$labels[] = $rowNew;
					}
					
					if (count($labels))
					{
						$row['labels'] = $labels;
					}
					else
					{
						$row['labels'] = "";
					}
				}
				else
				{
					$row['labels'] = "";
				}

				$sqlNew = "SELECT label FROM `platform_projectLabel` WHERE taskId = ".$row['id'];
				$resultNew = mysqli_query($conn, $sqlNew);

				if ($resultNew)
				{
					$labels = array();
					while ($rowNew = mysqli_fetch_assoc($resultNew))
					{
						$labels[] = $rowNew;
					}
					
					if (count($labels))
					{
						$row['labels'] = $labels;
					}
					else
					{
						$row['labels'] = "";
					}
				}
				else
				{
					$row['labels'] = "";
				}

				// 判断报名延期
				if ($row['signUpEndTime'] >= $nowDateTime)
				{
					$row['havePersonSignUp'] = 1;
				}
				else
				{
					$row['havePersonSignUp'] = 0;
				}
				
				$resMessage[] = $row;
			}

			$sqlNew = "SELECT COUNT(*) AS total FROM `platform_project` WHERE ((adminCheck = 1) and (signUpEndTime <> '0000-00-00 00:00:00') and ((signUpEndTime >= '".$nowDateTime.
				"') OR (id NOT IN (".$sqlInSelect."))) and proName LIKE '%".$signUpCondition."%' and realEndFlag = 0 and projectType = 10)";
			// $sqlNew = "SELECT COUNT(*) AS total FROM `platform_project` WHERE ((adminCheck = 1) and (signUpEndTime <> '0000-00-00 00:00:00') and (signUpEndTime >= '".$nowDateTime.
			// "') and proName LIKE '%".$signUpCondition."%' and realEndFlag = 0)";

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