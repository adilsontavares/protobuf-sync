<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: messages.proto

namespace Messages;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>Messages.Books</code>
 */
class Books extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>repeated .Messages.Book books = 1;</code>
     */
    private $books;

    public function __construct() {
        \GPBMetadata\Messages::initOnce();
        parent::__construct();
    }

    /**
     * Generated from protobuf field <code>repeated .Messages.Book books = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getBooks()
    {
        return $this->books;
    }

    /**
     * Generated from protobuf field <code>repeated .Messages.Book books = 1;</code>
     * @param \Messages\Book[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setBooks($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \Messages\Book::class);
        $this->books = $arr;

        return $this;
    }

}

