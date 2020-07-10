<?php

namespace egor260890\feedback\repositories;

use egor260890\feedback\entities\Feedback;
use egor260890\feedback\exceptions\NotFoundException;

class FeedbackRepository {

    /**
     * @param $id
     *
     * @return Feedback
     */
    public function get($id): Feedback {
        if (!$feedback = Feedback::findOne($id)) {
            throw new NotFoundException('Feedback is not found.');
        }

        return $feedback;
    }

    /**
     * @param Feedback $feedback
     */
    public function save(Feedback $feedback) {
        if (!$feedback->validate()) {
            throw new \RuntimeException('Feedback. Validate error.');
        }

        if (!$feedback->save()) {
            throw new \RuntimeException('Feedback. Saving error.');
        }
    }

    /**
     * @param Feedback $feedback
     *
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function remove(Feedback $feedback) {
        if (!$feedback->delete()) {
            throw new \RuntimeException('Feedback. Removing error.');
        }
    }
}
