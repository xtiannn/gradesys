<?php
if (!function_exists('buildUrl')) {
    function buildUrl($baseUrl, $params) {
        $url = $baseUrl . '?';
        $queryParams = [];
        foreach ($params as $key => $value) {
            if (!empty($value)) {
                $queryParams[] = $key . '=' . urlencode(trim($value));
            }
        }
        $url .= implode('&', $queryParams);
        return $url;
    }
}
?>
