<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: book.proto

namespace GPBMetadata;

class Book
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
          return;
        }
        $pool->internalAddGeneratedFile(hex2bin(
            "0a8a010a0a626f6f6b2e70726f746f1207636174616c6f67223a0a0c4361" .
            "74616c6f675f4974656d121b0a04626f6f6b18012003280b320d2e636174" .
            "616c6f672e426f6f6b120d0a057175616e74180220012805222f0a04426f" .
            "6f6b120a0a026964180120012805120c0a046e616d65180220012809120d" .
            "0a057072696365180320012802620670726f746f33"
        ));

        static::$is_initialized = true;
    }
}

