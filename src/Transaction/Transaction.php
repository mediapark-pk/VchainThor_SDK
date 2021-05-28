<?php
declare(strict_types=1);

namespace MediaParkPK\VeChainThor\Transaction;

/**
 * Class Transaction
 * @package MediaParkPK\VeChainThor\Transaction
 */
class Transaction
{
    /** @var string  */
    public string $id;
    /** @var int  */
    public int $chainTag;
    /** @var string  */
    public string $blockRef;
    /** @var int  */
    public int $expiration;
    /** @var array|null  */
    public ?array $clauses;
    /** @var int  */
    public int $gasPriceCoef;
    /** @var int  */
    public int $gas;
    /** @var string  */
    public string $origin;
    /** @var bool|mixed|null  */
    public ?bool $delegator = null;
    /** @var string  */
    public string $nonce;
    /** @var mixed|null  */
    public $dependsOn = null;
    /** @var int  */
    public int $size;
    /** @var array|null  */
    public ?array $meta;

    /**
     * Transaction constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->chainTag = $data["chainTag"];
        $this->blockRef = $data["blockRef"];
        $this->expiration = $data["expiration"];
        $this->clauses = $data["clauses"];
        $this->gasPriceCoef = $data["gasPriceCoef"];
        $this->gas = $data["gas"];
        $this->origin = $data["origin"];
        $this->delegator = $data["delegator"];
        $this->nonce = $data["nonce"];
        $this->dependsOn = $data["dependsOn"];
        $this->size = $data["size"];
        $this->meta = $data["meta"];
    }
}