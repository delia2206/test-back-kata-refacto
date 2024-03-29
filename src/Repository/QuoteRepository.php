<?php

namespace Kata\Repository;

use Faker;
use Kata\Entity\Quote;

class QuoteRepository implements Repository
{
    use \Kata\Helper\SingletonTrait;

    private $siteId;
    private $destinationId;
    private $date;

    /**
     * QuoteRepository constructor.
     */
    public function __construct()
    {
        // DO NOT MODIFY THIS METHOD
        $generator = Faker\Factory::create();

        $this->siteId = $generator->numberBetween(1, 10);
        $this->destinationId = $generator->numberBetween(1, 200);
        $this->date = new \DateTime();
    }

    /**
     * @param int $id
     *
     * @return Quote
     */
    public function getById(int $id)
    {
        // DO NOT MODIFY THIS METHOD
        return new Quote(
            $id,
            $this->siteId,
            $this->destinationId,
            $this->date
        );
    }
}
