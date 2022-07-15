<?php
    // settings
    header('Access-Control-Allow-Origin: *');
	ini_set("display_errors","Off");

    $taskId = $_POST['taskId'];
    $roleName = $_POST['roleName'];
    $wantPersonNumChanged = $_POST['wantPersonNumChanged'];
    $leastPersonNumChanged = $_POST['leastPersonNumChanged'];
    $signUpBeginDateTime = $_POST['signUpBeginDateTime'];
    $signUpEndDateTime = $_POST['signUpEndDateTime'];
    $adminCheck = $_POST['adminCheck'];

    // 数据库
    include("mysqlConnect.php");
	if (!$conn)
	{
		$res['code'] = 0;
		$res['message'] = "连接错误";
	}
	else
	{        
        // 标签
        $sql = "INSERT INTO `platform_role` (roleName, wantPersonNum, leastPersonNum, signUpBeginTime, signUpEndTime, taskId) VALUE ('"
                .$roleName."', ".$wantPersonNumChanged.", ".$leastPersonNumChanged.", '".$signUpBeginDateTime."', '".$signUpEndDateTime."', '".$taskId."')";
        // $res = $sql;

        $result = mysqli_query($conn, $sql);
            
        if ($result)
        {
            // 查询最迟时间
            $sql = "SELECT signUpEndTime FROM `platform_role` WHERE taskId = '".$taskId."' ORDER BY signUpEndTime DESC LIMIT 1";
            $result = mysqli_query($conn, $sql);

            if ($result)
            {
                if ($row = mysqli_fetch_assoc($result))
                {
                    $lateSignUpEndTime = $row['signUpEndTime'];
                }
                else
                {
                    $lateSignUpEndTime = "0000-00-00 00:00:00";
                }
            }
            else
            {
                $lateSignUpEndTime = "0000-00-00 00:00:00";
            }

            // 写入最迟时间
            $sql = "UPDATE `platform_project` SET signUpEndTime = '".$lateSignUpEndTime."' WHERE id = ".$taskId;
            $result = mysqli_query($conn, $sql);
            if ($result)
            {
                $res['code'] = 200;
                $res['message'] = "角色新增成功";
            }
            else
            {
                $res['code'] = 30;
			    $res['message'] = "最迟报名时间更新失败";
            }
        }
        else
        {
            $res['code'] = 20;
			$res['message'] = "角色新增失败";
        }
	}

    echo json_encode($res, JSON_FORCE_OBJECT);
?>