<?php
    // settings
    header('Access-Control-Allow-Origin: *');
	ini_set("display_errors","Off");

    $taskId = $_POST['taskId'];
    $dateTime = $_POST['dateTime'];
    $checkAdminId = $_POST['checkAdminId'];
    $moneyPointInfo = $_POST['moneyPointInfo'];

    // 数据库
    include("mysqlConnect.php");
	if (!$conn)
	{
		$res['code'] = 0;
		$res['message'] = "连接错误";
	}
	else
	{
		foreach ($moneyPointInfo as $key => $value)
        {
            // 获取变量信息
            $taskMoney = $value['autoTaskMoney'];
            $taskPoint = $value['autoTaskPoint'];
            $userId = $value['userId'];
    
            // 插入
            $sql = "INSERT INTO `platform_moneyPointConfirm` (taskId, insertDateTime, checkAdminId, userId, taskMoney, taskPoint) VALUE ("
                    .$taskId.", '".$dateTime."', ".$checkAdminId.", ".$userId.", ".$taskMoney.", ".$taskPoint.")";            
            $result = mysqli_query($conn, $sql);
            
            // 后续
            if ($result)
            {
                // 查询 已有的泺研币和泺学分
                $sql = "SELECT taskPoint, taskMoney FROM `platform_user` WHERE id = ".$userId;
                $result = mysqli_query($conn, $sql);
                if ($result)
                {
                    if ($row = mysqli_fetch_assoc($result))
                    {
                        $nowTaskMoney = (int)$row['taskMoney'];
                        $nowTaskPoint = (int)$row['taskPoint'];

                        $newTaskMoney = $nowTaskMoney + (int)$taskMoney;
                        $newTaskPoint = $nowTaskPoint + (int)$taskPoint;

                        // 更新
                        $sql = "UPDATE `platform_user` SET taskMoney = ".$newTaskMoney.", taskPoint = ".$taskPoint." WHERE id = ".$userId;
                        $result = mysqli_query($conn, $sql);
                        if ($result)
                        {
                            // 再更新
                            $sql = "UPDATE `platform_project` SET scoreCheck = 1 WHERE id = ".$taskId;
                            $result = mysqli_query($conn, $sql);
                            if ($result)
                            {
                                $res['code'] = 200;
                            }
                            else
                            {
                                $res['code'] = 40;
			                    $res['message'] = "项目参数更新失败";
                            }
                        }
                        else
                        {
                            $res['code'] = 30;
			                $res['message'] = "个人参数更新失败";
                        }
                    }
                    else
                    {
                        $res['code'] = 20;
			            $res['message'] = "查询现有个人参数失败";
                    }
                }
                else
                {
                    $res['code'] = 20;
			        $res['message'] = "查询现有个人参数失败";
                }
            }
            else
            {
                $res['code'] = 10;
			    $res['message'] = "积分明细添加失败";
            }
        }

        if ($res['code'] == 200)
        {
            $res['message'] = "复核确认成功";
        }
	}

    echo json_encode($res, JSON_FORCE_OBJECT);
?>