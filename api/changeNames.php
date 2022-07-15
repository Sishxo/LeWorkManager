<?php
    // settings
    header('Access-Control-Allow-Origin: *');
	ini_set("display_errors","Off");

    $username = $_POST['username'];
    $nickNameChanged = $_POST['nickNameChanged'];
    $nameChanged = $_POST['nameChanged'];
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
		$sql = "UPDATE `platform_user` SET nickName = '".$nickNameChanged."', name = '".$nameChanged."' WHERE username = '".$username."'";
		// echo $sql;
		
		$result = mysqli_query($conn, $sql);
		
		if ($result)
		{
			$res['code'] = 200;
            $res['message'] = $sql;
			// $res['message'] = "基础个人信息更新成功";
		}
		else
		{
			$res['code'] = 0;
			$res['message'] = "基础个人信息更新失败";
		}
	}

    echo json_encode($res, JSON_FORCE_OBJECT);
?>