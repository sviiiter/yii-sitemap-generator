<?php

  /**
   * adapter loads data from DB and convert the rows into sitemap elements
   */
  abstract class SitemapExtractor
  {

    /**
     * Querying data from DB
     *
     * @return array
     */
    abstract protected function __getItems(): array;


    /**
     * Convert nodes into tree of sitemap items
     *
     * @param array $nodes
     *
     * @return SitemapElement[] tree
     */
    abstract protected function __toElements(array $nodes): array;


    /**
     * Get tree of SitemapElements
     *
     * @return SitemapElement[] tree
     */
    public function extract() {
      $nodes = $this->__getItems();

      return $this->__toElements($nodes);
    }

  }