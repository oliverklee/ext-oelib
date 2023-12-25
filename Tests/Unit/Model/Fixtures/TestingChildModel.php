<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Unit\Model\Fixtures;

use OliverKlee\Oelib\Model\AbstractModel;

/**
 * This class represents a domain model for testing purposes.
 */
final class TestingChildModel extends AbstractModel
{
    public function getTitle(): string
    {
        return $this->getAsString('title');
    }

    public function setTitle(string $value): void
    {
        $this->setAsString('title', $value);
    }

    public function getParent(): ?TestingModel
    {
        /** @var TestingModel|null $model */
        $model = $this->getAsModel('parent');

        return $model;
    }

    public function setParent(TestingModel $parent): void
    {
        $this->set('parent', $parent);
    }

    /**
     * Gets the "tx_oelib_parent2" data item.
     */
    public function getParent2(): ?TestingModel
    {
        /** @var TestingModel|null $model */
        $model = $this->getAsModel('tx_oelib_parent2');

        return $model;
    }

    /**
     * Sets the "tx_oelib_parent2" data item.
     */
    public function setParent2(TestingModel $parent): void
    {
        $this->set('tx_oelib_parent2', $parent);
    }
}
