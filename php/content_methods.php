<?php
/*
 * Helpers for rendering CMS content in the front-end sections (assets/sections/*.php).
 *
 * Text fields are stored HTML-escaped by the CMS (FILTER_SANITIZE_SPECIAL_CHARS).
 * cms_html() turns them back into HTML, so editors can use simple markup such as <br>.
 */

// Stored CMS value -> HTML
function cms_html($value) {
    return deepDecode((string)$value);
}

// Stored CMS value -> safe attribute / plain text value
function cms_attr($value) {
    return htmlspecialchars(trim(strip_tags(cms_html($value))), ENT_QUOTES, 'UTF-8');
}

// Content (rich text) of a content block or service as HTML
function cms_content($value) {
    return trim(cms_html($value));
}

// Inner HTML of every <tag> element, e.g. all <li> items of a list
function cms_elements($html, $tag = 'li') {
    preg_match_all('/<' . $tag . '(?:\s[^>]*)?>(.*?)<\/' . $tag . '>/is', (string)$html, $matches);
    return array_values(array_filter(array_map('trim', $matches[1]), 'strlen'));
}

// Add a class to every <tag> that does not have a class attribute yet
function cms_add_class($html, $tag, $class) {
    return preg_replace_callback('/<' . $tag . '(\s[^>]*)?>/i', function ($m) use ($tag, $class) {
        $attributes = $m[1] ?? '';
        if (stripos($attributes, 'class=') !== false) {
            return $m[0];
        }
        return '<' . $tag . ' class="' . $class . '"' . $attributes . '>';
    }, (string)$html);
}

// Replace <strong>/<b> by a span with the given class (theme styling)
function cms_strong_to_span($html, $class = 'font-weight-600') {
    $html = preg_replace('/<(strong|b)(\s[^>]*)?>/i', '<span class="' . $class . '">', (string)$html);
    return preg_replace('/<\/(strong|b)>/i', '</span>', $html);
}

// Plain text lines of a text: split on line breaks, paragraphs and list items
function cms_lines($html) {
    $html = preg_replace('/<br\s*\/?>|<\/p>|<\/li>|<\/div>/i', "\n", cms_html($html));
    $lines = array_map(function ($line) {
        return trim(html_entity_decode(strip_tags($line), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }, explode("\n", $html));
    return array_values(array_filter($lines, 'strlen'));
}

// Image url inside a CMS upload folder (path segments url encoded)
function cms_image($folder, $file) {
    $file = html_entity_decode((string)$file, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return SERVERPATH . '/images/' . $folder . '/' . implode('/', array_map('rawurlencode', explode('/', $file)));
}

// Link from the CMS: absolute urls and anchors stay as they are, others are relative to the site root
function cms_url($url) {
    $url = trim(html_entity_decode((string)$url, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    if ($url === '' || $url === '/') {
        return SERVERPATH;
    }
    if (preg_match('/^(https?:|mailto:|tel:|#|\/\/)/i', $url)) {
        return htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
    }
    // Encode every path segment (file names may contain spaces), keep a query string as it is
    $parts = explode('?', ltrim($url, '/'), 2);
    $path  = implode('/', array_map(function ($segment) {
        return rawurlencode(rawurldecode($segment));
    }, explode('/', $parts[0])));
    return htmlspecialchars(SERVERPATH . '/' . $path . (isset($parts[1]) ? '?' . $parts[1] : ''), ENT_QUOTES, 'UTF-8');
}

// Front page url and menu label
function cms_page_url($pageName) {
    return strtolower($pageName) === 'index' ? SERVERPATH : SERVERPATH . '/' . $pageName;
}

function cms_page_label($pageName) {
    return strtolower($pageName) === 'index' ? 'Home' : ucfirst(preg_replace('/[-_]+/', ' ', $pageName));
}

// Extra (JSON) setting of a content block
function cms_extra($fpc_obj, $key, $default = null) {
    $extra = $fpc_obj->getFpextraArray();
    return array_key_exists($key, $extra) ? $extra[$key] : $default;
}

// Optional anchor id of a content block (extra setting "anchor")
function cms_anchor($fpc_obj) {
    $anchor = cms_extra($fpc_obj, 'anchor');
    return $anchor ? ' id="' . htmlspecialchars($anchor, ENT_QUOTES, 'UTF-8') . '"' : '';
}

// Services linked to a front page
function cms_services_for_page($servicesList, $pageId) {
    return array_values(array_filter($servicesList, function ($service) use ($pageId) {
        return $service->getSname() == $pageId;
    }));
}

// Telephone link value
function cms_tel($phone) {
    return preg_replace('/[^0-9+]/', '', (string)$phone);
}

// Font Awesome icon for a social media platform from the system settings
function cms_social_icon($platform) {
    $icons = [
        'facebook'              => 'fab fa-facebook-f',
        'instagram'             => 'fab fa-instagram',
        'x (voorheen twitter)'  => 'fab fa-twitter',
        'linkedin'              => 'fab fa-linkedin',
        'github'                => 'fab fa-github',
        'youtube'               => 'fab fa-youtube',
        'tiktok'                => 'fab fa-tiktok',
        'snapchat'              => 'fab fa-snapchat',
        'pinterest'             => 'fab fa-pinterest',
        'whatsapp'              => 'fab fa-whatsapp',
        'telegram'              => 'fab fa-telegram',
        'bluesky'               => 'fas fa-cloud',
    ];
    return $icons[strtolower($platform)] ?? 'fas fa-link';
}

/*
 * Counter value: a number or a simple calculation with placeholders.
 *   {years:2019}  = number of years since 2019
 *   {projects}    = number of projects on the projects overview
 * Examples: "{years:2019}", "{projects} + 21", "{projects} * 5", "150"
 */
function cms_counter_value($expression, $placeholders) {
    $expression = html_entity_decode(strip_tags((string)$expression), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $expression = preg_replace_callback('/\{years:(\d{4})\}/', function ($m) {
        return (string)((int)date('Y') - (int)$m[1]);
    }, $expression);
    $expression = preg_replace_callback('/\{(\w+)\}/', function ($m) use ($placeholders) {
        return (string)(int)($placeholders[$m[1]] ?? 0);
    }, $expression);

    // Evaluate "number (operator number)*" from left to right, only + - * are allowed
    if (!preg_match_all('/([+\-*])?\s*(\d+)/', $expression, $parts, PREG_SET_ORDER)) {
        return 0;
    }
    $total = 0;
    foreach ($parts as $index => $part) {
        $number = (int)$part[2];
        $operator = $index === 0 ? '+' : ($part[1] ?: '+');
        if ($operator === '*') {
            $total *= $number;
        } elseif ($operator === '-') {
            $total -= $number;
        } else {
            $total += $number;
        }
    }
    return $total;
}
?>
