<?php
class XMLRPCServer {
   
    private $serverHandler;
   
    private $externalFunctions;
   
    public function __construct() {
        $this->serverHandler = xmlrpc_server_create();
        $this->externalFunctions = array();
    }
   
    public function registerMethod($externalName, $function, $parameterNames) {
        if($function == null) $function = $externalName;
        xmlrpc_server_register_method($this->serverHandler, $externalName, array(&$this, 'callMethod'));
        $this->externalFunctions[$externalName] =
            array('function'       => $function,
                  'parameterNames' => $parameterNames);
    }
   
    public function callMethod($functionName, $parametersFromRequest) {
        $function = $this->externalFunctions[$functionName]['function'];
        $parameterNames = $this->externalFunctions[$functionName]['parameterNames'];
        $parameters = array();
        foreach($parameterNames as $parameterName) {
            $parameters[] = $parametersFromRequest[0][$parameterName];
        }
        return call_user_func_array($function, $parameters);
    }
   
    public function computeAnswer() {
        return xmlrpc_server_call_method($this->serverHandler, file_get_contents('php://input'), null);
    }
}

// USAGE EXAMPLE HERE
$xmlRPCServer = new XMLRPCServer();
$someServer = new SomeXmlRPCServer($xmlRPCServer);
$answer = $xmlRPCServer->computeAnswer();
header('Content-Type: text/xml');
print($answer);


class SomeXmlRPCServer{
   
    private $xmlRPCServer;
   
    public function __construct($xmlRPCServer) {
        $this->xmlRPCServer = $xmlRPCServer;
        $this->xmlRPCServer->registerMethod(
            'ping',        // The name the XML-RPC Client calls
            array(&$this, 'pingInternal'),    // Pointer to the method, can be a simple string if you have global functions
            array('text')        // Name of the parameters and their ordering
        );
    }
   
    public function pingInternal($text) {
        if($text == "ping") return "pong";
		else return "pong";
    }
}
?>