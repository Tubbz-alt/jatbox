<?php

use \Behat\Behat\Hook\Scope\AfterScenarioScope;
use \Behat\Testwork\Tester\Result\TestResult;

/**
 * Features context.
 */
class FeatureContext extends \Behat\MinkExtension\Context\MinkContext
{
    /**
     * The path to the folder in which screenshots are to be stored
     *
     * @var string
     */
    protected $screenshotFolder;

    /**
     * Initializes context.
     * Every scenario gets its own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        $this->screenshotFolder = $parameters['error_screenshot_folder'];
    }

    /**
     * Used by the Wikipedia demo test to wait for the dropdown suggestion
     * box to appear when text has been typed into the search bar. This
     * is probably not useful in real tests and can be safely deleted.
     *
     * @Given /^I wait for the suggestion box to appear$/
     */
    public function iWaitForTheSuggestionBoxToAppear()
    {
        $this->getSession()->wait(
            5000,
            "$('.suggestions-results').children().length > 0"
        );
    }

    /**
     * Take a screenshot of failed tests to assist in debugging.
     * @AfterScenario
     * @param AfterScenarioScope $event
     */
    public function generateScreenshotOnError(AfterScenarioScope $event)
    {
        // If the result is an error...
        if ($event->getTestResult()->getResultCode() == TestResult::FAILED) {
            $profileName = $this->getCurrentProfileName();
            $featureTitle = $event->getFeature()->getTitle();
            $scenarioTitle = $event->getScenario()->getTitle();
            $filename = $this->screenshotFolder . DIRECTORY_SEPARATOR . "$scenarioTitle.$featureTitle.$profileName.jpg";
            if (!file_exists($this->screenshotFolder)) {
                mkdir($this->screenshotFolder, 0755, true);
            }

            try {
                file_put_contents(
                    $filename,
                    $this->getSession()->getDriver()->getScreenshot()
                );
            } catch (\Behat\Mink\Exception\UnsupportedDriverActionException $e) {
                // Do nothing - this exception is generated by drivers like
                // Goutte that don't have the facility to generate screenshots
            }
        }
    }

    /**
     * Gets the name of the current Behat configuration profile
     * @return string
     */
    protected function getCurrentProfileName()
    {
        $input   = new \Symfony\Component\Console\Input\ArgvInput($_SERVER['argv']);
        $profileName = $input->getParameterOption(array('--profile', '-p')) ? : 'default';
        return $profileName;
    }
}
