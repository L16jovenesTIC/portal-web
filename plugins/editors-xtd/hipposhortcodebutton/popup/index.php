<!DOCTYPE html>
<html class="no-js lt-ie9" lang="en">
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width"/>
	<title>Hippo Shortcode list</title>

	<link rel="stylesheet" href="stylesheets/foundation.min.css">
	<link rel="stylesheet" href="stylesheets/app.css">

	<script src="javascripts/modernizr.foundation.js"></script>
</head>
<body>

<div class="row">
	<div class="six columns">
		<h2>Hippo Shortcodes</h2>
	</div>
	<div class="six columns">
		<h5 class="text-right" style="margin-top:35px;"></h5>
	</div>
	<hr/>
</div>

<?php

	// Settings

	$template_name = trim( $_GET[ 'template' ] );
	$base_path     = trim( $_GET[ 'path' ] );
	$base_uri      = trim( $_GET[ 'uri' ] );

	$xml_path = '/hippo-shortcodes/shortcodeDetails.xml';
	$xml_base = '/hippo-shortcodes';

	$plugin_path    = '/plugins/system/hippo-shortcode';
	$overwride_path = '/templates/' . $template_name;

	$xml_file = $base_path . $plugin_path . $xml_path;
	$uri      = $base_uri . $plugin_path;

	if ( file_exists( $base_path . $overwride_path . $xml_path ) ) {
		$xml_file = $base_path . $overwride_path . $xml_path;
		$uri      = $base_uri . $overwride_path . $xml_base;
	}

	$xml_object = simplexml_load_file( $xml_file );

?>

<div class="row">
	<div class="three columns">
		<dl class="vertical tabs">

			<?php $key = 0;
				foreach ( $xml_object->hippoShortcode as $shortcodes ) {
					echo '<dd class="' . ( ( $key == 0 ) ? 'active' : '' ) . '"><a href="#' . $shortcodes->attributes()->name . '">' . $shortcodes->attributes()->title . '</a></dd>';
					$key ++;
				} ?>
		</dl>
	</div>

	<div class="nine columns">
		<ul class="tabs-content">

			<?php $key2 = 0;
				foreach ( $xml_object->hippoShortcode as $shortcodes ) {
					echo '<li class="' . ( ( $key2 == 0 ) ? 'active' : '' ) . '" id="' . $shortcodes->attributes()->name . 'Tab">';
					echo '<img width="700" src="' . $uri . '/' . $shortcodes->attributes()->image . '" alt=""/>';
					echo '<div class="pt">' . $shortcodes->description . '</div>';
					echo '<textarea onclick="this.focus();this.select()" style="height:120px;">' . $shortcodes->code . '</textarea>';


					if ( $shortcodes->options ) {

					echo '<div class="pt">' . $shortcodes->options->attributes()->title . ' </div>

	<div class="bt">
			<table style="width:100%">
			<thead>
			<tr>
			<th> Attribute Name </th>
			<th> Description </th>
			<th> Default </th>
			<th> Example </th>
			</tr>
			</thead>
			<tbody>';
						foreach ( $shortcodes->options->option as $option ) {
							echo '<tr>';
							echo '<td> ' . $option->attributes()->name . ' </td>';
							echo '<td> ' . $option . '</td>';
							echo '<td> ' . ( ( $option->attributes()->default ) ? $option->attributes()->default : '' ) . '</td>';
							echo '<td> ' . ( ( $option->attributes()->example ) ? $option->attributes()->example : '' ) . '</td>';
							echo '</tr>';
						}
				echo '
			</tbody>
			</table>
			</div>';
					}

					echo '</li>';
					$key2 ++;
				} ?>
		</ul>
	</div>
</div>


<!-- Included JS Files (Compressed) -->
<script src="javascripts/foundation.min.js"></script>

<!-- Initialize JS Plugins -->
<script src="javascripts/app.js"></script>


</body>
</html>
