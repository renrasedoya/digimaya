<?php

/**
 * Custom Purifier config for Digimaya blog.
 *
 * 'blog' profile: matches Quill toolbar (h2, h3, bold, italic, link, ol, ul) + paragraph/br.
 * Strict whitelist — blocks iframe, script, style, img, video, h1, h4-h6, etc.
 */

return [
    'encoding'      => 'UTF-8',
    'finalize'      => true,
    'ignoreNonStrings' => false,
    'cachePath'     => storage_path('app/purifier'),
    'cacheFileMode' => 0755,
    'settings'      => [
        'default' => [
            'HTML.Doctype'             => 'HTML 4.01 Transitional',
            'HTML.Allowed'             => 'div,b,strong,i,em,u,a[href|title],ul,ol,li,p[style],br,span[style],img[width|height|alt|src],*[style|class]',
            'CSS.AllowedProperties'    => 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align',
            'AutoFormat.AutoParagraph' => false,
            'AutoFormat.RemoveEmpty'   => true,
        ],
        'blog' => [
            'HTML.Doctype'             => 'HTML 4.01 Transitional',
            'HTML.Allowed'             => 'p,br,h2,h3,strong,b,em,i,a[href|title|target|rel],ul,ol,li',
            'HTML.SafeIframe'          => false,
            'AutoFormat.AutoParagraph' => false,
            'AutoFormat.RemoveEmpty'   => true,
            'AutoFormat.Linkify'       => false,
            'URI.AllowedSchemes'       => [
                'http'   => true,
                'https'  => true,
                'mailto' => true,
                'tel'    => true,
            ],
            'Attr.AllowedRel'          => ['nofollow', 'noopener', 'noreferrer'],
            'Attr.AllowedFrameTargets' => ['_blank'],
        ],
        'test' => [
            'Attr.EnableID' => 'true',
        ],
        "youtube" => [
            "HTML.SafeIframe"      => 'true',
            "URI.SafeIframeRegexp" => "%^(http://|https://|//)(www.youtube.com/embed/|player.vimeo.com/video/)%",
        ],
        'custom_definition' => [
            'id'  => 'html5-definitions',
            'rev' => 1,
            'debug' => false,
            'elements' => [],
        ],
    ],
];
