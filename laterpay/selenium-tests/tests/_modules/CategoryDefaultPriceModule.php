<?php

class CategoryDefaultPriceModule extends BaseModule {

    //pricing tab elements
    public static $pricingAddCategoryButton     = '#add_category_button';
    public static $pricingCategorySelect        = '#select2-drop-mask';
    public static $pricingCategorySelectOption  = '.category-title';
    public static $pricingSaveLink              = ".edit-link.laterpay-save-link";
    public static $pricingCancelLink            = ".edit-link.laterpay-cancel-link";
    public static $pricingChangeLink            = ".edit-link.laterpay-change-link";
    public static $pricingDeleteLink            = ".edit-link.laterpay-delete-link";
    public static $pricingPriceInput            = ".lp-input.number";
    public static $pricingCurrency              = "#laterpay_currency";

    //message
    public static $messageCategoryPriceSave     = "All posts in category {category_name} have a default price of {category_price}";
    public static $messageCategoryPriceDeleted  = "The default price for this category was deleted.";
    public static $messageCategoryPriceChanged  = "All posts in category {category_name} have a default price of {category_price}";
    public static $messageCurrencyChanged       = "The currency for this website is USD now.";

    /**
     * @param $category_name
     * @param $category_default_price
     * @return $this
     */
    public function createCategoryDefaultPrice($category_name, $category_default_price) {
        $I = $this->BackendTester;

        $I->amGoingTo('Open LaterPay plugin page pricing tab');
        $I->amOnPage(self::$baseUrl);
        $I->click(self::$adminMenuPluginButton);
        $I->click(self::$pluginPricingTab);

        $I->amGoingTo('Add category default price');
        $I->click(self::$pricingAddCategoryButton);
        $I->seeElement(self::$pricingCategorySelect);
        $I->seeElement(self::$pricingSaveLink);
        $I->seeElement(self::$pricingCancelLink);

        $I->amGoingTo('Validate Price');
        BackendModule::of($I)
            ->validatePrice(self::$pricingPriceInput, self::$pricingCancelLink, self::$pricingSaveLink);

        $I->amGoingTo('Cancel category default price');
        $I->click(self::$pricingAddCategoryButton);
        $I->seeElement(self::$pricingAddCategoryButton);
        $I->dontSeeElement(self::$pricingCancelLink);
        $I->dontSeeElement(self::$pricingSaveLink);

        $I->amGoingTo('Add category default price');
        $I->click(self::$pricingAddCategoryButton);
        $I->seeElement(self::$pricingCategorySelect);
        $I->seeElement(self::$pricingSaveLink);
        $I->seeElement(self::$pricingCancelLink);

        $I->amGoingTo('Fill and save category default price');
        $I->click(self::$pricingCategorySelect);
        $I->click($category_name, self::$pricingCategorySelectOption);
        $I->fillField(self::$pricingPriceInput, $category_default_price);
        $I->click(self::$pricingSaveLink);
        $I->seeElement(self::$pricingChangeLink);
        $I->seeElement(self::$pricingDeleteLink);
        $I->dontSeeElement(self::$pricingCancelLink);
        $I->dontSeeElement(self::$pricingSaveLink);
        $messageCategoryPriceSaveText = str_replace(
            array('{category_name}', '{category_price}'),
            array($category_name, $category_default_price),
            self::$messageCategoryPriceSave
        );
        $I->waitForText($messageCategoryPriceSaveText, self::$shortTimeout, self::$messageArea);

        return $this;
    }

    /**
     * @param $category_name
     * @return $this
     */
    public function deleteCategoryDefaultPrice($category_name) {
        $I = $this->BackendTester;

        $I->amGoingTo('Open LaterPay plugin page pricing tab');
        $I->amOnPage(self::$baseUrl);
        $I->click(self::$adminMenuPluginButton);
        $I->click(self::$pluginPricingTab);

        $I->amGoingTo('Delete category default price');
        //TODO: implement deletion of concrete category
        $I->click(self::$pricingDeleteLink);
        $I->waitForText(self::$messageCategoryPriceDeleted, self::$shortTimeout, self::$messageArea);

        return $this;
    }

    /**
     * @param $category_name
     * @param $new_category_default_price
     * @return $this
     */
    public function changeCategoryDefaultPrice($category_name, $new_category_default_price) {
        $I = $this->BackendTester;

        $I->amGoingTo('Open LaterPay plugin page pricing tab');
        $I->amOnPage(self::$baseUrl);
        $I->click(self::$adminMenuPluginButton);
        $I->click(self::$pluginPricingTab);

        $I->amGoingTo('Change category default price');
        //TODO: implement change of concrete category
        $I->click(self::$pricingChangeLink);
        $I->seeElement(self::$pricingCategorySelect);
        $I->seeElement(self::$pricingSaveLink);
        $I->seeElement(self::$pricingCancelLink);

        $I->amGoingTo('Fill and save category default price');
        $I->fillField(self::$pricingPriceInput, $new_category_default_price);
        $I->click(self::$pricingSaveLink);
        $I->seeElement(self::$pricingChangeLink);
        $I->seeElement(self::$pricingDeleteLink);
        $I->dontSeeElement(self::$pricingCancelLink);
        $I->dontSeeElement(self::$pricingSaveLink);
        $messageCategoryPriceChangeText = str_replace(
            array('{category_name}', '{category_price}'),
            array($category_name, $new_category_default_price),
            self::$messageCategoryPriceSave
        );
        $I->waitForText($messageCategoryPriceChangeText, self::$shortTimeout, self::$messageArea);

        return $this;
    }

    /**
     * @param $new_currency
     * @return $this
     */
    public function changeCurrency($new_currency)
    {
        $I = $this->BackendTester;

        $I->amGoingTo('Open LaterPay plugin page pricing tab');
        $I->amOnPage(self::$baseUrl);
        $I->click(self::$adminMenuPluginButton);
        $I->click(self::$pluginPricingTab);

        $I->selectOption(self::$pricingCurrency, $new_currency);
        $I->see(self::$messageCurrencyChanged, self::$messageArea);
        $I->see($new_currency, self::$pricingCurrency);

        return $this;
    }
}