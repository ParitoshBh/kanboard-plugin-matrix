<?php

namespace Kanboard\Plugin\Matrix;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;

class Plugin extends Base
{
    public function initialize()
    {
        $this->template->hook->attach('template:config:integrations', 'matrix:integration');
        // $this->template->hook->attach('template:project:integrations', 'matrix:project/integration');
        $this->template->hook->attach('template:user:integrations', 'matrix:integration');

        // $this->projectNotificationTypeModel->setType('matrix', t('Matrix'), '\Kanboard\Plugin\Matrix\Notification\Matrix');
        $this->userNotificationTypeModel->setType('matrix', t('Matrix'), '\Kanboard\Plugin\Matrix\Notification\Matrix');
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }

    public function getPluginName()
    {
        return 'Matrix';
    }

    public function getPluginDescription()
    {
        return t('Receive notifications on Matrix');
    }

    public function getPluginAuthor()
    {
        return 'Paritosh Bhatia';
    }

    public function getPluginVersion()
    {
        return '1.0.0';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/ParitoshBh/kanboard-plugin-matrix';
    }
}

