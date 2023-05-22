<?php

    require_once("../../includes/config.php");

    if(isset($_POST['group_chat_id']) &&
        isset($_POST['login_username']) && 
        isset($_POST['chat_message_groupchat'])){
    

        $chat_message_groupchat = $_POST['chat_message_groupchat'];
        $group_chat_id = $_POST['group_chat_id'];
        $login_username = $_POST['login_username'];

            
        $query = $con->prepare("INSERT INTO group_message
            (group_chat_id, user_username, body)
            VALUES(:group_chat_id, :user_username, :body)");

        $query->bindValue(":group_chat_id", $group_chat_id);
        $query->bindValue(":user_username", $login_username);
        $query->bindValue(":body", $chat_message_groupchat);
        
        if($query->execute()){

            $group_message_id = $con->lastInsertId();
           
            $statement = $con->prepare("SELECT group_chat_id, user_username,
                body, created_at FROM group_message
                WHERE group_message_id=:group_message_id
                LIMIT 1");

            $statement->bindValue(":group_message_id", $group_message_id);
            // $statement->bindValue(":user_username", $login_username);
            $statement->execute();

            if($statement->rowCount() > 0){
                // echo $statement->fetch(PDO::FETCH_ASSOC);
                echo json_encode($statement->fetch(PDO::FETCH_ASSOC));
            }


        }
    }

?>