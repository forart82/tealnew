<?php

/**
 * @file
 * Contains class with static const tu get all entities names.
 */

namespace App\Services\Statics;

class Entities
{
    const ENTITIES = [
        "company" => "App\Entity\Company",
        "csvkeyvalues" => "App\Entity\CsvKeyValues",
        "emails" => "App\Entity\Emails",
        "keytext" => "App\Entity\Keytext",
        "language" => "App\Entity\Language",
        "navigations" => "App\Entity\Navigations",
        "result" => "App\Entity\Result",
        "subject" => "App\Entity\Subject",
        "svg" => "App\Entity\Svg",
        "translation" => "App\Entity\Translation",
        "user" => "App\Entity\User",
    ];
}
