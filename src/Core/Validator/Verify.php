<?php

namespace NINA\Core\Validator;
use Illuminate\Http\UploadedFile;

trait Verify
{
    public function min($value, array $rules, float $min): void
    {
        switch (true) {
            case in_array('number', $rules):
                if ($min > (int) $value) {
                    $this->pushErrorMessage($this->current, $this->buildErrorMessage([$this->current, 'number'], __FUNCTION__, [
                        'min' => $min
                    ]));
                }
                break;
            case in_array('file', $rules) || in_array('video', $rules) || in_array('audio', $rules) || in_array('image', $rules):
                $sizeMb = $value->getSize() / 1000 / 1000;
                if ($min > $sizeMb) {
                    $this->pushErrorMessage($this->current, $this->buildErrorMessage([$this->current, 'file'], __FUNCTION__, [
                        'min' => $min
                    ]));
                }
                break;
            case 'string':
            default:
                if (strlen((string) $value) < $min) {
                    $this->pushErrorMessage($this->current, $this->buildErrorMessage([$this->current, 'string'], __FUNCTION__, [
                        'min' => $min
                    ]));
                }
        }
    }
    public function max($value, array $rules, float $max): void
    {
        switch (true) {
            case in_array('number', $rules):
                if ($max < (int) $value) {
                    $this->pushErrorMessage($this->current, $this->buildErrorMessage([$this->current, 'number'], __FUNCTION__, [
                        'max' => $max
                    ]));
                }
                break;
            case in_array('file', $rules) || in_array('video', $rules) || in_array('audio', $rules) || in_array('image', $rules):
                $sizeMb = $value->getSize() / 1000 / 1000;
                if ($max < $sizeMb) {
                    $this->pushErrorMessage($this->current, $this->buildErrorMessage([$this->current, 'file'], __FUNCTION__, [
                        'max' => $max
                    ]));
                }
                break;
            case 'string':
            default:
                if (strlen((string) $value) > $max) {
                    $this->pushErrorMessage($this->current, $this->buildErrorMessage([$this->current, 'string'], __FUNCTION__, [
                        'max' => $max
                    ]));
                }
        }
    }
    public function number($value): void
    {
        if (!is_numeric($value)) {
            $this->pushErrorMessage($this->current, $this->buildErrorMessage($this->current, __FUNCTION__));
        }
    }
    public function string($value): void
    {
        if (!is_string($value)) {
            $this->pushErrorMessage($this->current, $this->buildErrorMessage($this->current, __FUNCTION__));
        }
    }
    public function required($value): void
    {
        if (empty($value)) {
            $this->pushErrorMessage($this->current, $this->buildErrorMessage($this->current, __FUNCTION__));
        }
    }
    public function file($value): void
    {
        if (!$value instanceof UploadedFile) {
            $this->pushErrorMessage($this->current, $this->buildErrorMessage($this->current, __FUNCTION__));
        }
    }
    public function image($value): void
    {

        if (!$value instanceof UploadedFile || strpos($value->getMimeType(), 'image/') === false) {
            $this->pushErrorMessage($this->current, $this->buildErrorMessage($this->current, __FUNCTION__));
        }
    }
    public function audio($value): void
    {
        if (!$value instanceof UploadedFile || strpos($value->getMimeType(), 'audio/') === true) {
            $this->pushErrorMessage($this->current, $this->buildErrorMessage($this->current, __FUNCTION__));
        }
    }
    public function video($value): void
    {
        if (!$value instanceof UploadedFile || strpos($value->getMimeType(), 'video/') === true) {
            $this->pushErrorMessage($this->current, $this->buildErrorMessage($this->current, __FUNCTION__));
        }
    }
    public function email($value): void
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->pushErrorMessage($this->current, $this->buildErrorMessage($this->current, __FUNCTION__));
        }
    }
    public function unique($value, $ruleValue): void
    {
        list($table, $columnValue) = explode(',', $ruleValue);
        if (str_contains($columnValue, ';')) {
            list($column, $keyValue) = explode(';', $columnValue);
        } else {
            $column = $columnValue;
        }

        $table = DB::table($table)->where($column, $value)->first();
        if ($table && isset($keyValue) && $table->$column != $keyValue || $table && !isset($keyValue)) {
            $this->pushErrorMessage($this->current, $this->buildErrorMessage($this->current, __FUNCTION__));
        }
    }
    public function handleCustomRule($rule): void
    {
        $handle = $this->getCustom($rule);
        if (!$handle($this->passable)) {
            $this->pushErrorMessage($this->current, $this->customMessages[$rule]);
        }
    }
}