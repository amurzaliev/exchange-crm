<?php

use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;
use Symfony\Component\HttpKernel\KernelInterface;


/**
 * Defines application features from the specific context.
 */
class MainContext extends RawMinkContext
{
    /** @var  KernelInterface */
    protected $kernel;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    protected function getContainer()
    {
        return $this->kernel->getContainer();
    }

    public function visit($page)
    {
        $this->visitPath($page);
    }

    public function reload()
    {
        $this->getSession()->reload();
    }

    public function back()
    {
        $this->getSession()->back();
    }


    public function forward()
    {
        $this->getSession()->forward();
    }

    public function pressButton($button)
    {
        $button = $this->fixStepArgument($button);
        $this->getSession()->getPage()->pressButton($button);
    }

    public function clickLink($link)
    {
        $link = $this->fixStepArgument($link);
        $this->getSession()->getPage()->clickLink($link);
    }


    public function fillField($field, $value)
    {
        $field = $this->fixStepArgument($field);
        $value = $this->fixStepArgument($value);
        $this->getSession()->getPage()->fillField($field, $value);
    }


    public function fillFields(TableNode $fields)
    {
        foreach ($fields->getRowsHash() as $field => $value) {
            $this->fillField($field, $value);
        }
    }


    public function selectOption($select, $option)
    {
        $select = $this->fixStepArgument($select);
        $option = $this->fixStepArgument($option);
        $this->getSession()->getPage()->selectFieldOption($select, $option);
    }


    public function additionallySelectOption($select, $option)
    {
        $select = $this->fixStepArgument($select);
        $option = $this->fixStepArgument($option);
        $this->getSession()->getPage()->selectFieldOption($select, $option, true);
    }

    public function checkOption($option)
    {
        $option = $this->fixStepArgument($option);
        $this->getSession()->getPage()->checkField($option);
    }

    public function uncheckOption($option)
    {
        $option = $this->fixStepArgument($option);
        $this->getSession()->getPage()->uncheckField($option);
    }

    public function attachFileToField($field, $path)
    {
        $field = $this->fixStepArgument($field);

        if ($this->getMinkParameter('files_path')) {
            $fullPath = rtrim(realpath($this->getMinkParameter('files_path')), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$path;
            if (is_file($fullPath)) {
                $path = $fullPath;
            }
        }

        $this->getSession()->getPage()->attachFileToField($field, $path);
    }

    public function assertPageAddress($page)
    {
        $this->assertSession()->addressEquals($this->locatePath($page));
    }


    public function assertHomepage()
    {
        $this->assertSession()->addressEquals($this->locatePath('/'));
    }


    public function assertUrlRegExp($pattern)
    {
        $this->assertSession()->addressMatches($this->fixStepArgument($pattern));
    }


    public function assertResponseStatus($code)
    {
        $this->assertSession()->statusCodeEquals($code);
    }


    public function assertResponseStatusIsNot($code)
    {
        $this->assertSession()->statusCodeNotEquals($code);
    }


    public function assertPageContainsText($text)
    {
        $this->assertSession()->pageTextContains($this->fixStepArgument($text));
    }


    public function assertPageNotContainsText($text)
    {
        $this->assertSession()->pageTextNotContains($this->fixStepArgument($text));
    }


    public function assertPageMatchesText($pattern)
    {
        $this->assertSession()->pageTextMatches($this->fixStepArgument($pattern));
    }


    public function assertPageNotMatchesText($pattern)
    {
        $this->assertSession()->pageTextNotMatches($this->fixStepArgument($pattern));
    }


    public function assertResponseContains($text)
    {
        $this->assertSession()->responseContains($this->fixStepArgument($text));
    }


    public function assertResponseNotContains($text)
    {
        $this->assertSession()->responseNotContains($this->fixStepArgument($text));
    }


    public function assertElementContainsText($element, $text)
    {
        $this->assertSession()->elementTextContains('css', $element, $this->fixStepArgument($text));
    }


    public function assertElementNotContainsText($element, $text)
    {
        $this->assertSession()->elementTextNotContains('css', $element, $this->fixStepArgument($text));
    }


    public function assertElementContains($element, $value)
    {
        $this->assertSession()->elementContains('css', $element, $this->fixStepArgument($value));
    }


    public function assertElementNotContains($element, $value)
    {
        $this->assertSession()->elementNotContains('css', $element, $this->fixStepArgument($value));
    }


    public function assertElementOnPage($element)
    {
        $this->assertSession()->elementExists('css', $element);
    }


    public function assertElementNotOnPage($element)
    {
        $this->assertSession()->elementNotExists('css', $element);
    }


    public function assertFieldContains($field, $value)
    {
        $field = $this->fixStepArgument($field);
        $value = $this->fixStepArgument($value);
        $this->assertSession()->fieldValueEquals($field, $value);
    }


    public function assertFieldNotContains($field, $value)
    {
        $field = $this->fixStepArgument($field);
        $value = $this->fixStepArgument($value);
        $this->assertSession()->fieldValueNotEquals($field, $value);
    }


    public function assertNumElements($num, $element)
    {
        $this->assertSession()->elementsCount('css', $element, intval($num));
    }


    public function assertCheckboxChecked($checkbox)
    {
        $this->assertSession()->checkboxChecked($this->fixStepArgument($checkbox));
    }


    public function assertCheckboxNotChecked($checkbox)
    {
        $this->assertSession()->checkboxNotChecked($this->fixStepArgument($checkbox));
    }


    public function printCurrentUrl()
    {
        echo $this->getSession()->getCurrentUrl();
    }


    public function printLastResponse()
    {
        echo (
            $this->getSession()->getCurrentUrl()."\n\n".
            $this->getSession()->getPage()->getContent()
        );
    }


    public function showLastResponse()
    {
        if (null === $this->getMinkParameter('show_cmd')) {
            throw new \RuntimeException('Set "show_cmd" parameter in behat.yml to be able to open page in browser (ex.: "show_cmd: firefox %s")');
        }

        $filename = rtrim($this->getMinkParameter('show_tmp_dir'), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.uniqid().'.html';
        file_put_contents($filename, $this->getSession()->getPage()->getContent());
        system(sprintf($this->getMinkParameter('show_cmd'), escapeshellarg($filename)));
    }


    public static function getTranslationResources()
    {
        return self::getMinkTranslationResources();
    }


    public static function getMinkTranslationResources()
    {
        return glob(__DIR__.'/../../../../i18n/*.xliff');
    }


    protected function fixStepArgument($argument)
    {
        return str_replace('\\"', '"', $argument);
    }
}
