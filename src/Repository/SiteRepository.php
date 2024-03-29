<?php

namespace Kata\Repository;

use Faker;
use Kata\Entity\Site;

class SiteRepository implements Repository
{
    use \Kata\Helper\SingletonTrait;

    private $url;

    /**
     * SiteRepository constructor.
     *
     */
    public function __construct()
    {
        // DO NOT MODIFY THIS METHOD
        $this->url = Faker\Factory::create()->url;
    }

    /**
     * @param int $id
     *
     * @return Site
     */
    public function getById(int $id)
    {
        // DO NOT MODIFY THIS METHOD
        return new Site($id, $this->url);
    }
}
