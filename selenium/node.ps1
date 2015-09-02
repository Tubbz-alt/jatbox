##
# This file is part of the JAT-Box project
#
# node: Starts a Selenium Server from PowerShell if a node is not already 
# running on this machine. This script can be used on a slave machine to 
# connect it to the testing machine and allow the slave machine to run
# browser-dependent tests.
################################################################################
$DIR=Split-Path $script:MyInvocation.MyCommand.Path
$HUB_IP="192.168.33.11"
$HUB_PORT=4444
$RUNNING=get-WmiObject win32_process -Filter "name like '%java.exe'" | select CommandLine
if(!$RUNNING) {$RUNNING = $TRUE} else {$RUNNING = -Not "$RUNNING".Contains("selenium-server-standalone-2.45.0.jar -role node")}

if($RUNNING)
{
	"Starting Selenium Server node"
	cd $DIR
	java -jar  selenium-server-standalone-2.45.0.jar -role node -hub http://${HUB_IP}:${HUB_PORT}/grid/register '-browser browserName=internet explorer,maxInstances=5' '-Dwebdriver.chrome.driver=chromedriver.exe' '-Dwebdriver.ie.driver=IEDriverServer.exe'
	
}
else
{
	"Selenium Node Already Running"
}