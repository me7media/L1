<?php

namespace App\Messages;


class EmailMessage
{
    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $body;
    /**
     * @var string
     */
    private $from;
    /**
     * @var string
     */
    private $to;

    /**
     * EmailMessage constructor.
     * @param string $from
     * @param string $to
     * @param string $title
     * @param string $body
     */
    public function __construct(string $from, string $to, string $title, string $body)
    {
        $this->from = $from;
        $this->to = $to;
        $this->title = $title;
        $this->body = $body;

    }
    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }
    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }
    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }
}