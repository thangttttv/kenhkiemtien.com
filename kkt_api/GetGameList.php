<?php
	date_default_timezone_set('Asia/Saigon'); 
    require_once("config.php");
    require_once("functions.php");
    
    $output = array();
    $status = array();
    $promotion = array();
    $ads = array();
    $data = array();
    $sms = array();
    $item = array();
    $numberRecordPerPage = 10;
        
    if(isset($_GET["catid"]) && isset($_GET["p"])){        
        $status["message"] = "";
        $status["code"] = "0";
        $status["date"] = date("dmY");
        
        /* Lấy game hot */
        $gamehot = GetGameHotApp();         
        $promotion["id"] = $gamehot["id"];
        $promotion["title"] = $gamehot["title"];
        $promotion["action"]["type"] = "aSMS";
        $promotion["action"]["url"] = "";
        $promotion["sms"]["message"] = "Nhắn tin để tải";
        $promotion["sms"]["syntax"] = "KBK 1";
        $promotion["sms"]["number"] = service_code();
        
        /* Lấy quảng cáo */
        $adv = getAdvApp();
        $ads["message"] = "";
        $ads["img"] = getImage($adv["picture"], "banner", $adv["create_date"], "");;
        $ads["time_out"] = 5;
        $ads["action"]["type"] = "aWAP";
        $ads["action"]["url"] = "";
        
        $currentPage = !empty($_GET["p"]) ? intval($_GET["p"]) : 0;
        $categoryId = !empty($_GET["catid"]) ? intval($_GET["catid"]) : 0;
        $game = GetAllGame($categoryId, $currentPage, $numberRecordPerPage);        
        $totalGame = GetTotalGame($categoryId);
        $totalPage = ceil($totalGame / $numberRecordPerPage);
                
        foreach($game as $i=>$row){
            $urlImg = getImage($row["picture"], "game", $row["create_date"], "m");
            $item[$i]["id"] = $row["id"]; 
            $item[$i]["title"] = $row["title"]; 
            $item[$i]["info"] = $row["download"] . " Lượt tải"; 
            $item[$i]["summary"] = $row["introtext"];
            $item[$i]["img"]["w"] = "70";
            $item[$i]["img"]["h"] = "70";
            $item[$i]["img"]["src"] = $urlImg;
        }
           
        $data["type"] = "ITEM";
        $data["totalPage"] = $totalPage;
        $data["action"]["type"] = "aURL";
        $data["action"]["url"] = "/GetGameList.php?catid=" . $categoryId . "&p=";
        $data["item"] = $item;
    } else {
        
        $status["message"] = "";
        $status["code"] = "1";
        $status["date"] = date("dmY");            
        
        $data["type"] = "ITEM";
        $data["totalPage"] = 0;
        $data["action"]["type"] = "aURL";
        $data["action"]["url"] = "/GetGameList.php?catid=&p=";
        $data["item"] = array();        
    }
    
    $sms["message"] = "Nhắn tin để tải";
    $sms["syntax"] = "KBK 1";
    $sms["number"] = service_code();    

    $data["sms"] = $sms;
    $output["status"] = $status;
    $output["promotion"] = $promotion;
    $output["ads"] = $ads;
    $output["data"] = $data;
    echo json_encode($output);  
?>