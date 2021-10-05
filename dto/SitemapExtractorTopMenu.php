<?php


  class SitemapExtractorTopMenu extends SitemapExtractor
  {


    protected function __getItems(): array {
      $items = [
        [
          'name' => 'Корень',
          'url' => '/',
          'children' => [
            [
              'name' => 'О компании',
              'url' => null,
              'children' => [
                [
                  'name' => 'Общая информация',
                  'url' => '/about',
                  'children' => []
                ],
                [
                  'name' => 'Услуги',
                  'url' => '/services',
                  'children' => function () {
                    $services = Pages::getServices(); // kinda repository
                    return array_map(function (Pages $p) {
                      return [
                        'name' => $p->name,
                        'url' => '/' . $p->uri,
                        'children' => []
                      ];
                    }, $services);
                  }
                ],
                [
                  'name' => 'Контакты',
                  'url' => '/contacts',
                  'children' => []
                ]
              ]
            ],
            [
              'name' => 'Сотрудничество',
              'url' => null,
              'children' => [
                [
                  'name' => 'Оптовым клиентам',
                  'url' => '/wholesale_clients',
                  'children' => []
                ]
              ]
            ]
          ]
        ]
      ];

      return $items;
    }


    /**
     * @inheritDoc
     */
    protected function __toElements(array $nodes): array {
      if (!$nodes) {
        return [];
      }


      $stack = [];
      foreach ($nodes as $node) {
        $item = new SitemapElement();
        $item->name = $node['name'];
        $item->loc = $node['url'];
        $item->changefreq = 'weekly';
        $item->priority = $node['url'] === '/' ? 1 : 0.9;
        $item->children = $this->__toElements(
          ($node['children'] instanceof Closure)
            ? call_user_func($node['children'])
            : $node['children']
        );

        $stack[] = $item;
      }

      return $stack;
    }

  }