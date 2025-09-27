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
    case 'add':
            $type = $_POST['type'] ?? '';
            $amount = $_POST['amount'] ?? 0;
            $description = $_POST['description'] ?? '';
            $created_at = $_POST['created_at'] ?? date('Y-m-d H:i:s');

            $stmt = $conn->prepare("INSERT INTO transactions (type, amount, description, created_at) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sdss", $type, $amount, $description, $created_at);
            $retArray["status"] = $stmt->execute() ? "OK" : "ERROR";
            break;


    case 'get':
        $selectedMonth = $_POST['month'] ?? date('Y-m');

        $start = $_POST['start'] ?? date('Y-m-01');
        $end = $_POST['end'] ?? date('Y-m-t');

        $transactions = [];
        $grouped = [];

        // Fetch transactions
        $result1 = $conn->query("SELECT id, type, amount, TRIM(LOWER(description)) AS group_key, description, DATE_FORMAT(created_at,'%d-%b-%y') as created_at FROM transactions WHERE created_at BETWEEN '$start' AND '$end'");

        while ($row = $result1->fetch_assoc()) {
            $transactions[] = $row;
            $grouped[$row['group_key']]['items'][] = $row;
            $grouped[$row['group_key']]['total'] = ($grouped[$row['group_key']]['total'] ?? 0) + $row['amount'];
        }

        // Fetch payments
        $result2 = $conn->query("SELECT id, 'income' as type, amount, TRIM(LOWER(remarks)) AS group_key, remarks as description, DATE_FORMAT(date_created,'%d-%b-%y') AS created_at FROM payments WHERE date_created BETWEEN '$start' AND '$end'");

        while ($row = $result2->fetch_assoc()) {
            $transactions[] = $row;
            $grouped[$row['group_key']]['items'][] = $row;
            $grouped[$row['group_key']]['total'] = ($grouped[$row['group_key']]['total'] ?? 0) + $row['amount'];
        }

        $income = array_reduce($transactions, fn($carry, $t) => $carry + ($t['type'] == 'income' ? $t['amount'] : 0), 0);
        $expense = array_reduce($transactions, fn($carry, $t) => $carry + ($t['type'] == 'expense' ? $t['amount'] : 0), 0);
        $retArray = [
            "status" => "OK",
            "data" => $transactions,
            "grouped" => $grouped,
            "income" => $income,
            "expense" => $expense,
            "balance" => $income - $expense
        ];
        break;


    case 'update':
        $id = $_POST['id'] ?? 0;
        $amount = $_POST['amount'] ?? 0;
        $description = $_POST['description'] ?? '';
        $created_at = $_POST['created_at'] ?? date('Y-m-d H:i:s');

        $stmt = $conn->prepare("UPDATE transactions SET amount=?, description=?, created_at=? WHERE id=?");
        $stmt->bind_param("dssi", $amount, $description, $created_at, $id);
        $retArray["status"] = $stmt->execute() ? "OK" : "ERROR";
        break;


    case 'delete':
        $id = $_POST['id'] ?? 0;
        $stmt = $conn->prepare("DELETE FROM transactions WHERE id=?");
        $stmt->bind_param("i", $id);
        $retArray["status"] = $stmt->execute() ? "OK" : "ERROR";
        break;
       
        default:
		$retArray["status"] = "ERROR";	
    }
    

    ob_clean();
	echo json_encode($retArray);
}
?>
