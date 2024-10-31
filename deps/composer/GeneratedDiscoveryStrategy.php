<?php

namespace ScobyAnalyticsDeps\Http\Discovery\Strategy;

class GeneratedDiscoveryStrategy implements DiscoveryStrategy
{
    public static function getCandidates($type)
    {
        switch ($type) {
            case 'Psr\\Http\\Message\\RequestFactoryInterface':
                return [['class' => 'ScobyAnalyticsDeps\\RebelCode\\Psr7\\HttpFactory']];
            case 'Psr\\Http\\Message\\ResponseFactoryInterface':
                return [['class' => 'ScobyAnalyticsDeps\\RebelCode\\Psr7\\HttpFactory']];
            case 'Psr\\Http\\Message\\ServerRequestFactoryInterface':
                return [['class' => 'ScobyAnalyticsDeps\\RebelCode\\Psr7\\HttpFactory']];
            case 'Psr\\Http\\Message\\StreamFactoryInterface':
                return [['class' => 'ScobyAnalyticsDeps\\RebelCode\\Psr7\\HttpFactory']];
            case 'Psr\\Http\\Message\\UploadedFileFactoryInterface':
                return [['class' => 'ScobyAnalyticsDeps\\RebelCode\\Psr7\\HttpFactory']];
            case 'Psr\\Http\\Message\\UriFactoryInterface':
                return [['class' => 'ScobyAnalyticsDeps\\RebelCode\\Psr7\\HttpFactory']];
            default:
                return [];
        }
    }
}
