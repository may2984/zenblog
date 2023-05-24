<?php

namespace App\Enums;

enum PostState: String
{
    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';
}