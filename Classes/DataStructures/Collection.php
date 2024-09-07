<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\DataStructures;

use OliverKlee\Oelib\Model\AbstractModel;

/**
 * This class represents a list of models.
 *
 * @template-covariant M of AbstractModel
 * @extends \SplObjectStorage<M, int>
 */
class Collection extends \SplObjectStorage
{
    /**
     * @var array<positive-int, positive-int> the UIDs in the list using the UIDs as both the keys and values
     */
    private array $uids = [];

    /**
     * The model this List belongs to.
     *
     * This is used for modeling relations and will remain null in any other context.
     */
    private ?AbstractModel $parentModel = null;

    /**
     * whether the parent model is the owner (which is the case for IRRE relations).
     */
    private bool $parentIsOwner = false;

    /**
     * whether there is at least one item without a UID
     */
    private bool $hasItemWithoutUid = false;

    /**
     * Adds a model to this list (as last element) if it is not already in the list.
     *
     * The model to add need not necessarily have a UID.
     *
     * @param M $model the model to add, need not have a UID
     *
     * @throws \UnexpectedValueException
     */
    public function add(AbstractModel $model): void
    {
        $this->attach($model);

        $uid = $model->getUid();
        if ($uid > 0) {
            $this->uids[$uid] = $uid;
        } else {
            $this->hasItemWithoutUid = true;
        }

        // Initializes the Iterator.
        if ($this->count() === 1) {
            $this->rewind();
        }

        $this->markAsDirty();
    }

    /**
     * Checks whether this list is empty.
     *
     * @return bool TRUE if this list is empty, FALSE otherwise
     */
    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    /**
     * Returns the first item.
     *
     * Note: This method rewinds the iterator.
     *
     * @return M|null
     */
    public function first(): ?AbstractModel
    {
        $this->rewind();
        if (!$this->valid()) {
            return null;
        }

        /** @var M $current */
        $current = $this->current();

        return $current;
    }

    /**
     * Returns a comma-separated list of unique UIDs of the current items, ordered by first insertion.
     *
     * @return string comma-separated list of UIDs, will be empty if the list is empty or no item has a UID
     *
     * @internal
     */
    public function getUids(): string
    {
        $this->checkUidCache();
        return implode(',', $this->uids);
    }

    /**
     * Checks whether a model with a certain UID exists in this list
     *
     * @param positive-int $uid UID to test
     *
     * @return bool TRUE if a model with the UID $uid exists in this list, FALSE otherwise
     */
    public function hasUid(int $uid): bool
    {
        $this->checkUidCache();
        return isset($this->uids[$uid]);
    }

    /**
     * Checks whether the UID list cache needs to be rebuilt and does so if necessary.
     */
    private function checkUidCache(): void
    {
        if ($this->hasItemWithoutUid) {
            $this->rebuildUidCache();
        }
    }

    /**
     * Rebuilds the UID cache.
     */
    private function rebuildUidCache(): void
    {
        $this->hasItemWithoutUid = false;

        /** @var M $item */
        foreach ($this as $item) {
            if ($item->hasUid()) {
                $uid = $item->getUid();
                \assert($uid > 0);
                $this->uids[$uid] = $uid;
            } else {
                $this->hasItemWithoutUid = true;
            }
        }
    }

    /**
     * Sorts this list by using the given callback function.
     *
     * The callback function, must take 2 parameters and return -1, 0 or 1.
     * The return value -1 means that the first parameter is sorted before the
     * second one, 1 means that the second parameter is sorted before the first
     * one and 0 means the parameters stay in order.
     *
     * @param callable $callbackFunction callback function to use with the models stored in the list
     */
    public function sort(callable $callbackFunction): void
    {
        $items = iterator_to_array($this, false);
        usort($items, $callbackFunction);

        /** @var M $item */
        foreach ($items as $item) {
            $this->detach($item);
            $this->attach($item);
        }

        $this->markAsDirty();
    }

    /**
     * Appends the contents of `$list` to this list.
     *
     * Note: Since Collection extends `\SplObjectStorage`, this method is in most
     * cases a synonym to `appendUnique()` as `\SplObjectStorage` makes sure that
     * no object is added more than once to it.
     *
     * @param Collection<AbstractModel> $list the list to append, may be empty
     */
    public function append(Collection $list): void
    {
        /** @var M $item */
        foreach ($list as $item) {
            $this->add($item);
        }
    }

    /**
     * Drops the current element from the list and sets the pointer to the next element.
     *
     * If the pointer does not point to a valid element, this function is a no-op.
     */
    public function purgeCurrent(): void
    {
        if (!$this->valid()) {
            return;
        }

        $current = $this->current();
        if ($current->hasUid()) {
            $uid = $current->getUid();
            if (isset($this->uids[$uid])) {
                unset($this->uids[$uid]);
            }
        }

        $this->detach($current);

        $this->markAsDirty();
    }

    /**
     * Returns the model this list belongs to.
     *
     * @internal
     */
    public function getParentModel(): ?AbstractModel
    {
        return $this->parentModel;
    }

    /**
     * Sets the model this list belongs to.
     *
     * @internal
     */
    public function setParentModel(AbstractModel $model): void
    {
        $this->parentModel = $model;
    }

    /**
     * Checks whether this relation is owner by the parent model.
     *
     * @internal
     */
    public function isRelationOwnedByParent(): bool
    {
        return $this->parentIsOwner;
    }

    /**
     * Marks this relation as owned by the parent model.
     *
     * @internal
     */
    public function markAsOwnedByParent(): void
    {
        $this->parentIsOwner = true;
    }

    /**
     * Marks the parent model as dirty.
     *
     * @internal
     */
    protected function markAsDirty(): void
    {
        if ($this->parentModel instanceof AbstractModel) {
            $this->parentModel->markAsDirty();
        }
    }

    /**
     * Returns the elements of this list in an array.
     *
     * @return list<M> the elements of this list, might be empty
     *
     * @internal
     */
    public function toArray(): array
    {
        /** @var list<M> $elements */
        $elements = [];
        foreach ($this as $item) {
            $elements[] = $item;
        }

        return $elements;
    }
}
