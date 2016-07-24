<?php
/**
 * stream.php
 *
 * @package MCFileManager.includes
 * @author Moxiecode
 * @copyright Copyright © 2005, Moxiecode Systems AB, All rights reserved.
 */

/**
 * Handles basic authentication. Returns true if user is authenticated.
 *
 * @param String $username User name to verify
 * @param String $password Password name to verify
 * @return Bool true on success, false on failure.
 */
function basicAuth($username, $password) {
	$PHP_AUTH_USER = $_SERVER['PHP_AUTH_USER'];
	$PHP_AUTH_PW = $_SERVER['PHP_AUTH_PW'];

	if ($PHP_AUTH_USER != $username || $PHP_AUTH_PW != $password) {
		header('WWW-Authenticate: Basic realm="Restricted Directory"');
		header('HTTP/1.0 401 Unauthorized');
		header('status: 401 Unauthorized');
		return false;
	}

	return true;
}

/**
 * Custom binary stream file function like readfile.
 */
function streamFile($file_path) {
	if (($fp = fopen($file_path, "rb"))) {
		while (!feof ($fp)) {
			$data = fread($fp, 8192);
			echo $data;
			flush();
		}

		fclose($fp);
	}
}

/**
 * Returns the mime type of an URL by resolving it agains a apache style "mime.types" file.
 *
 * @param String $url URL to Map/get content type by
 * @patam String $mime_File Absolute filepath to mime.types style file.
 * @return String mime type of URL or an empty string on failue.  
 */
function mapMimeTypeFromUrl($url, $mime_file) {
	if (($fp = fopen($mime_file, "r"))) {
		$urlParts = & parse_url($url);
		$ar = explode('.', $urlParts['path']);
		$ext = strtolower(array_pop($ar));

		while (!feof ($fp)) {
			$line = fgets($fp, 4096);
			$chunks = & preg_split("/(\t+)|( +)/", $line);

			for ($i=1; $i<count($chunks); $i++) {
				if (rtrim($chunks[$i]) == $ext)
					return $chunks[0];
			}
		}

		fclose($fp);
	}

	return "";
}

?>