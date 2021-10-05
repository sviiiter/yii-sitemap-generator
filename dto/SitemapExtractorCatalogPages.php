<?php



  /**
   * Querying categories and subcategories. Convert it.
   */
  class SitemapExtractorCatalogPages extends SitemapExtractor
  {

    /**
     * Catalog root
     * @var integer
     */
    private $rootPageId;


    public function __construct($rootPageId) {
      $this->rootPageId = $rootPageId;
    }


    /**
     * @inheritDoc
     *
     * @return array
     */
    protected function __getItems(): array {
      $pages = new Pages();
      $pages::$defaultParentId = $this->rootPageId;

      return $pages->getPages();
    }


    /**
     * Recursive convert pages to Sitemap elements
     *
     * @param array $nodes
     * @return SitemapElement[]
     */
    protected function __toElements(array $nodes): array {
      if (!$nodes) {
        return [];
      }


      $stack = [];
      foreach ($nodes as $node) {
        $item = new SitemapElement();
        $item->name = $node['name'];
        $item->loc = Y::url('catalog/index', ['item' => $node['page_id']]);
        $item->changefreq = 'weekly';
        $item->children = $this->__toElements((array)$node['children']);
        $item->priority = 0.6;
        $stack[] = $item;
      }

      return $stack;
    }

  }