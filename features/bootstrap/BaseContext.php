<?php

use Behat\Behat\Context\TranslatableContext;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Gherkin\Node\TableNode;
use Symfony\Component\HttpKernel\KernelInterface;

class BaseContext extends RawMinkContext implements TranslatableContext
{
    /** @var  KernelInterface */
    protected $kernel;

    public $routes = [];

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        $this->routes = [
            'Главная страница' => 'main_index'
        ];
    }

    protected function getContainer()
    {
        return $this->kernel->getContainer();
    }

    /**
     * @When /^я нахожусь на главной странице$/
     */
    public function iAmOnHomepage()
    {
        $this->visitPath($this->getContainer()->get('router')->generate($this->routes['Главная страница']));
    }

    /**
     * @When /^я перехожу по маршруту "([^"]*)"$/
     *
     * @param $route
     */
    public function visitRoute($route)
    {
        if (array_key_exists($route, $this->routes)) {
            $this->visitPath($this->getContainer()->get('router')->generate($this->routes['Главная страница']));
        } else {
            throw new \LogicException("Маршрут {$route} не найден в routes");
        }

    }

    /**
     * @When /^я перезагружаю страницу$/
     */
    public function reload()
    {
        $this->getSession()->reload();
    }

    /**
     * @When /^я перехожу назад на страницу$/
     */
    public function back()
    {
        $this->getSession()->back();
    }

    /**
     * @When /^я перехожу вперед на страницу$/
     */
    public function forward()
    {
        $this->getSession()->forward();
    }

    /**
     * @When /^я нажимаю на кнопку "([^"]*)"$/
     *
     * @param $button
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function pressButton($button)
    {
        $button = $this->fixStepArgument($button);
        $this->getSession()->getPage()->pressButton($button);
    }

    /**
     * @When /^я нажимаю на ссылку "([^"]*)"$/
     *
     * @param $link
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function clickLink($link)
    {
        $link = $this->fixStepArgument($link);
        $this->getSession()->getPage()->clickLink($link);
    }

    /**
     * @When /^я заполняю поле формы "([^"]*)" значением "([^"]*)"$/
     *
     * @param $field
     * @param $value
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function fillField($field, $value)
    {
        $field = $this->fixStepArgument($field);
        $value = $this->fixStepArgument($value);
        $this->getSession()->getPage()->fillField($field, $value);
    }

    /**
     * Example: When I fill in the following"
     *              | username | bruceWayne |
     *              | password | iLoveBats123 |
     *
     * @When /^я заполняю поля формы:$/
     *
     * @param TableNode $fields
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function fillFields(TableNode $fields)
    {
        foreach ($fields->getRowsHash() as $field => $value) {
            $this->fillField($field, $value);
        }
    }

    /**
     * @When /^я выбираю опцию "([^"]*)" из поля выбора "([^"]*)"$/
     *
     * @param $select
     * @param $option
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function selectOption($option, $select)
    {
        $select = $this->fixStepArgument($select);
        $option = $this->fixStepArgument($option);
        $this->getSession()->getPage()->selectFieldOption($select, $option);
    }

    /**
     * @When /^я дополнительно выбираю опцию "([^"]*)" из поля выбора "([^"]*)"$/
     *
     * @param $select
     * @param $option
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function additionallySelectOption($option, $select)
    {
        $select = $this->fixStepArgument($select);
        $option = $this->fixStepArgument($option);
        $this->getSession()->getPage()->selectFieldOption($select, $option, true);
    }

    /**
     * @When /^я отмечаю галочкой опцию "([^"]*)"$/
     *
     * @param $option
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function checkOption($option)
    {
        $option = $this->fixStepArgument($option);
        $this->getSession()->getPage()->checkField($option);
    }

    /**
     * @When /^я снимаю галочку с опции "([^"]*)"$/
     *
     * @param $option
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function uncheckOption($option)
    {
        $option = $this->fixStepArgument($option);
        $this->getSession()->getPage()->uncheckField($option);
    }

    /**
     * @When /^я прикрепляю файл "([^"]*)" к полю "([^"]*)"$/
     *
     * @param $field
     * @param $path
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function attachFileToField($path, $field)
    {
        $field = $this->fixStepArgument($field);

        if ($this->getMinkParameter('files_path')) {
            $fullPath = rtrim(realpath($this->getMinkParameter('files_path')), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $path;
            if (is_file($fullPath)) {
                $path = $fullPath;
            }
        }

        $this->getSession()->getPage()->attachFileToField($field, $path);
    }

    /**
     * Checks, that current page PATH is equal to specified
     * Example: Then I should be on "/"
     * Example: And I should be on "/bats"
     * Example: And I should be on "http://google.com"
     *
     * @Then /^(?:|I )should be on "(?P<page>[^"]+)"$/
     * @param $page
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function assertPageAddress($page)
    {
        $this->assertSession()->addressEquals($this->locatePath($page));
    }

    /**
     * @Then /^я должен быть на главной странице$/
     *
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function assertHomepage()
    {
        $this->assertSession()->addressEquals($this->locatePath('/'));
    }

    /**
     * Checks, that current page PATH matches regular expression
     * Example: Then the url should match "superman is dead"
     * Example: Then the uri should match "log in"
     * Example: And the url should match "log in"
     *
     * @Then /^the (?i)url(?-i) should match (?P<pattern>"(?:[^"]|\\")*")$/
     * @param $pattern
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function assertUrlRegExp($pattern)
    {
        $this->assertSession()->addressMatches($this->fixStepArgument($pattern));
    }

    /**
     * @Then /^код ответа сервера должен быть ([^"]*)$/
     *
     * @param $code
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function assertResponseStatus($code)
    {
        $this->assertSession()->statusCodeEquals((int)$code);
    }

    /**
     * @Then /^код ответа сервера НЕ должен быть ([^"]*)$/
     *
     * @param $code
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function assertResponseStatusIsNot($code)
    {
        $this->assertSession()->statusCodeNotEquals((int)$code);
    }

    /**
     * @Then /^я вижу слово "([^"]*)" где-то на странице$/
     *
     * @param $text
     * @throws \Behat\Mink\Exception\ResponseTextException
     */
    public function assertPageContainsText($text)
    {
        $this->assertSession()->pageTextContains($this->fixStepArgument($text));
    }

    /**
     * @Then /^я не должен видеть слово "([^"]*)" где-то на странице$/
     *
     * @param $text
     * @throws \Behat\Mink\Exception\ResponseTextException
     */
    public function assertPageNotContainsText($text)
    {
        $this->assertSession()->pageTextNotContains($this->fixStepArgument($text));
    }

    /**
     * @Then /^я вижу текст совпадающий ([^"]*)$/
     *
     * @param $pattern
     * @throws \Behat\Mink\Exception\ResponseTextException
     */
    public function assertPageMatchesText($pattern)
    {
        $this->assertSession()->pageTextMatches($this->fixStepArgument($pattern));
    }

    /**
     * @Then /^я НЕ вижу текст совпадающий ([^"]*)$/
     *
     * @param $pattern
     * @throws \Behat\Mink\Exception\ResponseTextException
     */
    public function assertPageNotMatchesText($pattern)
    {
        $this->assertSession()->pageTextNotMatches($this->fixStepArgument($pattern));
    }

    /**
     * Checks, that HTML response contains specified string
     * Example: Then the response should contain "Batman is the hero Gotham deserves."
     * Example: And the response should contain "Batman is the hero Gotham deserves."
     *
     * @Then /^the response should contain "(?P<text>(?:[^"]|\\")*)"$/
     * @param $text
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function assertResponseContains($text)
    {
        $this->assertSession()->responseContains($this->fixStepArgument($text));
    }

    /**
     * Checks, that HTML response doesn't contain specified string
     * Example: Then the response should not contain "Bruce Wayne is a billionaire, play-boy, vigilante."
     * Example: And the response should not contain "Bruce Wayne is a billionaire, play-boy, vigilante."
     *
     * @Then /^the response should not contain "(?P<text>(?:[^"]|\\")*)"$/
     * @param $text
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function assertResponseNotContains($text)
    {
        $this->assertSession()->responseNotContains($this->fixStepArgument($text));
    }

    /**
     * @Then /^я вижу слово "([^"]*)" в "([^"]*)" элементе$/
     *
     * @param $element
     * @param $text
     * @throws \Behat\Mink\Exception\ElementTextException
     */
    public function assertElementContainsText($text, $element)
    {
        $this->assertSession()->elementTextContains('css', $element, $this->fixStepArgument($text));
    }

    /**
     * @Then /^я НЕ вижу слово "([^"]*)" в "([^"]*)" элементе$/
     *
     * @param $element
     * @param $text
     * @throws \Behat\Mink\Exception\ElementTextException
     */
    public function assertElementNotContainsText($text, $element)
    {
        $this->assertSession()->elementTextNotContains('css', $element, $this->fixStepArgument($text));
    }

    /**
     * Checks, that element with specified CSS contains specified HTML
     * Example: Then the "body" element should contain "style=\"color:black;\""
     * Example: And the "body" element should contain "style=\"color:black;\""
     *
     * @Then /^the "(?P<element>[^"]*)" element should contain "(?P<value>(?:[^"]|\\")*)"$/
     *
     * @param $element
     * @param $value
     * @throws \Behat\Mink\Exception\ElementHtmlException
     */
    public function assertElementContains($element, $value)
    {
        $this->assertSession()->elementContains('css', $element, $this->fixStepArgument($value));
    }

    /**
     * Checks, that element with specified CSS doesn't contain specified HTML
     * Example: Then the "body" element should not contain "style=\"color:black;\""
     * Example: And the "body" element should not contain "style=\"color:black;\""
     *
     * @Then /^the "(?P<element>[^"]*)" element should not contain "(?P<value>(?:[^"]|\\")*)"$/
     *
     * @param $element
     * @param $value
     * @throws \Behat\Mink\Exception\ElementHtmlException
     */
    public function assertElementNotContains($element, $value)
    {
        $this->assertSession()->elementNotContains('css', $element, $this->fixStepArgument($value));
    }

    /**
     * @Then /^я вижу "([^"]*)" css элемент на странице$/
     *
     * @param $element
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function assertElementOnPage($element)
    {
        $this->assertSession()->elementExists('css', $element);
    }

    /**
     * @Then /^я НЕ вижу "([^"]*)" css элемент на странице$/
     *
     * @param $element
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function assertElementNotOnPage($element)
    {
        $this->assertSession()->elementNotExists('css', $element);
    }

    /**
     * @Then /^поле формы "([^"]*)" должно содержать значение "([^"]*)"$/
     *
     * @param $field
     * @param $value
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function assertFieldContains($field, $value)
    {
        $field = $this->fixStepArgument($field);
        $value = $this->fixStepArgument($value);
        $this->assertSession()->fieldValueEquals($field, $value);
    }

    /**
     * @Then /^поле формы "([^"]*)" НЕ должно содержать значение "([^"]*)"$/
     *
     * @param $field
     * @param $value
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function assertFieldNotContains($field, $value)
    {
        $field = $this->fixStepArgument($field);
        $value = $this->fixStepArgument($value);
        $this->assertSession()->fieldValueNotEquals($field, $value);
    }

    /**
     * Checks, that (?P<num>\d+) CSS elements exist on the page
     * Example: Then I should see 5 "div" elements
     * Example: And I should see 5 "div" elements
     *
     * @Then /^(?:|I )should see (?P<num>\d+) "(?P<element>[^"]*)" elements?$/
     *
     * @param $num
     * @param $element
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function assertNumElements($num, $element)
    {
        $this->assertSession()->elementsCount('css', $element, intval($num));
    }

    /**
     * @Then /^поле выбора "([^"]*)" должно быть отмечено галочкой$/
     *
     * @param $checkbox
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function assertCheckboxChecked($checkbox)
    {
        $this->assertSession()->checkboxChecked($this->fixStepArgument($checkbox));
    }

    /**
     * @Then /^поле выбора "([^"]*)" НЕ должно быть отмечено галочкой$/
     *
     * @param $checkbox
     * @throws \Behat\Mink\Exception\ExpectationException
     */
    public function assertCheckboxNotChecked($checkbox)
    {
        $this->assertSession()->checkboxNotChecked($this->fixStepArgument($checkbox));
    }

    /**
     * Prints current URL to console.
     * Example: Then print current URL
     * Example: And print current URL
     *
     * @Then /^print current URL$/
     */
    public function printCurrentUrl()
    {
        echo $this->getSession()->getCurrentUrl();
    }

    /**
     * Prints last response to console
     * Example: Then print last response
     * Example: And print last response
     *
     * @Then /^print last response$/
     */
    public function printLastResponse()
    {
        echo(
            $this->getSession()->getCurrentUrl() . "\n\n" .
            $this->getSession()->getPage()->getContent()
        );
    }

    /**
     * Opens last response content in browser
     * Example: Then show last response
     * Example: And show last response
     *
     * @Then /^show last response$/
     */
    public function showLastResponse()
    {
        if (null === $this->getMinkParameter('show_cmd')) {
            throw new \RuntimeException('Set "show_cmd" parameter in behat.yml to be able to open page in browser (ex.: "show_cmd: firefox %s")');
        }

        $filename = rtrim($this->getMinkParameter('show_tmp_dir'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . uniqid() . '.html';
        file_put_contents($filename, $this->getSession()->getPage()->getContent());
        system(sprintf($this->getMinkParameter('show_cmd'), escapeshellarg($filename)));
    }

    /**
     * Returns list of definition translation resources paths
     *
     * @return array
     */
    public static function getTranslationResources()
    {
        return self::getMinkTranslationResources();
    }

    /**
     * Returns list of definition translation resources paths for this dictionary
     *
     * @return array
     */
    public static function getMinkTranslationResources()
    {
        return glob(__DIR__ . '/../../../../i18n/*.xliff');
    }

    /**
     * Returns fixed step argument (with \\" replaced back to ")
     *
     * @param string $argument
     *
     * @return string
     */
    protected function fixStepArgument($argument)
    {
        return str_replace('\\"', '"', $argument);
    }

    /**
     * @When /^я захожу на сайт как "([^"]*)" с паролем "([^"]*)"$/
     *
     * @param $username
     * @param $password
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function iLoginAs($username, $password)
    {
        $this->visitPath($this->getContainer()->get('router')->generate('fos_user_security_login'));
        $this->fillField('username', $username);
        $this->fillField('password', $password);
        $this->pressButton('Войти');
    }

    /**
     * @When /^я завершаю сеанс$/
     */
    public function iLogout()
    {
        $this->visitPath($this->getContainer()->get('router')->generate('fos_user_security_logout'));
    }

    /** @BeforeScenario @loginUser
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function loginUserBeforeScenario()
    {
        $this->visitPath($this->getContainer()->get('router')->generate('fos_user_security_login'));
        $this->fillField('username', 'user@mail.ru');
        $this->fillField('password', '12345');
        $this->pressButton('Войти');
    }

    /** @BeforeScenario @loginOwner
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function loginOwnerBeforeScenario()
    {
        $this->visitPath($this->getContainer()->get('router')->generate('fos_user_security_login'));
        $this->fillField('username', 'owner@mail.ru');
        $this->fillField('password', '12345');
        $this->pressButton('Войти');
    }

    /** @BeforeScenario @loginAdmin
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function loginAdminBeforeScenario()
    {
        $this->visitPath($this->getContainer()->get('router')->generate('fos_user_security_login'));
        $this->fillField('username', 'admin@mail.ru');
        $this->fillField('password', '12345');
        $this->pressButton('Войти');
    }

    /**
     * @AsterScenario @logout
     */
    public function logoutAfterScenario()
    {
        $this->visitPath($this->getContainer()->get('router')->generate('fos_user_security_logout'));
    }
}