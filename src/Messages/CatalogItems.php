<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: messages.proto

namespace Messages;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>Messages.CatalogItems</code>
 */
class CatalogItems extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>repeated .Messages.CatalogItem items = 1;</code>
     */
    private $items;

    public function __construct() {
        \GPBMetadata\Messages::initOnce();
        parent::__construct();
    }

    /**
     * Generated from protobuf field <code>repeated .Messages.CatalogItem items = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Generated from protobuf field <code>repeated .Messages.CatalogItem items = 1;</code>
     * @param \Messages\CatalogItem[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setItems($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Messages\CatalogItem::class);
        $this->items = $arr;

        return $this;
    }

}

