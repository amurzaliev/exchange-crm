<?php

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MainContext
{
    /**
     * @When /^я вижу слово "([^"]*)" где\-то на странице$/
     */
    public function яВижуСловоГдеТоНаСтранице($arg1)
    {
        $this->assertPageContainsText($arg1);
    }

    /**
     * @When /^я нахожусь на главной странице$/
     */
    public function яНахожусьНаГлавнойСтранице()
    {
        $this->visit($this->getContainer()->get('router')->generate('main_index'));
    }
}

