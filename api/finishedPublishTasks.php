<?php
    // settings
    header('Access-Control-Allow-Origin: *');
	ini_set("display_errors","Off");

    // 页码和每页显示几条
    $page = $_POST['page'];
    $length = $_POST['length'];
    $headPerson = $_POST['headPerson'];
	$finishedCondition = $_POST['finishedCondition'];

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
		$sql = "SELECT * FROM `platform_project` WHERE ((headPerson = '".$headPerson."') AND (realEndFlag = 1)"
			." AND (proName LIKE '%".$finishedCondition."%')) ORDER BY createTime DESC LIMIT ".$startNum.", ".$length;
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

				$resMessage[] = $row;
			}

			$sqlNew = "SELECT COUNT(*) AS total FROM `platform_project` WHERE ((headPerson = '".$headPerson
				."') AND (realEndFlag = 1) AND (proName LIKE '%".$finishedCondition."%'))";
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