    <?php
    $menuItems = [
        [
            'title' => 'Dashboards',
            'icon' => '<svg class="svg-icon" width="25" height="25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                  <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                  <polyline points="9 22 9 12 15 12 15 22"></polyline>
                              </svg>',
            'url' => BASE_URL_HTML . '/system/',
            'active' => $_SERVER['REQUEST_URI'] == BASE_URL_HTML . '/system/',
            'submenu' => []
        ],
        [
            'title' => 'Data',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>',
            'url' => '#',
            'active' => false,
            'submenu' => [
                [
                    'title' => 'User',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>',
                    'url' => BASE_URL_HTML . '/system/data/user/',
                    'active' => $_SERVER['REQUEST_URI'] == BASE_URL_HTML . '/system/data/user/'
                ],
                [
                    'title' => 'Bank',
                    'icon' => '<svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                               <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M3 21h18M4 18h16M6 10v8m4-8v8m4-8v8m4-8v8M4 9.5v-.955a1 1 0 0 1 .458-.84l7-4.52a1 1 0 0 1 1.084 0l7 4.52a1 1 0 0 1 .458.84V9.5a.5.5 0 0 1-.5.5h-15a.5.5 0 0 1-.5-.5Z"/>
                                </svg>
',
                    'url' => BASE_URL_HTML . '/system/data/bank/',
                    'active' => $_SERVER['REQUEST_URI'] == BASE_URL_HTML . '/system/data/bank/'
                ],
                [
                    'title' => 'Transfer',
                    'icon' => '<svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16 10 3-3m0 0-3-3m3 3H5v3m3 4-3 3m0 0 3 3m-3-3h14v-3" />
                                                    </svg>
',
                    'url' => BASE_URL_HTML . '/system/data/transfer/',
                    'active' => $_SERVER['REQUEST_URI'] == BASE_URL_HTML . '/system/data/transfer/'
                ],
                [
                    'title' => 'Cash Flow',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>',
                    'url' => BASE_URL_HTML . '/system/data/cashflow/',
                    'active' => $_SERVER['REQUEST_URI'] == BASE_URL_HTML . '/system/data/cashflow/'
                ],
            ]
        ],
        [
            'title' => 'Analytic',
            'icon' => '<svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15v4m6-6v6m6-4v4m6-6v6M3 11l6-5 6 5 5.5-5.5"/>
</svg>
',
            'url' => '#',
            'active' => false,
            'submenu' => [
                [
                    'title' => 'Cashflow',
                    'icon' => '<svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.6 16.733c.234.269.548.456.895.534a1.4 1.4 0 0 0 1.75-.762c.172-.615-.446-1.287-1.242-1.481-.796-.194-1.41-.861-1.241-1.481a1.4 1.4 0 0 1 1.75-.762c.343.077.654.26.888.524m-1.358 4.017v.617m0-5.939v.725M4 15v4m3-6v6M6 8.5 10.5 5 14 7.5 18 4m0 0h-3.5M18 4v3m2 8a5 5 0 1 1-10 0 5 5 0 0 1 10 0Z"/>
</svg>

',
                    'url' => BASE_URL_HTML . '/system/analytic/cashflow/',
                    'active' => $_SERVER['REQUEST_URI'] == BASE_URL_HTML . '/system/analytic/cashflow/'
                ]
            ]
        ],
        [
            'title' => 'Report',
            'icon' => '<svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 3v4a1 1 0 0 1-1 1H5m4 8h6m-6-4h6m4-8v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Z"/>
</svg>
',
            'url' => '#',
            'active' => false,
            'submenu' => [
                [
                    'title' => 'Bank Report',
                    'icon' => '<svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
  <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M8 7V6a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1h-1M3 18v-7a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
</svg>
',
                    'url' => BASE_URL_HTML . '/system/report/bank/',
                    'active' => $_SERVER['REQUEST_URI'] == BASE_URL_HTML . '/system/report/bank/'
                ]
            ]
        ],
    ];
    ?>

    <div class="iq-sidebar sidebar-default">
        <div class="iq-sidebar-logo d-flex align-items-center">
            <a href="<?= BASE_URL_HTML ?>/backend/index.html" class="header-logo">
                <img src="<?= BASE_URL_HTML ?>/assets/images/logo.svg" alt="logo">
                <h3 class="logo-title light-logo">CASHFLOW</h3>
            </a>
            <div class="iq-menu-bt-sidebar ml-0">
                <i class="las la-bars wrapper-menu"></i>
            </div>
        </div>
        <div class="data-scrollbar" data-scroll="1">
            <nav class="iq-sidebar-menu">
                <ul id="iq-sidebar-toggle" class="iq-menu">
                    <?php foreach ($menuItems as $item): ?>
                        <li class="<?= $item['active'] ? 'active' : '' ?>">
                            <a href="<?= !empty($item['submenu']) ? '#' . strtolower(str_replace(' ', '-', $item['title'])) : $item['url'] ?>"
                                class="svg-icon <?= !empty($item['submenu']) ? 'collapsed' : 'no-submenu' ?>"
                                <?= !empty($item['submenu']) ? 'data-toggle="collapse" aria-expanded="false"' : '' ?>>
                                <?= $item['icon'] ?>
                                <span class="ml-4"><?= $item['title'] ?></span>
                                <?php if (!empty($item['submenu'])): ?>
                                    <i class="las la-angle-right iq-arrow-right arrow-active"></i>
                                    <i class="las la-angle-down iq-arrow-right arrow-hover"></i>
                                <?php endif; ?>
                            </a>
                            <?php if (!empty($item['submenu'])): ?>
                                <ul id="<?= strtolower(str_replace(' ', '-', $item['title'])) ?>" class="iq-submenu collapse" data-parent="#iq-sidebar-toggle">
                                    <?php foreach ($item['submenu'] as $submenu): ?>
                                        <li class="<?= $submenu['active'] ? 'active' : '' ?>">
                                            <a href="<?= $submenu['url'] ?>" class="svg-icon">
                                                <?= $submenu['icon'] ?>
                                                <span class="ml-4"><?= $submenu['title'] ?></span>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>
    </div>