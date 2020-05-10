<?php


namespace App\Service;


use App\Model\ActivityModel;

class ActivityStatusUpdater
{
    public function __construct()
    {
    }


    public function updateStatus(ActivityModel $activityModel, $status)
    {
        // The aim is to change the status so set value to opposite of submitted
        switch ($status){
            case '0':
                $status = 1;
                break;
            case '1':
                $status = 0;
                break;
        }

        $activityModel->setStatus($status);
        return $activityModel;

    }
}