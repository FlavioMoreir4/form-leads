<?php
    function formatPhone($phone){
        $formatedPhone = preg_replace('/[^0-9]/', '', $phone);
        $matches = [];
        preg_match('/^([0-9]{2})([0-9]{4,5})([0-9]{4})$/', $formatedPhone, $matches);
        if ($matches) {
            return ' '.$matches[1].' '.$matches[2].'-'.$matches[3];
        }

        return $phone; // return number without format
    }
    if( $_SERVER['REQUEST_METHOD'] == 'POST'){ 
        include 'conn.php';
        header('Content-type: application/json');
        $response = array();
        $sql;

        if($_POST['r']){
            $seletor = $_POST["r"];
            $retVal = $_POST['r'] ? $_POST['r'] : $_POST['gr'];
            $franquia = $_POST["franquia"];
            $sql = "SELECT * FROM tbl_regioes WHERE sigla='$seletor' OR grupo_regional='$seletor' ORDER BY NOME";
            $numlin = $conn->query($sql);
            if($numlin->num_rows == 0){
            $sql = "SELECT * FROM tbl_regioes ORDER BY NOME";
            }
        }
        
        if(isset($_POST['phonesCons'])){
            $phonesCons = strip_tags($_POST['phonesCons']);
            $franquia = strip_tags($_POST['franquia']);
            $sql = "SELECT * FROM tbl_phones WHERE regiao = '$phonesCons' AND franquia = '$franquia'";
        }

        if(isset($_POST['regCons'])){
            $sql = "SELECT * FROM tbl_phones ORDER BY REGIAO";
            $sqls = "SELECT * FROM tbl_phones ORDER BY REGIAO";
            $SS = "SELECT *
            FROM tbl_regioes JOIN tbl_phones 
              ON tbl_regioes.nome = tbl_phones.regiao AND tbl_phones.franquia = ''";
        }

        if(isset($_POST['cadsCons'])){
            $franquia = strip_tags($_POST['franquia']);
            $sql = "SELECT * FROM tbl_leads_ipmil WHERE franquia = '$franquia'";
        }
        
        if($_POST['wpp']){
            $tbl = 'tbl_leads_'.$_POST['franquia'];
            $seletor = $_POST["wpp"]; 
            $date = new DateTime();
            $date->sub(new DateInterval('P30D'));
            $dataAntiga = $date->format('Y-m-d');            
            $sql = "SELECT * FROM $tbl WHERE whatsapp='$seletor' AND data >= '$dataAntiga' ORDER BY DATA DESC";
        }

        if($_POST['candidato']){ 
            $data = new DateTime();
            $candidato = $_POST['candidato'];
            $idade = $_POST['idade'];
            $whatsapp = $_POST['whatsapp'];
            $responsavel = $_POST['responsavel'];
            $telefone = $_POST['telefone'];
            $regiao = $_POST['regiao'];
            $campanha = $_POST['campanha'];
            $tbl = 'tbl_leads_'.$_POST['franquia'];
            $sql = "INSERT INTO `$tbl`  (`id`, `data`, `candidato`, `idade`, `whatsapp`, `responsavel`, `telefone`, `regiao`, `campanha`) VALUES
                                       (NULL, NOW(), '$candidato', '$idade', '$whatsapp', '$responsavel', '$telefone', '$regiao', '$campanha')";
        }

        if($_POST['whatsapp_resp']){ 
            $whatsapp = $_POST['whatsapp'];
            $responsavel = $_POST['responsavel'];
            $whatsappResp = $_POST['whatsapp_resp'];
            $regiao = $_POST['regiao'];
            $tbl = 'tbl_responsavel';
            $sql = "INSERT INTO `$tbl`  (`id`, `whatsapp_candidato`, `whatsapp_responsavel`, `responsavel`, `regiao`) VALUES
                                       (NULL, '$whatsapp', '$whatsappResp', '$responsavel', '$regiao')";
        }

        if($_POST['candidato'] || $_POST['whatsapp_resp']){
            echo $conn->query($sql);
        }else{
            $result = $conn->query($sql);
            while($row = $result->fetch_object()){
                foreach($row as $key => $col){
                    $col_array[$key] = $col;
                }
                array_push($response, $col_array);
            }
            echo json_encode($response);
        }
    }else{
        //$url = "https://".$_SERVER['HTTP_HOST'];
        //header("Location: $url");

    

    echo formatPhone($_GET["t"])."<br>";
    echo formatPhone('1187654321');
    }

?>