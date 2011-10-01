<?php

session_start();


$request_xml = file_get_contents("php://input");

$xmlrpc_server = xmlrpc_server_create();

xmlrpc_server_register_method($xmlrpc_server, "ping", "ping");
xmlrpc_server_register_method($xmlrpc_server, "start_game", "start_game");
xmlrpc_server_register_method($xmlrpc_server, "get_move", "get_move");
xmlrpc_server_register_method($xmlrpc_server, "get_deck_exchange", "get_deck_exchange");
xmlrpc_server_register_method($xmlrpc_server, "move_result", "move_result");
xmlrpc_server_register_method($xmlrpc_server, "game_result", "game_result");
header('Content-Type: text/xml');

print xmlrpc_server_call_method($xmlrpc_server, $request_xml, array());



 function ping($method_name, $args, $app_data) {
	if($text == "ping") return "pong";
	else return "pong";
}

 function start_game($method_name, $args, $app_data) {
	$ourFileName = "testFile2.html";
	$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
	fwrite($ourFileHandle, varDumpToString($args));
	fclose($ourFileHandle);	
	
	$_SESSION['discardpile'] = null;
	$_SESSION['discardpile'] = $args[0]['initial_discard'];
	
	return "";
}

 function get_move($method_name, $args, $app_data) {
	$returnResult = new Move_Result;
	$returnResult->move = "";
	$returnResult->idx = 0;
	return $returnResult;
}

 function get_deck_exchange($method_name, $args, $app_data) {
	return 0;
}

 function move_result($method_name, $args, $app_data) {
	return "";
}

 function game_result($method_name, $args, $app_data) {
	return "";
}


function varDumpToString($var)
{
	ob_start();
	var_dump($var);
	$result = ob_get_clean();
	return $result;
}

class Move_Result{
public $move;
public $idx;
}
?>