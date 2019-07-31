<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 05.05.2018
 * Time: 9:20
 */

namespace egor260890\feedback\listeners;

use egor260890\feedback\entities\FeedbackInterface;

interface FeedbackObserverInterface {

    function update(FeedbackInterface $feedback);
}
