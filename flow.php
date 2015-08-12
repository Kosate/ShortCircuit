<?php
    session_start();
    require('connect.php');

    $content = [];

    function getFileContent( $fileName ) {
        global $content;
        $content = file_get_contents($fileName);
        $content = json_decode( $content, true );
    }

    function getHashStorage() {
        if( isset($_SESSION["curHash"]) && strlen($_SESSION["curHash"]) != 0 ) {
            return $_SESSION["curHash"];
        }
        return "-1";
    }

    if( isset($_GET['adminToken']) && $_GET['adminToken'] == 'eiei' ) {
    } else {

        if( $_GET['type'] == 'register_structure' ) {
            getFileContent('data/structure.json');
            print json_encode( $content['setup'] );
            die();

        } else if( $_GET['type'] == 'next_id' ) {
            $q = $connect->query("select count(*) from shortcircuit_each_info");
            $q = $q->fetch_assoc();
            $q = intval( $q['count(*)'] ) + 1;
            $hash = md5( ($q + 2500).'apple' );
            $connect->query("insert into shortcircuit_each_info(hash,info,update_data) values ('$hash','{}','{}')");
            print json_encode(array('id' => $hash));
            die();

        } else if( $_GET['type'] == 'update_each_info' ) {
            $hash = getHashStorage();
            if( $hash == "-1" ) die("-1");

            if( $connect->query("update shortcircuit_each_info set info='".$_GET["data"]."' where hash='$hash'") ) {
                die("ok");
            }

        }
    }
    printf('-1');
?>
