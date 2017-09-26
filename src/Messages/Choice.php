<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: messages.proto


namespace Messages;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;
/**
 * Protobuf type <code>Messages.Choice</code>
 */
class Choice extends \Google\Protobuf\Internal\Message
{
    /**
     * <code>int32 id_message = 1;</code>
     */
    private $id_message = 0;
    /**
     * <code>int32 id_search = 2;</code>
     */
    private $id_search = 0;
    /**
     * <code>string name_search = 3;</code>
     */
    private $name_search = '';
    /**
     * <code>float place_holder = 4;</code>
     */
    private $place_holder = 0.0;

    public function __construct() {
        \GPBMetadata\Messages::initOnce();
        parent::__construct();
    }

    /**
     * <code>int32 id_message = 1;</code>
     */
    public function getIdMessage()
    {
        return $this->id_message;
    }

    /**
     * <code>int32 id_message = 1;</code>
     */
    public function setIdMessage($var)
    {
        GPBUtil::checkInt32($var);
        $this->id_message = $var;
    }

    /**
     * <code>int32 id_search = 2;</code>
     */
    public function getIdSearch()
    {
        return $this->id_search;
    }

    /**
     * <code>int32 id_search = 2;</code>
     */
    public function setIdSearch($var)
    {
        GPBUtil::checkInt32($var);
        $this->id_search = $var;
    }

    /**
     * <code>string name_search = 3;</code>
     */
    public function getNameSearch()
    {
        return $this->name_search;
    }

    /**
     * <code>string name_search = 3;</code>
     */
    public function setNameSearch($var)
    {
        GPBUtil::checkString($var, True);
        $this->name_search = $var;
    }

    /**
     * <code>float place_holder = 4;</code>
     */
    public function getPlaceHolder()
    {
        return $this->place_holder;
    }

    /**
     * <code>float place_holder = 4;</code>
     */
    public function setPlaceHolder($var)
    {
        GPBUtil::checkFloat($var);
        $this->place_holder = $var;
    }

}

