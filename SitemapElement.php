<?php

  /**
   * Type of a sitemap item
   */
  class SitemapElement
  {

    public $name;

    public $loc;

    public $lastmod;

    public $changefreq;

    public $priority = 0.5;

    /* @var SitemapElement[] */
    public $children = [];

  }