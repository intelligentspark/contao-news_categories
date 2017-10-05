<?php

namespace NewsCategories;

class ModuleNewsCategoryHeader extends \ModuleNews {

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_newscategory_header';

    /**
     * Active category
     * @var object
     */
    protected $objActiveCategory = null;

    /**
     * Display a wildcard in the back end
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE') {
            $objTemplate = new \BackendTemplate('be_wildcard');

            $objTemplate->wildcard = '### NEWS CATEGORY HEADER ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

            return $objTemplate->parse();
        }

        $strClass = \NewsCategories\NewsCategories::getModelClass();

        $strParam = NewsCategories::getParameterName();

        // Get the active category
        if (\Input::get($strParam) != '') {

            return parent::generate();
        }

        return false;
    }

    public function compile() {

        $strClass = \NewsCategories\NewsCategories::getModelClass();

        $strParam = NewsCategories::getParameterName();

        // Get the active category
        if (\Input::get($strParam) != '') {
            $this->objActiveCategory = $strClass::findPublishedByIdOrAlias(\Input::get($strParam));

            if ($this->objActiveCategory !== null) {
                $this->arrCategoryTrail = $this->Database->getParentRecords($this->objActiveCategory->id, 'tl_news_category');

                // Remove the current category from the trail
                unset($this->arrCategoryTrail[array_search($this->objActiveCategory->id, $this->arrCategoryTrail)]);
            }

            $this->Template->addImage = $this->objActiveCategory->addImage;

            if($this->objActiveCategory->addImage=='1') {
                $objFile = \FilesModel::findByUuid($this->objActiveCategory->singleSRC);

                $this->Template->image = $objFile->path;
            }else{
                $this->Template->image = false;
            }


            $this->Template->frontendTitle = $this->objActiveCategory->frontendTitle;
            $this->Template->description = $this->objActiveCategory->description;
        }

    }
}