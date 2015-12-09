<?php

use \Behat\Behat\Event\BaseScenarioEvent;
use \Behat\Behat\Event\OutlineExampleEvent;
use \Behat\Behat\Event\ScenarioEvent;

/**
 * Features context.
 */
class FeatureContext extends \Behat\MinkExtension\Context\MinkContext
{
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
     * @param BaseScenarioEvent $event
     */
    public function generateScreenshotOnError(BaseScenarioEvent $event)
    {
        // If the result is an error...
        if (4 == $event->getResult()) {
            $profileName = $this->getCurrentProfileName();
            $featureTitle = $this->getFeatureTitle($event);
            $scenarioTitle = $this->getScenarioTitle($event);
            $filename = $this->screenshotFolder . DIRECTORY_SEPARATOR . "$scenarioTitle.$featureTitle.$profileName.jpg";
            if (!file_exists($this->screenshotFolder)) {
                mkdir($this->screenshotFolder, 0755, true);
            }
            file_put_contents(
                $filename,
                $this->getSession()->getDriver()->getScreenshot()
            );
        }
    }

    /**
     * Get the title of the event
     * @param BaseScenarioEvent $event
     * @return string
     * @throws Exception if event type is not handled
     */
    public function getScenarioTitle(BaseScenarioEvent $event) {
        if (method_exists($event, 'getScenario'))
            return $event->getScenario()->getTitle();
        if ($event instanceof OutlineExampleEvent) {
            $outlineTitle = $event->getOutline()->getTitle();
            $outlineIteration = $event->getIteration();
            return "$outlineTitle $outlineIteration";
        }
        throw new Exception("Unknown event type " . get_class($event));
    }

    /**
     * Get the title of the feature containing the event
     * @param BaseScenarioEvent $event
     * @return string
     * @throws Exception if event type is not handled
     */
    public function getFeatureTitle(BaseScenarioEvent $event) {
        if ($event instanceof ScenarioEvent)
            return $event->getScenario()->getFeature()->getTitle();
        if ($event instanceof OutlineExampleEvent) {
            return $event->getOutline()->getFeature()->getTitle();
        }
        throw new Exception("Unknown event type " . get_class($event));
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
