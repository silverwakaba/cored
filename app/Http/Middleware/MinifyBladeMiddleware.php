<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MinifyBladeMiddleware{
    public function handle($request, Closure $next){
        $response = $next($request);

        if($this->isHtmlResponse($response)){
            $response->setContent(
                $this->minifyHtml($response->getContent())
            );
        }
        
        return $response;
    }
    
    protected function isHtmlResponse(Response $response) : bool{
        $contentType = $response->headers->get('Content-Type');
        
        return strpos($contentType, 'text/html') !== false;
    }
    
    protected function minifyHtml(string $html) : string{
        $replace = [
            '/<!--[^\[](.*?)[^\]]-->/s' => '', // Remove HTML comments except IE conditions
            "/\s+/"                     => " ", // Collapse whitespace
            "/\>\s+\</"                 => "><", // Remove whitespace between tags
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
