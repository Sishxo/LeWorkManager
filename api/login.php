<?php
    // settings
    header('Access-Control-Allow-Origin: *');
	ini_set("display_errors","Off");

    $email = $_POST['email'];
    $passwd = $_POST['passwd'];

    // 数据库
    include("mysqlConnect.php");
	if (!$conn)
	{
		$res['code'] = 0;
		$res['message'] = "连接错误";
	}
	else
	{
		$sql = "SELECT * FROM `platform_user` WHERE email='".$email."'";
		// echo $sql;
		
		$result = mysqli_query($conn, $sql);
		
		if ($result)
		{
			if ($row = mysqli_fetch_assoc($result))
			{
				if ($passwd == $row['passwd'])
				{
					$res['code'] = 200;
					$res['message'] = $row;
				}
				else
				{
					$res['code'] = 30;
					$res['message'] = "密码输入错误";
				}
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