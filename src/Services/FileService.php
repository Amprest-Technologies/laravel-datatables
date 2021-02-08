<?php

namespace Amprest\LaravelDatatables\Services;

class FileService
{
    /**
     * Generate datatables payload.
     *
     * @param string $file
     * @param string $mimeType
     * @return array
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     */
    public function load($file, string $mimeType = 'application/javascript')
    {
        //  Determine the expiry time 
        $expires = strtotime('+1 year');

        //  Determine the last modified time
        $lastModified = filemtime($file);

        //  Create the cache control string
        $cacheControl = 'public, max-age=31536000';

        //  if the server modification date matches the file date
        if ($this->matchesCache($lastModified)) {
            return response()->make('', 304, [
                'Expires' => $this->httpDate($expires),
                'Cache-Control' => $cacheControl,
            ]);
        }

        //  Return the flle as a response type
        return response()->file($file, [
            'Content-Type' => "$mimeType; charset=utf-8",
            'Expires' => $this->httpDate($expires),
            'Cache-Control' => $cacheControl,
            'Last-Modified' => $this->httpDate($lastModified),
        ]);
    }

    /**
     * Check if the server modifed data is similar to when the file
     * was last modified
     *
     * @param string $lastModified
     * @return bool
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     */
    protected function matchesCache($lastModified): bool
    {
        return @strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'] ?? '') === $lastModified;
    }

    /**
     * Determine the http date
     *
     * @param string $timestamp
     * @author Alvin Gichira Kaburu <geekaburu@amprest.co.ke>
     */
    protected function httpDate($timestamp)
    {
        return sprintf('%s GMT', gmdate('D, d M Y H:i:s', $timestamp));
    }
}