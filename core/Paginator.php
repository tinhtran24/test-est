<?php namespace core;

class Paginator
{
    private $currentPage;
    private $itemAllCount;
    private $pageLimit;
    private $lastPage;

    public function __construct(int $currentPage, int $itemAllCount, int $pageLimit)
    {
        $this->currentPage = $currentPage;
        $this->itemAllCount = $itemAllCount;
        $this->pageLimit = $pageLimit;
        $this->lastPage = $this->calcLastPage($itemAllCount, $pageLimit);
    }

    public function getLastPage(): int
    {
        return $this->lastPage;
    }

    public function calcItemsOffset(): int
    {
        return ($this->currentPage - 1) * $this->pageLimit;
    }

    private function calcLastPage(int $itemAllCount, int $pageLimit): int
    {
        $last = ceil($itemAllCount / $pageLimit);
        return ($last == 0 ? 1 : $last);
    }
}