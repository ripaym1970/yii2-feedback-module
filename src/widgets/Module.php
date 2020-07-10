<?php

namespace egor260890\feedback\widgets;

class Module extends \yii\base\Module {

    public $controllerNamespace = 'egor260890\feedback\widgets\controllers';
    public $defaultRoute        = 'send';
    public $observers           = null;
}
