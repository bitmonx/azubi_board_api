<?php


namespace App\Action;

/**
 * Class ResponseData.
 * Helps storing data for responses.
 * @package App\Action
 */
final class ResponseData
{
    /**
     * @var string $message
     */
    public $message;
    /**
     * @var object|array $data
     */
    public $data;

    /**
     * Sets the attributes
     * @param string $message
     * @param null $data
     */
    public function build(string $message, $data = null): void{
        $this->message = $message;
        $this->data = $data;
    }
}
