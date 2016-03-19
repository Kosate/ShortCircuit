<?php
    session_start();
    require('connect.php');

    $content = array();
    require_once('data/color.php');

    function getColor($id) {
        global $colorSet;
        return $colorSet[ intval($id) % count($colorSet) ];
    }

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


            $oldHash = getHashStorage();
            if( $oldHash != "-1" ) {
                $q = $connect->query("select hash from shortcircuit_each_info where hash='$oldHash'");
                if( $q->num_rows > 0 ) {
                    die(json_encode(array('id' => $oldHash)));
                }
            }

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

            var_dump(json_decode($_GET['data'], true));
            if( $data = json_decode($_GET['data'], true) ) {
                getFileContent('./data/structure.json');
                $clustering = $content;
                $data['_color'] = array();

                $i = 0;
                foreach( $data as $key => $value ) {
                    $i++;
                    foreach($clustering['setup'] as $eachCluster) {
                        if( $eachCluster['name'] == $key ) {
                            if( $eachCluster['type'] == 'choice' ) {
                                for($j = 0; $j < count($eachCluster['choice']); $j++) {
                                    if( $eachCluster['choice'][$j]['value'] == $value ) {
                                        $data["_color"][md5($key)] = getColor($j+$i*2);
                                        break;
                                    }
                                }
                            }
                            break;
                        }
                    }
                }
                if( $connect->query("update shortcircuit_each_info set info='".json_encode($data, JSON_UNESCAPED_UNICODE)."' where hash='$hash'") ) {
                  die("ok");
                } else {
                  die( $connect->error() );
                }
            }
        }
    }
    printf('-1');
?>
