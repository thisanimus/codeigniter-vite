<?php

function vite_config() {

	$env = parse_ini_file(ROOTPATH . '.env');
	$server = $env['VITE_PROTOCOL'] . '://' . $env['VITE_HOST'] . ':' . $env['VITE_PORT'];
	return [
		'server' => $server,
		'entry_point' => $env['VITE_ENTRY_POINT'],
		'dist_path' => ROOTPATH . $env['VITE_OUTPUT_DIR'],
		'dist_uri' =>  $env['VITE_OUTPUT_BASEURI']
	];
}
/**
 * Returns CodeIgniter's version.
 */
function vite_running(): bool {
	$vite_config = vite_config();
	$ch = curl_init($vite_config['server'] . '/' . $vite_config['entry_point']);
	curl_setopt($ch, CURLOPT_HEADER, true);    // Include headers in the response
	curl_setopt($ch, CURLOPT_NOBODY, true);    // Use HEAD method to reduce data transfer
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Return the response as a string
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);     // Set a timeout for the request
	$output = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	// Check if the HTTP status code is 200 (OK)
	if ($httpcode == 200) {
		return true;
	} else {
		return false;
	}
}

/**
 * Retrieves a list of CSS and JavaScript assets from vite's manifest file.
 *
 * This method reads the manifest file located at the specified distPath and extracts
 * the list of CSS and JavaScript assets associated with the entryPoint. The manifest
 * file should be in JSON format.
 *
 * @return array An associative array containing two keys:
 *               - 'css' (array): An array of CSS asset paths.
 *               - 'js' (array): An array of JavaScript asset paths.
 *               If the manifest file or specific assets are not found, empty arrays are returned.
 */
function get_production_assets() {
	$vite_config = vite_config();
	$manifest = json_decode(file_get_contents($vite_config['dist_path'] . '/.vite/manifest.json'), true);
	$filelist = [
		'css' => [],
		'js' => [],
	];

	if (is_array($manifest)) {
		if (isset($manifest[$vite_config['entry_point']])) {
			$files = $manifest[$vite_config['entry_point']];
			if (isset($files['css'])) {
				$filelist['css'] = $files['css'];
			}
			if (isset($files['file'])) {
				$filelist['js'][] = $files['file'];
			}
		}
	}
	return $filelist;
}

function vite_css(): string {
	$vite_config = vite_config();
	$css = '';
	if (vite_running()) {
	} else {
		$filelist = get_production_assets();
		$i = 0;
		foreach ($filelist['css'] as $file) {
			$i++;
			$css .= '<link href="' . $vite_config['dist_uri'] . '/' . $file . '" rel="stylesheet" />';
		}
	}
	return $css;
}

function vite_js(): string {
	$vite_config = vite_config();

	$js = '';
	if (vite_running()) {
		$src = $vite_config['server'] . '/' . $vite_config['entry_point'];
		$js .= '<script id="vite" type="module" crossorigin src="' . $src . '"></script>';
	} else {
		$filelist = get_production_assets();
		$i = 0;
		foreach ($filelist['js'] as $file) {
			$i++;
			$js .= '<script type="module" defer src="' . $vite_config['dist_uri'] . '/' . $file . '"></script>';
		}
	}
	return $js;
}
