<?php
declare(strict_types=1);

namespace MediaParkPK\VeChainThor;

/**
 * Class Validate
 * @package MediaParkPK\VeChainThor
 */
class Validate
{
    /**
     * @param $address
     * @return bool
     */
    public static function Address($address) : bool
    {
        if (is_string($address) && preg_match('/^[a-zA-Z0-9]{42}$/', $address)) {
            return true;
        }

        return false;
    }
}