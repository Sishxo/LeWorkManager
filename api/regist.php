<?php
    // settings
    header('Access-Control-Allow-Origin: *');
	ini_set("display_errors","Off");

    $email = $_POST['email'];
    $passwd = $_POST['passwd'];
    $inviteCode = $_POST['inviteCode'];
    $realName = $_POST['realName'];

    // 13位时间戳
    function getUnixTimestamp()
    {
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }

    // 先验证邀请码
    if ($inviteCode != "7X634-F107Q-9TZDU-P6NTC-8D5B8")
    {
        // 邀请码错误
        $res['code'] = 20;
		$res['message'] = "邀请码错误";
    }
    else
    {
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
                    $res['code'] = 30;
                    $res['message'] = "该用户已存在";
                }
                else
                {
                    // 生成用户名
                    $newUserName = "kcid_".getUnixTimestamp();
                    $nickName = "新用户";

                    $sql2 = "INSERT INTO `platform_user` (username, passwd, nickName, permit, email, name) VALUES ('".$newUserName."', '".$passwd."', '".$nickName."', 5, '".$email."', '".$realName."')";
                    
                    $result2 = mysqli_query($conn, $sql2);

                    if ($result2)
                    {
                        // 重新查询
                        $sql3 = "SELECT * FROM `platform_user` WHERE email='".$email."'";
                        $result3 = mysqli_query($conn, $sql3);

                        if ($result3)
                        {
                            if ($row3 = mysqli_fetch_assoc($result3))
                            {
                                $res['code'] = 200;
                                $res['message'] = $row3;
                            }
                        }
                    }
                    else
                    {
                        $res['code'] = 40;
                        $res['message'] = "写入数据库失败";
                    }
                }
            }
            else
            {
                $res['code'] = 10;
                $res['message'] = "查询失败";
            }
        }
    }

    echo json_encode($res, JSON_FORCE_OBJECT);
?>