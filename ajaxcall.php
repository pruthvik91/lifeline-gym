<?php 
include 'admin_class.php';
include 'db_connect.php';
global $pdoconn;

$retArray = array();

if(isset($_POST['action']) && $_POST['action']!="")
{

    switch($_POST['action'])
	{
    case 'getWhatsappSession':
        if (isset($_SESSION["login_id"]) && $_SESSION["login_id"] != "" ){
            
            $wa_token = 'WA_API_'.$_SESSION["login_id"];
            if($wa_token != ''){
                        $wa_result =$pdoconn->prepare("SELECT * FROM whatsapp_token where user_id = :user_id");
						$wa_result->execute(array(':user_id' => $_SESSION["login_id"]));
						$wa_rows = $wa_result->fetchAll(PDO::FETCH_OBJ);
						$wa_rowcount = count($wa_rows);	
						if($wa_rowcount > 0){
							$query = " UPDATE whatsapp_token SET status=:status,contact_number='' WHERE user_id=:user_id AND wa_token=:wa_token ";
							$result = $pdoconn->prepare($query);
							$result->execute(array(':user_id'=>$_SESSION["login_id"], ':wa_token'=>$wa_token, ':status'=>0));
						}else{
							$query = " INSERT INTO whatsapp_token (`user_id`,`contact_number` ,`wa_token`, `status`, `create_date`) VALUES (:user_id, '', :wa_token, :status, NOW()) ";
							$result = $pdoconn->prepare($query);
							$result->execute(array(':user_id'=>$_SESSION["login_id"], ':wa_token'=>$wa_token, ':status'=>0));
						}
                $retArray["status"] = "OK";
                $retArray["wa_token"] = $wa_token;

            }else{
                $retArray["status"] = "ERROR";
                $retArray["msg"] = "Please try again.";
                $retArray["title"] = "ERROR";
            }
        }
        break;
    case 'authenticateWhatsappSession':
        if (isset($_SESSION["login_id"]) && $_SESSION["login_id"] != "" && isset($_POST['wa_token']) && isset($_POST['status'])){
            $contact_number = explode(':',$_POST['contact_number']);
            $contact_number = $contact_number[0];
            $query = " UPDATE whatsapp_token SET status=:status,contact_number=:contact_number WHERE user_id=:user_id AND wa_token=:wa_token ";
            $result = $pdoconn->prepare($query);
            $result->execute(array(':user_id'=>$_SESSION["login_id"], ':contact_number'=>$contact_number , ':wa_token'=>$_POST['wa_token'], ':status'=>$_POST['status']));

            $retArray["status"] = "OK";
            $retArray["wa_token"] = $_POST['wa_token'];
            $retArray["contact_number"] = $contact_number;
        }
    break;
    case 'message_log':
        if (isset($_SESSION["login_id"]) && $_SESSION["login_id"] != "" && isset($_POST['member_id']) && isset($_POST['status'])){
            $wa_token = $_POST['wa_token'];
            $member_id = $_POST['member_id'];
            $to_number = $_POST['to_number'];
            $query = " INSERT INTO message_log (`user_id`,`to_number` ,`wa_token`, `status`,`member_id`, `create_date`) VALUES (:user_id, :to_number, :wa_token, :status,:member_id, NOW()) ";
            $result = $pdoconn->prepare($query);
            $result->execute(array(':user_id'=>$_SESSION["login_id"] ,':to_number'=>$to_number, ':wa_token'=>$wa_token, ':status'=>1, ':member_id'=>$member_id));
            $retArray["status"] = "OK";
        }
    break;
       
        default:
		$retArray["status"] = "ERROR";	
    }
    

    ob_clean();
	echo json_encode($retArray);
}
?>
