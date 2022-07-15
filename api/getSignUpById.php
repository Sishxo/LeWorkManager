<?php
    // settings
    header('Access-Control-Allow-Origin: *');
	ini_set("display_errors","Off");

    $taskId = $_POST['taskId'];
    $roleId = $_POST['roleId'];
    $userId = $_POST['userId'];
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
        $sql = "SELECT * FROM `platform_signUp` WHERE taskId=".$taskId." and userId = ".$userId." and roleId = ".$roleId;
		
		$result = mysqli_query($conn, $sql);
		
		if ($result)
		{
			$resMessage = array();
			while ($row = mysqli_fetch_assoc($result))
			{
                // 把 编号 改成 内容
                $sqlNew = "SELECT name FROM `platform_user` WHERE id=".$row['userId'];

                $resultNew = mysqli_query($conn, $sqlNew);
                if ($resultNew)
                {
                    if ($rowNew = mysqli_fetch_assoc($resultNew))
                    {
                        $row['userRealName'] = $rowNew['name'];
                    }
                    else
                    {
                        $res['code'] = 30;
				        $res['message'] = "人员信息查询失败";
                    }
                }
                else
                {
                    $res['code'] = 30;
				    $res['message'] = "人员信息查询失败";
                }

                // 角色
                $sqlNew = "SELECT roleName FROM `platform_role` WHERE id=".$row['roleId'];

                $resultNew = mysqli_query($conn, $sqlNew);
                if ($resultNew)
                {
                    if ($rowNew = mysqli_fetch_assoc($resultNew))
                    {
                        $row['roleName'] = $rowNew['roleName'];
                    }
                    else
                    {
                        $res['code'] = 40;
				        $res['message'] = "角色信息查询失败";
                    }
                }
                else
                {
                    $res['code'] = 40;
				    $res['message'] = "角色信息查询失败";
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