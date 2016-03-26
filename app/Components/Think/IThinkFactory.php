<?php
namespace SharingThinks\Components\Think;

interface IThinkFactory
{
    /**
     * @return Think
     */
    public function create();
}