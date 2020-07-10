<?php

namespace egor260890\feedback\listeners;

use egor260890\feedback\entities\FeedbackInterface;

interface FeedbackObserverInterface {

    function update(FeedbackInterface $feedback);
}
