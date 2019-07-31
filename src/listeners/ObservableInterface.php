<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 05.05.2018
 * Time: 9:22
 */

namespace egor260890\feedback\listeners;

use egor260890\feedback\entities\FeedbackInterface;

interface ObservableInterface {

    function attach(FeedbackObserverInterface $observer): bool;

    function detach(FeedbackObserverInterface $observer, bool $compareByClassName);

    function notify(FeedbackInterface $feedback);
}
