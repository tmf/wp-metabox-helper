<?php

use Tmf\WordPressExtension\Context\WordPressContext;
use Behat\Behat\Context\SnippetAcceptingContext;

/**
 * Defines application features from the specific context.
 */
class MetaboxFeatureContext extends WordPressContext implements SnippetAcceptingContext
{
    /**
     * @When I fill in the post title with :title
     */
    public function iFillInThePostTitleWith($title)
    {
        $this->fillField('title', $title);
        $this->getSession()->wait(1000, "jQuery('[name=\"save\"]').hasClass('disabled') == false");
    }

    /**
     * @When I select :item from the dropdown :label
     */
    public function iSelectFromTheDropdown($item, $label)
    {
        $session = $this->getSession();
        $session->wait(10000, 'typeof jQuery.fn.selectize === "function"');
        $session->executeScript(sprintf('jQuery("label:contains(\"%s\") + select")[0].selectize.addItem("%s")', $label, $item));
    }
}
