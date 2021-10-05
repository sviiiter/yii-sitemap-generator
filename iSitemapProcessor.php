<?php

  /**
   * Get sitemap tree
   */
  interface iSitemapProcessor
  {

    /**
     * Get tree
     *
     * @return SitemapElement[] tree of sitemap
     */
    public function collect(): array;

  }