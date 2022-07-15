<?php
	// Define a destination
	$targetDir = '../uploads/'; // Relative to the root

	//$username=isset($_POST['userName'])?$_POST['userName']:'';
	$fileName = $_FILES['files']['name'];
	// echo $fileName;

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

		// echo $conn;

        if($resultCheck)
        {
            if ($row = mysqli_fetch_assoc($resultCheck))
			{
				// 获取到路径信息
				$targetDir = $targetDir.$row['taskId'].'/'.$row['userId'];

				$targetFile = $targetDir . '/' . basename($_FILES["files"]["name"]);

				$response = array();

				if ($_FILES["files"]["size"] > 50000000) 
				{
					$response['code'] = 413;
					$response['message'] = "文件尺寸超出限制";
				} 
				else 
				{
					if(!file_exists($targetDir)){//检查文件夹是否存在
						mkdir(iconv("UTF-8", "GBK", $targetDir), 0777, true);    //没有就创建一个新文件夹
						chmod(iconv("UTF-8", "GBK", $targetDir), 0777);
					}
					if (move_uploaded_file($_FILES["files"]["tmp_name"], $targetFile)) 
					{
						$response['code'] = 200;
						$response['message'] = '文件 ' . $fileName . ' 上传成功!';
					} else 
					{
						$response['code'] = 10;
						$response['message'] = '文件 ' . $fileName . ' 上传失败。';
					}
				}
			}
            else
            {
                $response['code'] = 20;
				$response['message'] = '未获取到文件信息。';
            }
        }
		else
		{
			$response['code'] = 30;
			$response['message'] = '未获取到文件信息。';
		}
    }

	echo json_encode($response);
?>