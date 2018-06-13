<?php

namespace DieSchittigs\ContaoGraphQLBundle;

use DieSchittigs\ContaoGraphQLBundle\Type\ContentElementType;
use DieSchittigs\ContaoGraphQLBundle\Type\QueryType;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\Type;

class Types
{
        // Object types:
        private static $query;
        private static $contentElement;
         /**
         * @return QueryType
         */
        public static function query()
        {
            return self::$query ?: (self::$query = new QueryType());
        }
        /**
         * @return ContentElementType
         */
        public static function contentElement()
        {
            return self::$contentElement ?: (self::$contentElement = new ContentElementType());
        }

        public static function boolean()
        {
            return Type::boolean();
        }
        /**
         * @return \GraphQL\Type\Definition\FloatType
         */
        public static function float()
        {
            return Type::float();
        }
        /**
         * @return \GraphQL\Type\Definition\IDType
         */
        public static function id()
        {
            return Type::id();
        }
        /**
         * @return \GraphQL\Type\Definition\IntType
         */
        public static function int()
        {
            return Type::int();
        }
        /**
         * @return \GraphQL\Type\Definition\StringType
         */
        public static function string()
        {
            return Type::string();
        }
        /**
         * @param Type $type
         * @return ListOfType
         */
        public static function listOf($type)
        {
            return new ListOfType($type);
        }
        /**
         * @param Type $type
         * @return NonNull
         */
        public static function nonNull($type)
        {
            return new NonNull($type);
        }
}