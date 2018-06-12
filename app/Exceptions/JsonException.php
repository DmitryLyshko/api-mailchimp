<?php

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * Базовый класс исключений в api.
 *
 * Class JsonException
 * @package App\Exceptions
 */
class JsonException extends Exception
{
    protected $type;
    protected $status;
    protected $errors = [];
    protected $reason;
    protected $subject;
    protected $subjectType;

    public function __construct($type, $message, $code = 0, Throwable $previous = null)
    {
        $this->type = $type;
        $this->status = $code;
        $this->setMessage($message);

        $msg_str = '| ';
        foreach ($this->errors as $error) {
            $msg_str .= $error['message'] . ' | ';
        }

        parent::__construct((string) trim($msg_str, '| '), $this->status, $previous);
    }

    /**
     * @return array
     */
    public function getErrorData() : array
    {
        return ['errors' => array_values($this->errors)];
    }

    /**
     * @return integer
     */
    public function getStatus(): int
    {
        return $this->status;
    }


    /**
     * @param string $reason
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @param string $subjectType
     */
    public function setSubjectType($subjectType)
    {
        $this->subjectType = $subjectType;
    }

    /**
     *
     * @param $message
     */
    public function setMessage($message)
    {
        if (is_a($message, 'Illuminate\Validation\Validator')) {
            foreach ($message->errors()->getMessages() as $parameter => $error) {
                $this->errors[] = $this->createErrorData($error[0]);
            }
        } else {
            $this->errors[] = $this->createErrorData($message);
        }
    }

    /**
     * @param string $message
     * @return array
     */
    private function createErrorData(string $message) : array
    {
        $error_data = [
            'type' => $this->type,
            'message' => $message
        ];

        if ($this->reason) {
            $error_data['reason'] = $this->reason;
        }

        if ($this->subject) {
            $error_data['subject'] = $this->subject;
        }

        if ($this->subjectType) {
            $error_data['subjectType'] = $this->subjectType;
        }

        return $error_data;
    }

    public function render()
    {
        return response(
            $this->getErrorData(),
            $this->getStatus()
        );
    }
}
