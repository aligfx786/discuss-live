<?php

namespace App\Enums;

// Post statuses difference:
enum PostStatus: string
{
    case Published = 'published'; // Visible to all
    case Draft = 'draft';        // Only visible to author, not finalized
    case Hidden = 'hidden';      // Temporarily removed from public view
    case Archived = 'archived';  // Permanently removed but preserved for records
}
