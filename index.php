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
	
	$_SESSION['opphand'] = null;
	
	for ($i = 0; i < 20; i++)
	{
		$_SESSION['opphand'][i]['low'] = 1;
		$_SESSION['opphand'][i]['high'] = 80;
	}
	
	$_SESSION['discardpile'] = null;
	$_SESSION['discardpile'] = $args[0]['initial_discard'];
	
	return "";
}

 function get_move($method_name, $args, $app_data) {
 
	if (count($args[0]['other_player_moves']) > 0)
	{
		$enemyInfo = $args[0]['other_player_moves'];
		
		//switch the last discard card with the enemy's discard card
		if (enemyInfo['move'] == "take_discard")
		{
			//stores the discard card of the pile in the enemy rack
			$_SESSION['opphand'][$enemyInfo['idx']]['low'] = $_SESSION['discardpile'][count($_SESSION['discardpile']) - 1];
			$_SESSION['opphand'][$enemyInfo['idx']]['high'] = $_SESSION['discardpile'][count($_SESSION['discardpile']) - 1];
			
			//replaces the old discard card with the new discard card
			$_SESSION['discardpile'][count($_SESSION['discardpile']) - 1] = $args[0]['discard'];
		}
		
		//add the enemy's discarded card to the discard pile
		if (enemyInfo['move'] == "take_deck")
		{
			//adds the new discard card
			$_SESSION['discardpile'] = $args[0]['discard'];
		}
	}
 
 
	$cardfitness = array();
	for(int $card = 0; $card<count($args[0]->rack); $card++) {
		$fdisc = fitnessOnDiscard($args[0], $card);
		$fdeck = fitnessOnDeck($args[0], $card);
		$cardfitness[] = array( 
			'fitness' => $fdisc,
			'idx' => $card,
			'move' => 'request_discard',		
			);
		$cardfitness[] = array( 
			'fitness' => $fdeck,
			'idx' => $card,
			'move' => 'request_deck',		
			);
	}
	$returnResult = new Move_Result;
	$returnResult->move = "";
	$returnResult->idx = 0;
	return $returnResult;
}

function fitnessOnDiscard($args, $card) {
	return 0;
}

function fitnessOnDeck($args, $card) {
	return 0;
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
