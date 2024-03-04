<?php
$path_prefix = '';

if ( isset( $_SERVER['PATH_INFO'] ) ) {
	$path_count = substr_count( $_SERVER['PATH_INFO'], '/' ) - 1;

	for ( $i = 0; $i < $path_count; $i++ ) {
		$path_prefix .= '../';
	}

	if ( strpos( $_SERVER['PATH_INFO'], 'api' ) !== false ) {
		$path_info = explode( '/', trim( $_SERVER['PATH_INFO'], '/' ) );

		if ( count( $path_info ) % 2 === 0 ) {
			for ( $i = 0; $i < count( $path_info ); $i += 2 ) {
				$path_data[$path_info[$i]] = $path_info[$i + 1];
			}
		}

		if ( isset( $path_data['api'] ) ) {
			$date = $path_data['api'];
		} else {
			$date = 'now';
		}

		if ( strpos( $date, '.' ) !== false ) {
			$date = str_replace( '.', '', $date );
		}

		if ( is_numeric( $date ) ) {
			if ( strlen( $date ) === 13 ) {
				$date = substr_replace( $date, '.', -3, 0 );
				$date = date_create_from_format( 'U.v', $date, timezone_open( 'UTC' ) );
				date_timezone_set( $date, timezone_open( 'UTC' ) );
			} else {
				$date = date_create_from_format( 'U', $date, timezone_open( 'UTC' ) );
				date_timezone_set( $date, timezone_open( 'UTC' ) );
			}
		} elseif ( $date == 'now' || ! ctype_alpha( str_replace( ' ', '', $date ) ) ) {
			$date = date_create( $date, timezone_open( 'UTC' ) );
		} else {
			$date = false;
		}

		if ( $date === false ) {
			$data = [
				'error' => 'Invalid Date',
			];
		} else {
			$data = [
				'unix' => (int) date_format( $date, 'Uv' ),
				'utc' => date_format( $date, 'D, j M Y H:i:s T' ),
			];
		}

		header( 'Content-Type: application/json; charset=utf-8' );
		echo json_encode( $data );
		exit;
	} else {
		redirect_to_index();
	}
}

function redirect_to_index() {
	global $path_prefix;

	if ( $path_prefix == '' ) {
		$path_prefix = './';
	}

	header( "Location: $path_prefix" );
	exit;
}
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Timestamp Microservice</title>
	<meta name="description" content="freeCodeCamp - APIs and Microservices Project: Timestamp Microservice">
	<link rel="icon" type="image/x-icon" href="<?php echo $path_prefix; ?>favicon.ico">
	<link rel="stylesheet" href="<?php echo $path_prefix; ?>assets/css/bootstrap.min.css">
</head>
<body>
	<div class="container">
		<div class="p-4 my-4 bg-light rounded-3">
			<div class="row">
				<div class="col">
					<h1 id="title" class="text-center">Timestamp Microservice</h1>

					<h3>User Stories:</h3>
					<ol class="user-stories">
						<li>The API endpoint is <code>GET [project_url]/api/[date_string]</code></li>
						<li>A date string is valid if can be successfully parsed.<br>
							Note that the Unix timestamp needs to be an <strong>integer</strong> (not a string) specifying <strong>milliseconds</strong>.<br>
							In our test we will use date strings compliant with ISO-8601 (for example: <code>"2016-11-20"</code>) because this will ensure a UTC timestamp.</li>
						<li>If the date string is <strong>empty</strong> the API uses the current timestamp.</li>
						<li>If the date string is <strong>valid</strong> the API returns a JSON having the structure <br>
							<code>{ "unix": (Unix timestamp), "utc": (UTC date and time) }</code><br>
							for example: <code>{"unix":1479663089000,"utc":"Sun, 20 Nov 2016 17:31:29 UTC"}</code></li>
						<li>If the input date string is <strong>invalid</strong> the API returns a JSON having the structure <br>
							<code>{ "error": "Invalid Date" }</code>. <br>
							It is what you get from the date manipulation functions.
						</li>
					</ol>

					<h3>Example Usage:</h3>
					<ul>
						<li><code>GET <a href="<?php echo $path_prefix; ?>api/" target="_blank">/api/</a></code></li>
						<li><code>GET <a href="<?php echo $path_prefix; ?>api/2015-12-25" target="_blank">/api/2015-12-25</a></code></li>
						<li><code>GET <a href="<?php echo $path_prefix; ?>api/1451001600000" target="_blank">/api/1451001600000</a></code></li>
						<li><code>GET <a href="<?php echo $path_prefix; ?>api/1451001600" target="_blank">/api/1451001600</a></code></li>
						<li><code>GET <a href="<?php echo $path_prefix; ?>api/invalid" target="_blank">/api/invalid</a></code></li>
					</ul>

					<h3>Example Output:</h3>
					<p>
						<code>{"unix":1451001600000,"utc":"Fri, 25 Dec 2015 00:00:00 UTC"}</code>
					</p>

					<div class="footer text-center">by <a href="https://www.freecodecamp.org" target="_blank">freeCodeCamp</a> & <a href="https://www.freecodecamp.org/adam777" target="_blank">Adam</a> | <a href="https://github.com/Adam777Z/freecodecamp-project-timestamp-php" target="_blank">GitHub</a></div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>