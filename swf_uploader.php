<?php

	include("config/config.php");

	$defaults = array(
							'required' => true,
							'max_size' => 50000000,
							'path' => 'files/',
							'bucket' => '',
							'swf_uploader' => '/swf_uploader.php'
						);
	
	foreach ($defaults as $key => $val) {
		if (!isset($_POST[$key])) {
			$_POST[$key] = $val;
		}
	}
	
	if (isset($_POST['dynamic'])) {
		$_POST['dynamic'] = unserialize(urldecode($_POST['dynamic']));
	}

	if (isset($_FILES['up_file'])) {
		if (!file_exists($GLOBALS['cfg']['basedir'] . $_POST['path'])) {
			mkdir ($GLOBALS['cfg']['basedir'] . $_POST['path']);
		}

		if (!file_exists($GLOBALS['cfg']['basedir'] . $_POST['path'] . $_POST['bucket'])) {
			mkdir ($GLOBALS['cfg']['basedir'] . $_POST['path'] . $_POST['bucket']);
		}				

		if (isset($_POST['dynamic'])) {
			if (isset($_POST['dynamic']['set'])) {
				$_POST['bucket'] .= '/' . $_POST['dynamic']['set']->get();
			}
		}
	
		if (!file_exists($GLOBALS['cfg']['basedir'] . $_POST['path'] . $_POST['bucket'])) {
			mkdir ($GLOBALS['cfg']['basedir'] . $_POST['path'] . $_POST['bucket']);
		}

		$rootpath = $_POST['path'] . $_POST['bucket'] . '/';

		if (!file_exists($GLOBALS['cfg']['basedir'] . $rootpath . $_FILES['up_file']['name'])) {
			$filepath = $rootpath . $_FILES['up_file']['name'];
		} else {
			$i = 1;
		
			$fileparts = array(substr($_FILES['up_file']['name'], 0, strrpos($_FILES['up_file']['name'], '.')),
				substr($_FILES['up_file']['name'], strrpos($_FILES['up_file']['name'], '.')));
		
			while (file_exists($GLOBALS['cfg']['basedir'] . $rootpath . $fileparts[0] . $i . $fileparts[1])) {
				$i++;
			}
			$filepath = $rootpath . $fileparts[0] . $i . $fileparts[1];
		}
	
		if (!move_uploaded_file($_FILES['up_file']['tmp_name'], $GLOBALS['cfg']['basedir'] . $filepath)) {
			echo '';
		} else {
			echo urlencode(serialize(array('filepath' => $filepath, 'secret' => md5($GLOBALS['cfg']['secret'] . $filepath))));
		}
	} else {
		echo '';
	}

?>