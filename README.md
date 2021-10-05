# Sitemap generator

Simple library contains interfaces to extract the data from storage, converts into tree and expand this into linear structure.
In respect that structure of site is tree of pages. 

Usage:

### - Create pipeline of ordered dto
Projects, products, news e.t.c. It is possible to create extractor tree as subtree of another extractor.
```
<?php


  class SitemapProcessor implements iSitemapProcessor
  {

    /**
     * @inheritDoc
     */
    public function collect(): array {

      $pipe = [ // examples
        'menu' => SitemapExtractorTopMenu::class,
        'catalog' => SitemapExtractorCatalogPages::class
      ];
    
      return array_map(function (SitemapExtractor $i) { return (new $i)->extract(); }, $pipe);     
    }

  }
```

### - create concrete generator with converting location method:

```

  class ConcreteXmlSitemapGenerator extends XmlSitemapGenerator
  {

    /**
     * @inheritDoc
     *
     * @param $url
     * @return string
     */
    protected static function convertLocation($url):string {
      return rtrim(Site::current()->url, '/') . '/' . ltrim($url, '/');
    }

  }
  
```

### - in your clients code (kinda console command):


```
...
  $piecesToExportToFile = (new SitemapProcessor())->collect();


  $rootXml = new SimpleXMLElement('<sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd"></sitemapindex>');

  $generator = new ConcreteXmlSitemapGenerator();
  foreach ($piecesToExportToFile as $baseFilename => $tree) {

    $linear = $generator->extractTree($tree);
    $xml = $generator->getXmlString($linear);

    $baseFilenameWExt = 'sitemap_' . $baseFilename . '.xml';
    $filename = $exportDir . '/' . $baseFilenameWExt;
    file_put_contents($filename, $xml);

    $rootSitemapTag = $rootXml->addChild('sitemap');
    $rootSitemapTag->addChild('loc', rtrim(Site::current()->url, '/') . '/' . $baseFilenameWExt);
  }

  $rootXml->asXML($exportDir . '/sitemap.xml');
...  
```
... or to create a single file:

```
$tree = (new SitemapProcessor())->collect();

$generator = new ConcreteXmlSitemapGenerator();
$linear = $generator->extractTree($tree);
$xml = $generator->getXmlString($linear);
```



