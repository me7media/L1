<?php

namespace App\DB;

class JsonDB implements DB
{
    private $path;
    const FILLENAME = __DIR__.'/data/database.json';

    public function __construct($path = null)
    {
        $this->path = $path ?: self::FILLENAME;
        if (!file_exists($this->path)) {
            echo 'error file database not found';
            exit();
        }
    }

    /**
     * Checks whether json file exists.
     *
     * @return bool
     */
    public function exists()
    {
        return is_file($this->path);
    }

    /**
     * @param $json
     * @param null $file
     *
     * @return bool|mixed
     */
    public function parseJson($json, $file = null)
    {
        if (null === $json) {
            return false;
        }
        $data = json_decode($json, true);
        if (null === $data && JSON_ERROR_NONE !== json_last_error()) {
            return false;
        }

        return $data;
    }

    /**
     * @return bool|mixed
     */
    public function read()
    {
        if (self::exists()) {
            $json = file_get_contents($this->path);

            return static::parseJson($json, $this->path);
        }

        return false;
    }

    /**
     * @param $data
     */
    public function write($data)
    {
        //todo validation
        $dir = dirname($this->path);
        if (is_dir($dir) && self::exists()) {
            file_put_contents($this->path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }

    public function printData()
    {
        $jsonData = self::read();
        $mask = "|%5s |%-20s | %11s | %10s | %10s |\n";
        printf($mask, 'Id', 'Name', 'Category', 'Price', 'Qt');

        foreach ($jsonData['products'] as $product) {
            printf($mask, $product['id'], $product['name'], @$jsonData['categories'][$product['category_id'] - 1]['name'], $product['price'], $product['qt']);
        }

        return true;
    }
}
