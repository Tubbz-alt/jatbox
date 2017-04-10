<?php
/**
 * Wrapper class for JATBox Selenium functionality
 */

require_once(__DIR__ . '/../scripts/common.php');

class Selenium {
    const SYS_LINUX_32 = 'linux32';
    const SYS_MAC_64 = 'mac64';
    const SYS_WIN_32 = 'win32';

    const HUB_IP = '192.168.33.11'; # This should be the same as the IP address configured in the private_network configuration line of Vagrantfile
    const HUB_PORT = '4444'; # 4444 is the default port for the server to listen on and shouldn't need to be changed

    private static function launchSeleniumServer ($javaArgs, $seleniumArgs)
    {
        self::assertSeleniumServerNotAlreadyRunning();
        $DIR = __DIR__;
        $COMMAND_TO_EXECUTE="java $javaArgs -jar $DIR/../selenium/selenium-server-standalone-3.0.1.jar $seleniumArgs > selenium_server.log";
        echo "Launching Selenium Server: $COMMAND_TO_EXECUTE";
        exec_in_background($COMMAND_TO_EXECUTE);
    }

    private static function assertSeleniumServerNotAlreadyRunning ()
    {
        if (get_os() == OS_WIN) {
            $filterResult = `get-WmiObject win32_process -Filter "name like '%java.exe'" | select CommandLine`;
            $isRunning = !$filterResult || `-Not "$filterResult".Contains("selenium-server-standalone-3.0.1.jar -role node")`;
        } else {
            // $jobCount will be 1 if Selenium Server is already running on this machine
            $jobCount = `ps -f | grep java.*jar.*selenium-server.* | grep -v grep | wc -l`;
            $isRunning = $jobCount > 0;
        }

        if ($isRunning) {
            throw new \Exception('Error: Selenium server is already running on this machine.');
        }
    }

    private static function validateSystem($system)
    {
        $validSystems = array( self::SYS_LINUX_32, self::SYS_MAC_64, self::SYS_WIN_32 );
        if (!in_array($system, $validSystems)) {
            throw new \Exception("Unexpected system '$system'. Expected one of " . implode(',', $validSystems));
        }
    }

    /**
     * @throws \Exception
     * @param string $system One of this class's SYS_* constants
     * @param string $browserArgs browser flag arguments to be passed to the selenium server.
     *     E.G. "-browser browserName=safari -browser browserName=opera" to register a node that can run tests in either
     *     Safari or Opera.
     */
    public static function launchNode ($system, $browserArgs)
    {
        self::validateSystem($system);

        $driverPath = __DIR__ . "/../selenium/driver/$system";
        if (!file_exists($driverPath)) {
            throw new \Exception("Couldn't find the driver directory at $driverPath");
        }

        $drivers = "-Dwebdriver.gecko.driver=$driverPath/geckodriver.exe -Dwebdriver.chrome.driver=$driverPath/chromedriver.exe";
        self::launchSeleniumServer($drivers, "-role node -hubHost " . self::HUB_IP . " -hubPort " . self::HUB_PORT . " " . $browserArgs);
    }

    public static function launchHub ()
    {
        self::launchSeleniumServer('', '-role hub');
    }
}