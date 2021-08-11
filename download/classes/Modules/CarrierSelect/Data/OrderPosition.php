<?php

declare(strict_types=1);

namespace Xentral\Modules\CarrierSelect\Data;


final class OrderPosition
{
    /** @var int $articleId */
    private $articleId = 0;

    /** @var float $quantity */
    private $quantity = 0;

    /** @var float $weight */
    private $weight = 0;

    /** @var float $length */
    private $length = 0;

    /** @var float $height */
    private $height = 0;

    /** @var float $width */
    private $width = 0;

    /**
     * OrderPosition constructor.
     *
     * @param array $position
     */
    public function __construct(array $position = [])
    {
        if (empty($position)) {
            return;
        }
        if (isset($position['artikel'])) {
            $this->setArticleId((int)$position['artikel']);
        }
        if (isset($position['menge'])) {
            $this->setQuantity((float)$position['menge']);
        }
        if (isset($position['gewicht'])) {
            $this->setWeight((float)str_replace(',', '.', $position['gewicht']));
        }
        if (isset($position['laenge'])) {
            $this->setLength((float)str_replace(',', '.', $position['laenge']));
        }
        if (isset($position['breite'])) {
            $this->setWidth((float)str_replace(',', '.', $position['breite']));
        }
        if (isset($position['hoehe'])) {
            $this->setHeight((float)str_replace(',', '.', $position['hoehe']));
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'artikel' => $this->articleId,
            'menge'   => $this->quantity,
            'gewicht' => $this->weight,
            'breite'  => $this->width,
            'hoehe'   => $this->height,
            'laenge'  => $this->length,
        ];
    }

    /**
     * @return int
     */
    public function getArticleId(): int
    {
        return $this->articleId;
    }

    /**
     * @param int $articleId
     */
    public function setArticleId(int $articleId): void
    {
        $this->articleId = $articleId;
    }

    /**
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

    /**
     * @param float $quantity
     */
    public function setQuantity(float $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return float
     */
    public function getWeight(): float
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     */
    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }

    /**
     * @return float
     */
    public function getVolume(): float
    {
        return $this->width * $this->height * $this->length;
    }

    /**
     * @return float
     */
    public function getLength(): float
    {
        return $this->length;
    }

    /**
     * @param float $length
     */
    public function setLength(float $length): void
    {
        $this->length = $length;
    }

    /**
     * @return float
     */
    public function getHeight(): float
    {
        return $this->height;
    }

    /**
     * @param float $height
     */
    public function setHeight(float $height): void
    {
        $this->height = $height;
    }

    /**
     * @return float
     */
    public function getWidth(): float
    {
        return $this->width;
    }

    /**
     * @param float $width
     */
    public function setWidth(float $width): void
    {
        $this->width = $width;
    }

}
