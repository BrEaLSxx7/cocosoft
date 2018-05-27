<?php

class Request
{
    private $post;
    private $get;
    private $put;
    private $delete;
    public function __construct(array $post, array $get, array $put, array $delete)
    {
        $this->post=$post;
        $this->get=$get;
        $this->put=$put;
        $this->delete=$delete;
    }

    /**
     * @return array
     */
    public function getPost(): array
    {
        return $this->post;
    }

    /**
     * @return array
     */
    public function getGet(): array
    {
        return $this->get;
    }

    /**
     * @return array
     */
    public function getPut(): array
    {
        return $this->put;
    }

    /**
     * @return array
     */
    public function getDelete(): array
    {
        return $this->delete;
    }
}