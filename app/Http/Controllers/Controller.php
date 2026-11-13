<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

abstract class Controller
{
    /**
     * Strip HTML tags, trim whitespace, and normalize blank text to null.
     */
    protected function sanitizeText(?string $text): ?string
    {
        if (! filled($text)) {
            return null;
        }

        $sanitized = strip_tags(trim($text));

        return filled($sanitized) ? $sanitized : null;
    }

    /**
     * Resolve the stored public image path after replacement or removal.
     */
    protected function resolveUpdatedPublicImage(
        Request $request,
        ?string $currentPath,
        string $directory,
        bool $removeImage = false,
    ): ?string {
        if ($request->hasFile('image')) {
            if ($currentPath) {
                Storage::disk('public')->delete($currentPath);
            }

            return $request->file('image')->store($directory, 'public');
        }

        if ($removeImage && $currentPath) {
            Storage::disk('public')->delete($currentPath);

            return null;
        }

        return $currentPath;
    }
}
