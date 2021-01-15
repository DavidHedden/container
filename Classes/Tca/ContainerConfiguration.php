<?php

namespace B13\Container\Tca;

/*
 * This file is part of TYPO3 CMS-based extension "container" by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

class ContainerConfiguration
{
    /**
     * @var string
     */
    protected $cType = '';

    /**
     * @var string
     */
    protected $label = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var mixed[]
     */
    protected $grid = [];

    /**
     * @var string
     */
    protected $icon = 'EXT:container/Resources/Public/Icons/Extension.svg';

    /**
     * @var string
     */
    protected $backendTemplate = 'EXT:container/Resources/Private/Templates/Container.html';

    /**
     * @var string
     */
    protected $gridTemplate = 'EXT:container/Resources/Private/Templates/Grid.html';

    /**
     * @var bool
     */
    protected $saveAndCloseInNewContentElementWizard = true;

    /**
     * @var bool
     */
    protected $registerInNewContentElementWizard = true;

    /**
     * @var string
     */
    protected $group = 'container';

    public function __construct(
        $cType,
        $label,
        $description,
        array $grid
    ) {
        $this->cType = $cType;
        $this->label = $label;
        $this->description = $description;
        $this->grid = $grid;
    }

    /**
     * @param string $icon
     * @return ContainerConfiguration
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @param string $backendTemplate
     * @return ContainerConfiguration
     */
    public function setBackendTemplate($backendTemplate)
    {
        $this->backendTemplate = $backendTemplate;
        return $this;
    }

    /**
     * @param string $gridTemplate
     * @return ContainerConfiguration
     */
    public function setGridTemplate($gridTemplate)
    {
        $this->gridTemplate = $gridTemplate;
        return $this;
    }

    /**
     * @param bool $saveAndCloseInNewContentElementWizard
     * @return ContainerConfiguration
     */
    public function setSaveAndCloseInNewContentElementWizard($saveAndCloseInNewContentElementWizard)
    {
        $this->saveAndCloseInNewContentElementWizard = $saveAndCloseInNewContentElementWizard;
        return $this;
    }

    /**
     * @param bool $registerInNewContentElementWizard
     * @return ContainerConfiguration
     */
    public function setRegisterInNewContentElementWizard($registerInNewContentElementWizard)
    {
        $this->registerInNewContentElementWizard = $registerInNewContentElementWizard;
        return $this;
    }

    /**
     * @param string $group
     * @return ContainerConfiguration
     */
    public function setGroup($group)
    {
        $this->group = $group;
        return $this;
    }

    /**
     * @return string
     */
    public function getCType()
    {
        return $this->cType;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return mixed[]
     */
    public function getGrid()
    {
        return $this->grid;
    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @return mixed[]
     */
    public function toArray()
    {
        return [
            'cType' => $this->cType,
            'icon' => $this->icon,
            'label' => $this->label,
            'description' => $this->description,
            'backendTemplate' => $this->backendTemplate,
            'grid' => $this->grid,
            'gridTemplate' => $this->gridTemplate,
            'saveAndCloseInNewContentElementWizard' => $this->saveAndCloseInNewContentElementWizard,
            'registerInNewContentElementWizard' => $this->registerInNewContentElementWizard,
            'group' => $this->group
        ];
    }
}
