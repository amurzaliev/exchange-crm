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

    /**
     * @When /^я нажимаю на кнопку: ([^"]*)$/
     * @param $button
     */
    public function ЯНажимаюНаКнопку($button)
    {
        $this->pressButton($button);
    }


        /**
         * @When /^я нажимаю на ссылку: ([^"]*)$/
         * @param $link
         */
        public function ЯНажимаюНаСсылку($link)
    {
        try {
            $this->getSession()->getPage()->clickLink($link);
        } catch (\Behat\Mink\Exception\ElementNotFoundException $e) {
            throw new \LogicException('Нету');
        }
    }


    /**
     * @When /^я захожу на сайт как "([^"]*)" с паролем "([^"]*)"$/
     * @param $username
     * @param $password
     */
    public function яАвторизуюсьКак($username, $password)
    {
        $this->visit($this->getContainer()->get('router')->generate('fos_user_security_login'));
        $this->fillField('username', $username);
        $this->fillField('password', $password);
        $this->pressButton('Войти');
    }

    /**
     * @When /^я завершаю сеанс$/
     */
    public function яЗавершаюСеанс()
    {
        $this->visit($this->getContainer()->get('router')->generate('fos_user_security_logout'));
    }





}

