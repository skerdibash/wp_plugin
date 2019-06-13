<?php

	/*
	Plugin Name: Test Plugin
	Plugin URI: http://localhost/idontexist
	description: Test Plugin
	Version: 1
	Author: Skerdi
	Author URI: http://localhost/idontexisteither
	License: GPL2
	*/

	// (2)
  	function hello_world_shortcode() {

		return "<div>Hello World</div>";

	}

	add_shortcode("helloworld", "hello_world_shortcode");


	// (3)
	function api_shortcode() {

		// Get cURL resource
		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, [
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => "http://jsonplaceholder.typicode.com/users",
		]);
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		// Close request to clear up some resources
		curl_close($curl);

		return $resp;

	}

	add_shortcode("api", "api_shortcode");

	// (4)
	function add_hello_world($the_content) {

		$content = $the_content . hello_world_shortcode();

		return $content;

	}

	add_filter("the_content", "add_hello_world");

	// (5)
	wp_enqueue_script("ajax-script", plugins_url( "/test_plugin.js", __FILE__ ), 
		array("jquery"));

	wp_localize_script("ajax-script", "ajax_object", 
		array("ajax_url" => admin_url("admin-ajax.php")));

	function api_button_action() {
		echo api_shortcode();
	}

	add_action("wp_ajax_api_button_action", "api_button_action");

	function api_button_shortcode() {

		return "<button class='apibutton'>API REQUEST</button>
					<div class='ajax_content'></div>";

	}

	add_shortcode("apibutton", "api_button_shortcode");

	// (6)
	function save_order_id($order_id) {

		$servername = "localhost";
		$username = "wp_admin";
		$password = "wp_password";
		$dbname = "wp_db";

		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);

		// sql to create table
		$sqlcreate = "CREATE TABLE IF NOT EXISTS 
			purchase_ids (id INT PRIMARY KEY, num INT NOT NULL)";

		$conn->query($sqlcreate);

		$randnum = rand();

		$sqlinsert = "INSERT INTO purchase_ids (id, num) 
			VALUES ('" . $order_id . "','" .  $randnum. "')";

		$conn->query($sqlinsert);

		$conn->close();

	}

	add_action("woocommerce_payment_complete", "save_order_id");

?>