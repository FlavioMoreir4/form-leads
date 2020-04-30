<?php
    if( $_SERVER['REQUEST_METHOD'] == 'POST'){ 
        include 'conn.php';
        header('Content-type: application/json');
        $response = array();
        $sql;

        if(isset($_POST['phonesCons'])){
            $phonesCons = strip_tags($_POST['phonesCons']);
            $franquia = strip_tags($_POST['franquia']);
            $sql = "SELECT * FROM tbl_phones WHERE regiao = '$phonesCons' AND franquia = '$franquia'";
        }

        $result = $conn->query($sql);
        while($row = $result->fetch_object()){
            foreach($row as $key => $col){
                $col_array[$key] = $col;
            }
            array_push($response, $col_array["telefone"]);
        }
        //echo json_encode($response);
        $whatsCons = $col_array["telefone"];
        $instrutor = $col_array["responsavel"];
        $json_str = 
        '{
        "messages": [
            {
            "attachment": {
                "type": "template",
                "payload": {
                "template_type": "button",
                "text": "Para continuar fale com o instrutor de sua região via WhatsApp clicando no botão abaixo 👇",
                "buttons": [
                    {
                    "type": "web_url",
                    "url": "https://wa.me/'.$whatsCons.'",
                    "title": "FALE COM O INSTRUTOR"
                    }
                ]
                }
            }
            }
        ]
        }';
        $jsonObj = json_decode($json_str);
        echo json_encode($jsonObj);
    }else{
        $url = "https://".$_SERVER['HTTP_HOST'];
        header("Location: $url");
    }
?>