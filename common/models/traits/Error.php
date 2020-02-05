<?php
namespace common\models\traits;

trait Error
{
    public function getFirstErrorString()
    {
        $errors = $this->getFirstErrors();
        return is_array($errors) ? reset($errors) : '';
    }
}
