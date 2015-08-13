<?php
	
	function getFileContent( $fileName ) {
        $content = file_get_contents($fileName);
        $content = json_decode( $content, true );
		return $content;
    }
	
	if( !isset($_GET['adminToken']) || $_GET['adminToken'] != 'cf1a44' ) {
        header("Location: ../index.php");
        die();
    }
	
	$clustering = getFileContent('../data/structure.json');
	$admin = getFileContent('../data/admin_inject.json');
	$current = getFileContent('../data/current.json');
	
	if( $_GET['name'] == 'controller' ) {
		$current['state'] = $_GET['state'];
		if( $_GET['state'] == 'admin' ) {
			for($i = 0; $i < count($admin); $i++) {
				if( $admin[$i]['name'] == $_GET['type'] ) {
					$current['data'] = $admin[$i]['data'];
					break;		
				}
			}
		} else if( $_GET['state'] == 'clustering' ) {
			$current['data'] = $_GET['type'];
		} else {
			$current['data'] = array();
			unset($current['data']);	
		}
	} else if( $_GET['name'] == 'time' ) {
		$current['updateTime'] = $_GET['val'];
	}
	
	file_put_contents(
		'../data/current.json',
		json_encode( $current )
	);
?>