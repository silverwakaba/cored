<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// To be honest this entire implementation is based on AI
// Since idk shit about regex
class MinifyBladeMiddleware{
    // Handle request
    public function handle($request, Closure $next){
        $response = $next($request);

        if($this->isHtmlResponse($response)){
            $response->setContent(
                $this->minifyHtml($response->getContent())
            );
        }
        
        return $response;
    }
    
    // Check if the response is HTML
    protected function isHtmlResponse(Response $response) : bool{
        $contentType = $response->headers->get('Content-Type');
        
        return strpos($contentType, 'text/html') !== false;
    }
    
    // Minify HTML
    protected function minifyHtml(string $html) : string{
        // First process inline script tags (without src attribute) separately
        $html = preg_replace_callback(
            '/<script(?!.*\bsrc\s*=)[^>]*>([\s\S]*?)<\/script>/i',
            function($matches){
                $scriptContent = $matches[1];
    
                // First protect URLs by temporarily replacing them with a placeholder
                $urlPlaceholders = [];
                $scriptContent = preg_replace_callback(
                    '/(https?:\/\/[^\s"\']+)/',
                    function($urlMatches) use(&$urlPlaceholders){
                        $key = '___URL_' . count($urlPlaceholders) . '___';
                        $urlPlaceholders[$key] = $urlMatches[0];
                        
                        return $key;
                    },

                    $scriptContent
                );
    
                // Remove JS comments (both single and multi-line)
                $scriptContent = preg_replace([
                    '/\/\*[\s\S]*?\*\//',   // Multi-line comments
                    '/\/\/.*$/m'            // Single-line comments
                ], '', $scriptContent);
    
                // Collapse whitespace in script content
                $scriptContent = preg_replace('/\s+/', ' ', $scriptContent);
                
                // Restore the protected URLs
                foreach($urlPlaceholders as $key => $url){
                    $scriptContent = str_replace($key, $url, $scriptContent);
                }
                
                return '<script>' . trim($scriptContent) . '</script>';
            },

            $html
        );
    
        $replace = [
            '/<!--[^\[](.*?)[^\]]-->/s' => '',      // Remove HTML comments except IE conditions
            "/\s+/"                     => " ",     // Collapse whitespace
            "/\>\s+\</"                 => "><",    // Remove whitespace between tags
        ];
        
        $html = preg_replace(
            array_keys($replace), array_values($replace), $html
        );
        
        // Optional: Remove whitespace around block elements
        $html = preg_replace(
            '/\s+(<\/?(?:header|footer|nav|section|article|div|h[1-6]|p|ul|ol|li|blockquote)[^>]*>)/', '$1', $html
        );
        
        return trim($html);
    }
}