#!/usr/bin/env php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

$client = new Client();

$makeNextLink = function ( $prefId, $pageNumber ) {
    // return "http://itp.ne.jp/genre_dir/buildingfirm/pg/${pageNumber}/?ngr=1&num=50";
    // return "http://itp.ne.jp//${prefId}/genre_dir/${pageNumber}/?sr=1&nad=1&ngr=1&num=50";
    return "http://itp.ne.jp/${prefId}/19201/genre_dir/pg/${pageNumber}/?sr=1&nad=1&ngr=1&num=50";
};


$prefIds = array(
    'yamanashi', 'yamaguchi', 'yamagata', 'wakayama', 'toyama', 'tottori', 'tokyo', 'tokushima', 'tochigi', 'shizuoka',
    'shimane', 'shiga', 'saitama', 'saga', 'osaka', 'okinawa', 'okayama', 'oita', 'niigata', 'nara',
    'nagasaki', 'nagano', 'miyazaki', 'miyagi', 'mie', 'kyoto', 'kumamoto', 'kouchi', 'kanagawa', 'kagoshima',
    'kagawa', 'iwate', 'ishikawa', 'ibaraki', 'hyogo', 'hokkaido', 'hiroshima', 'gunma', 'gifu', 'fukushima',
    'fukuoka', 'fukui', 'ehime', 'chiba', 'aomori', 'akita', 'aichi',
);

echo "会社名,メールアドレス", PHP_EOL;

foreach ($prefIds as $prefId) {
    $pageNumber = 1;
    $nextLink = $makeNextLink($prefId, $pageNumber++);

    while (!empty($nextLink)) {
        $crawler = $client->request('GET', $nextLink);
        $results = $crawler->filter('div.searchResultsWrapper > div.normalResultsBox > article > section')->each(function ($section) {
            // echo '<pre>'; var_dump($p->attr('onclick')); echo '</pre>';
            $companyNameLink = $section->filter('h4 > a.blueText');
            $emailLink = $section->filter('p > a.boxedLink.emailLink');

            if ($companyNameLink->count() <= 0 || $emailLink->count() <= 0) return;

            $columns = array(
                $companyNameLink->text(),
                preg_replace('/openMail\(\'[^\']+\', \'|\'\)/u', '', $emailLink->attr('onclick'))
            );

            echo implode($columns, ","), PHP_EOL;
        });

        $nextLink = '';
        $crawler->filter('div.bottomNav > ul > li > a')->each(function ($node) use (&$nextLink, $makeNextLink, $prefId, &$pageNumber) {
            if ($node->text() == 'Next' && !empty($node->attr('href'))) {
                $nextLink = $makeNextLink($prefId, $pageNumber++);
            }
        });
    }
}

// function uploadProductsCsv($file)
// {
    // // csv upload時の更新対象項目設定を初期化する
    // $crawler = $this->client->request(
        // 'GET',
        // "{$this->server}/admin/contents/csv.php?tpl_subno_csv=product"
    // );
    // $form = $crawler->filter('form')->form();
    // $form['mode'] = 'defaultset';
    // $crawler = $this->client->submit($form);

    // // csv upload開始
    // $crawler = $this->client->request(
        // 'GET',
        // "{$this->server}/admin/products/upload_csv.php"
    // );
    // $form = $crawler->filter('form#form1')->form();
    // $form['csv_file']->upload($file);
    // $crawler = $this->client->submit($form);

    // $nowText = date('Ymd_Hi');
    // file_put_contents ("./uploadProductsCsv_${nowText}.html" , $crawler->html());
// }

// function uploadProductsCsvForCategory($file)
// {
    // // csv upload時の更新対象項目設定を初期化する
    // $crawler = $this->client->request(
        // 'GET',
        // "{$this->server}/admin/contents/csv.php?tpl_subno_csv=product"
    // );
    // $form = $crawler->filter('form')->form();

    // // 上から順番に
    // // * 商品ID
    // // * 商品規格ID
    // // * 商品名
    // // * 一覧-メイン画像
    // // * 詳細-メイン画像
    // // * 詳細-メイン拡大画像
    // // * 商品コード
    // // * 通常価格
    // // * 販売価格
    // // * カテゴリID
    // $form->setValues(array('output_list' => array(
        // 1 => '1',
        // 2 => '2',
        // 3 => '11',
        // 4 => '21',
        // 5 => '23',
        // 6 => '24',
        // 7 => '48',
        // 8 => '52',
        // 9 => '53',
        // 10 => '72',
    // )));
    // $form['mode'] = 'confirm';
    // $crawler = $this->client->submit($form);

    // // csv upload開始
    // $crawler = $this->client->request(
        // 'GET',
        // "{$this->server}/admin/products/upload_csv.php"
    // );
    // $form = $crawler->filter('form#form1')->form();
    // $form['csv_file']->upload($file);
    // $crawler = $this->client->submit($form);

    // $nowText = date('Ymd_Hi');
    // file_put_contents ("./uploadProductsCsv_${nowText}.html" , $crawler->html());
// }

// function getClient()
// {
    // return $this->client;
// }

// function updateOrderStatus($orderId, $status)
// {
    // $crawler = $this->client->request(
        // 'GET',
        // "{$this->server}/admin/order/edit.php?mode=pre_edit&order_id=".$orderId
    // );

    // $form = $crawler->filter('form#form1')->form();

    // $form['status'] = $status;
    // $crawler = $this->client->submit($form);

    // $nowText = date('Ymd_Hi');
    // file_put_contents ("./updateOrderStatus_${nowText}.html" , $crawler->html());
// }
