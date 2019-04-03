#!/usr/bin/env php
<?php
require_once __DIR__ . '/vendor/autoload.php';

function getTargetPageUrl($prefId, $pageNo)
{
    return "http://itp.ne.jp/${prefId}/genre_dir/pg/${pageNo}/?sr=1&nad=1&ngr=1&num=50";
}

$prefIds = array(
    'yamanashi', 'yamaguchi', 'yamagata', 'wakayama', 'toyama', 'tottori', 'tokyo', 'tokushima', 'tochigi', 'shizuoka',
    'shimane', 'shiga', 'saitama', 'saga', 'osaka', 'okinawa', 'okayama', 'oita', 'niigata', 'nara',
    'nagasaki', 'nagano', 'miyazaki', 'miyagi', 'mie', 'kyoto', 'kumamoto', 'kouchi', 'kanagawa', 'kagoshima',
    'kagawa', 'iwate', 'ishikawa', 'ibaraki', 'hyogo', 'hokkaido', 'hiroshima', 'gunma', 'gifu', 'fukushima',
    'fukuoka', 'fukui', 'ehime', 'chiba', 'aomori', 'akita', 'aichi',
);

$client = new Goutte\Client();

echo '会社名,メールアドレス,電話番号,住所,都道府県ID', PHP_EOL;

foreach ($prefIds as $prefId) {
    $pageNo = 1;

    $targetPageUrl = getTargetPageUrl($prefId, $pageNo++);

    while (!empty($targetPageUrl)) {
        // ページ内のDOMツリーを取得
        $dom = $client->request(
            'GET',
            $targetPageUrl,
            array(
                'allow_redirects' => true,
                'headers' => array(
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.71 Safari/537.36',
                ),
            )
        );

        // ページ内の企業DOM要素毎にループ
        $results = $dom->filter('div.searchResultsWrapper > div.normalResultsBox > article > section')->each(function ($section) use ($prefId) {
            $companyNameLink = $section->filter('h4 > a.blueText');
            $emailLink = $section->filter('p > a.boxedLink.emailLink');

            if ($companyNameLink->count() <= 0 || $emailLink->count() <= 0) {
                return;
            }

            $tel = preg_match('/[0-9]{2,3}-[0-9]{3,4}-[0-9]{3,4}/u', $section->html(), $telMatches);
            $address = preg_match('/〒[0-9]{3}-[0-9]{4}[^< ]+/u', $section->html(), $addressMatches);

            $columns = array(
                $companyNameLink->text(),
                preg_replace('/openMail\(\'[^\']+\', \'|\'\)/u', '', $emailLink->attr('onclick')),
                $telMatches[0] ?? '',
                $addressMatches[0] ?? '',
                $prefId,
            );

            echo implode($columns, ','), PHP_EOL;
        });

        // ページリンクの「次へ」のリンクを取得
        $targetPageUrl = '';
        $dom->filter('div.bottomNav > ul > li > a')->each(function ($node) use (&$targetPageUrl, $prefId, &$pageNo) {
            if ($node->text() == '次へ' && !empty($node->attr('href'))) {
                $targetPageUrl = getTargetPageUrl($prefId, $pageNo++);
            }
        });

        // DOS攻撃扱いとならないように
        sleep(2);
    }
}
