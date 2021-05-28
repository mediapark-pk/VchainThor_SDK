<?php
declare(strict_types=1);

namespace MediaParkPK\VeChainThor\Blocks;

/**
 * Class Block
 * @package MediaParkPK\VeChainThor\Blocks
 */
class Block
{
    /** @var int|mixed  */
    public int $number;
    /** @var string|mixed  */
    public string $id;
    /** @var int|mixed  */
    public int $size;
    /** @var string|mixed  */
    public string $parentID;
    /** @var int|mixed  */
    public int $timestamp;
    /** @var int|mixed  */
    public int $gasLimit;
    /** @var string|mixed  */
    public string $beneficiary;
    /** @var int|mixed  */
    public int $gasUsed;
    /** @var int|mixed  */
    public int $totalScore;
    /** @var string|mixed  */
    public string $txsRoot;
    /** @var int|mixed  */
    public int $txsFeatures;
    /** @var string|mixed  */
    public string $stateRoot;
    /** @var string|mixed  */
    public string $receiptsRoot;
    /** @var string|mixed  */
    public string $signer;
    /** @var bool|mixed  */
    public bool $isTrunk;
    /** @var array|mixed  */
    public array $transactions;

    /**
     * Block constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->number = $data["number"];
        $this->id = $data["id"];
        $this->size = $data["size"];
        $this->parentID = $data["parentID"];
        $this->timestamp = $data["timestamp"];
        $this->gasLimit = $data["gasLimit"];
        $this->beneficiary = $data["beneficiary"];
        $this->gasUsed = $data["gasUsed"];
        $this->totalScore = $data["totalScore"];
        $this->txsRoot = $data["txsRoot"];
        $this->txsFeatures = $data["txsFeatures"];
        $this->stateRoot = $data["stateRoot"];
        $this->receiptsRoot = $data["receiptsRoot"];
        $this->signer = $data["signer"];
        $this->isTrunk = $data["isTrunk"];
        $this->transactions = $data["transactions"];
    }
}