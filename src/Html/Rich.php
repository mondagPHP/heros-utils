<?php
declare(strict_types=1);
/**
 * This file is part of heros-utils.
 *
 * @contact  mondagroup_php@163.com
 *
 */
namespace Monda\Utils\Html;

use Monda\Utils\Exception\HeroException;

class Rich
{
    /**
     * 处理富文本图片，图片url路径转对象存储路径-->渲染
     * @param string $text
     * @param string $prefixPath
     * @return string
     */
    public static function richTextImgUrl2ResUrl(string $text, string $prefixPath): string
    {
        if (empty($text)) {
            return '';
        }
        if (! extension_loaded('libxml') || ! extension_loaded('dom')) {
            throw new HeroException('please install libxml or dom extension.');
        }
        try {
            libxml_use_internal_errors(true);
            $content = html_entity_decode($text, ENT_HTML5);
            $dom = new \DOMDocument();
            $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
            $xpath = new \DOMXPath($dom);
            $nodeList = $xpath->query('//img');
            foreach ($nodeList as $node) {
                $url = $node->attributes->getNamedItem('src')->nodeValue;
                $node->setAttribute('src', $prefixPath . $url);
            }
            return self::dealRichText($dom->saveHTML());
        } catch (\Throwable $exception) {
        }
        return $text;
    }

    /**
     * 处理富文本图片，图片对象存储路径转url路径(保存、更新)
     * @param string $text
     * @param string $bucket
     * @return string
     */
    public static function richTextResUrl2ImgUrl(string $text, string $bucket): string
    {
        if (empty($text)) {
            return '';
        }
        if (! extension_loaded('libxml') || ! extension_loaded('dom')) {
            throw new HeroException('please install libxml or dom extension.');
        }
        try {
            libxml_use_internal_errors(true);
            $content = html_entity_decode($text, ENT_HTML5);
            $dom = new \DOMDocument();
            $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
            $xpath = new \DOMXPath($dom);
            $nodeList = $xpath->query('//img');
            foreach ($nodeList as $node) {
                $resUrl = $node->attributes->getNamedItem('src')->nodeValue;
                preg_match("/\/{$bucket}\/\d{4}\/\d{2}\/\d{2}\/[a-z0-9.]*\.(jpg|bmp|gif|ico|pcx|jpeg|tif|png|raw|tga)/", $resUrl, $matches);
                if (isset($matches[0])) {
                    $node->setAttribute('src', substr($matches[0], strlen("/{$bucket}/")));
                }
            }
            return self::dealRichText($dom->saveHTML());
        } catch (\Throwable $exception) {
        }
        return $text;
    }

    /**
     * 处理富文本数据
     * @param string $html
     * @return string
     */
    private static function dealRichText(string $html): string
    {
        //清除<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/REC-html40/loose.dtd">
        $text = trim(preg_replace('/^<!DOCTYPE html.*>/', '', $html));
        //清除<html><body></body></html>
        return html_entity_decode(substr($text, 12, -14)) ?: '';
    }
}
