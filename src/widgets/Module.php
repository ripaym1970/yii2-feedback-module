<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 04.05.2018
 * Time: 9:28
 */

namespace egor260890\feedback\widgets;

class Module extends \yii\base\Module {

    public $controllerNamespace = 'egor260890\feedback\widgets\controllers';

    public $defaultRoute='send';

    public $observers=null;
}
