<?php

  /**
   * Process and output XML string
   */
  abstract class XmlSitemapGenerator
  {

    /**
     * Get xml-string from linear array of SitemapElements
     *
     * @param SitemapElement[] $items
     * @return string result xml string
     */
    public static function getXmlString(array $items): string {
      $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');

      foreach ($items as $item) {
        /* @var SitemapElement $item */
        $urlNode = $xml->addChild('url');
        $urlNode->addChild('loc', static::convertLocation($item->loc));
        $urlNode->addChild('changefreq', $item->changefreq);
        $urlNode->addChild('priority', $item->priority);
      }

      return $xml->asXML();
    }


    /**
     * Convert tree of arrays into linear format
     *
     * @param SitemapElement[] $tree the tree of SitemapElement instances
     * @return SitemapElement[]
     */
    public static function extractTree($tree) {
      $data = [];
      self::__recursiveExtractTree($tree, $data);

      return $data;
    }


    /**
     * Recursive extract the data and push into linear container
     *
     * @param SitemapElement[] $tree the tree of SitemapElement instances
     */
    private static function __recursiveExtractTree(array $tree, &$data) {
      foreach ($tree as $item) {
        $data[] = $item;
        self::__recursiveExtractTree($item->children, $data);
      }
    }


    /**
     * Custom method to get url
     *
     * @param $url
     * @return string
     */
    abstract protected static function convertLocation($url): string;

  }