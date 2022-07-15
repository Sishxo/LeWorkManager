<?php
    // settings
    header('Access-Control-Allow-Origin: *');
	ini_set("display_errors","Off");

    $signUpId = $_POST['signUpId'];
    $roleId = $_POST['roleId'];

    // 数据库
    include("mysqlConnect.php");
	if (!$conn)
	{
		$res['code'] = 0;
		$res['message'] = "连接错误";
	}
	else
	{
		$sql = "DELETE FROM `platform_signUp` WHERE id=".$signUpId;
		// echo $sql;
		
		$result = mysqli_query($conn, $sql);
		
		if ($result)
		{
			// 更新已参与人数
			$sql = "SELECT joinedPersonNum FROM `platform_role` WHERE id = ".$roleId;
			$result = mysqli_query($conn, $sql);

			if ($result)
			{
				if ($row = mysqli_fetch_assoc($result))
				{
					// $row['joinedPersonNum']
					$joinedPersonNum = number_format($row['joinedPersonNum']);
					$joinedPersonNum = $joinedPersonNum - 1;
					// 重写
					$sql = "UPDATE `platform_role` SET joinedPersonNum = ".$joinedPersonNum." WHERE id = ".$roleId;
					$result = mysqli_query($conn, $sql);

					// 更新
					if ($result)
					{
						$res['code'] = 200;
						$res['message'] = "任务删除成功";
					}
					else
					{
						$res['code'] = 30;
						$res['message'] = "参与人数更新失败";
					}
				}
				else
				{
					$res['code'] = 20;
					$res['message'] = "参与人数获取失败";
				}
			}
			else
            {
				$res['code'] = 20;
				$res['message'] = "参与人数获取失败";
			}
		}
		else
		{
			$res['code'] = 10;
			$res['message'] = "删除失败";
		}
	}

    echo json_encode($res, JSON_FORCE_OBJECT);
?>