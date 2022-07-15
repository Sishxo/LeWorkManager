<?php
    // 获取文件名、用户ID、项目ID、项目标题
    $fileName = $_POST['fileName'];
    $taskId = $_POST['taskId'];
    $userId = $_POST['userId'];
    //$taskTitle = $_POST['taskTitle'];
    $name = $_POST['name'];

    include("mysqlConnect.php");
    if (!$conn)
    {
        $res['code'] = 0;
        $res['message'] = "连接错误";
    }
    else
    {
        $sqlCheck = "SELECT * FROM `platform_fileUploadInfo` WHERE fileName='".$fileName."'";
        // echo $sqlCheck;

        $resultCheck = mysqli_query($conn, $sqlCheck);

        if($resultCheck)
        {
            if ($row = mysqli_fetch_assoc($resultCheck))
            {
                $res['code'] = 20;
                $res['message'] = "上传的文件已经存在，请检查或者修改文件名";
            }
            else
            {
                // 查不到，插入
                $sqlInsert = "INSERT INTO `platform_fileUploadInfo` (taskId, userId, fileName) VALUES (".$taskId.", ".$userId.", '".$fileName."')";
                // echo $sqlInsert;
            
                $resultInsert = mysqli_query($conn, $sqlInsert);
                
                if($resultInsert)
                {
                    $res['code'] = 200;
                    $res['message'] = "上传文件信息插入成功";
                }
                else
                {
                    $res['code'] = 10;
                    $res['message'] = "上传文件信息插入失败";
                }
            }
        }
        else
        {
            $res['code'] = 30;
            $res['message'] = "数据表错误";
        }
    }

    echo json_encode($res, JSON_FORCE_OBJECT);
?>