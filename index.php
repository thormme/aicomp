<?php

class XMLRPCServer 
{
   
    private $serverHandler;
   
    private $externalFunctions;
   
    public function __construct() 
	{
        $this->serverHandler = xmlrpc_server_create();
        $this->externalFunctions = array();
    }
   
    public function registerMethod($externalName, $function, $parameterNames) 
	{
        if($function == null) $function = $externalName;
        xmlrpc_server_register_method($this->serverHandler, $externalName, array(&$this, 'callMethod'));
        $this->externalFunctions[$externalName] =
            array('function'       => $function,
                  'parameterNames' => $parameterNames);
    }
   
    public function callMethod($functionName, $parametersFromRequest) 
	{
        $function = $this->externalFunctions[$functionName]['function'];
        $parameterNames = $this->externalFunctions[$functionName]['parameterNames'];
        $parameters = array();
        foreach($parameterNames as $parameterName) {
            $parameters[] = $parametersFromRequest[0][$parameterName];
        }
        return call_user_func_array($function, $parameters);
    }
   
    public function computeAnswer() 
	{
        return xmlrpc_server_call_method($this->serverHandler, file_get_contents('php://input'), null);
    }
}

// USAGE EXAMPLE HERE
$xmlRPCServer = new XMLRPCServer();
$someServer = new SomeXmlRPCServer($xmlRPCServer);
$answer = $xmlRPCServer->computeAnswer();
header('Content-Type: text/xml');
print($answer);


class SomeXmlRPCServer
{
   
    private $xmlRPCServer;
   
    public function __construct($xmlRPCServer) 
	{
        $this->xmlRPCServer = $xmlRPCServer;
        $this->xmlRPCServer->registerMethod('ping',array(&$this, 'pingInternal'),array('text'));
		$this->xmlRPCServer->registerMethod('start_game',array(&$this, 'start_gameInternal'),array('sgStruct'));
		$this->xmlRPCServer->registerMethod('get_move',array(&$this, 'get_moveInternal'),array('gmStruct'));
		$this->xmlRPCServer->registerMethod('get_deck_exchange',array(&$this, 'get_deck_exchangeInternal'),array('gdeStuct'));
		$this->xmlRPCServer->registerMethod('move_result',array(&$this, 'move_resultInternal'),array('mrStruct'));
		$this->xmlRPCServer->registerMethod('game_result',array(&$this, 'game_resultInternal'),array('grStuct'));
    }
   
    public function pingInternal($text) 
	{
		$ourFileName = "testFile.html";
		$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
		fwrite($ourFileHandle, varDumpToString($text->parameterNames));
		fclose($ourFileHandle);
		
		return "pong";
    }
	
	public function start_gameInternal($sgStruct) 
	{
		//file_put_contents("startgametestfile.html", varDumpToString($sgStruct));
		
		$ourFileName = "testFile2.html";
		$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
		fwrite($ourFileHandle, $sgStruct[1]);
		fclose($ourFileHandle);
		
		return "";
	}
	
	public function get_moveInternal($gmStruct) 
	{
	
		$returnResult = new Move_Result;
		$returnResult->move = "";
		$returnResult->idx = 0;
		return $returnResult;
	}
	
	public function get_deck_exchangeInternal($gdeStuct) 
	{
		return 0;
	}
	
	public function move_resultInternal($mrStruct) 
	{
		return "";
	}
	
	public function game_resultInternal($grStuct) 
	{
		return "";
	}
	
	
}

function varDumpToString($var)
{
	ob_start();
	var_dump($var);
	$result = ob_get_clean();
	return $result;
}

class Move_Result
{
	public $move;
	public $idx;
}


?>