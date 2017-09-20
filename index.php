<?php
require './vendor/autoload.php';
use phpspider\core\phpspider;
use phpspider\core\requests;
use phpspider\core\selector;

/* Do NOT delete this comment */
/* 不要删除这段注释 */
$url = "https://www.acwind.net/icdb/18185/";
requests::$input_encoding = 'GB2312';
requests::$output_encoding = 'GB2312';
//$html = requests::get($url);

$selector = "@middle' >\s+-?(.*?)&nbsp;@";
// 提取结果 <a href='/icdb/people/[^\s]+'>(.*?)</a>

$configs = array(
    'name' => '动漫',
    'tasknum' => 8,
    'db_config' => array(
        'host'  => '127.0.0.1',
        'port'  => 3306,
        'user'  => 'root',
        'pass'  => '123456',
        'name'  => 'anime_db',
    ),
    'log_show' => true,
    'export' => array(
        'type' => 'db',
        'table' => 'anime_table',
    ),
    'domains' => array(
        'acwind.net',
        'www.acwind.net'
    ),
    'scan_urls' => array(
        'https://www.acwind.net/'
    ),
    'content_url_regexes' => array(
        "https://www.acwind.net/icdb/\d+"
    ),
    'list_url_regexes' => array(
        "https://www.acwind.net/icdb/year/\d+"
    ),
    'fields' => array(
        array(
            // 名字
            'name' => "name",
            'selector_type' => 'regex',
            'selector' => "@<b>《(.*?)》</b>@",
            'required' => true
        ),
        array(
            // 作者
            'name' => "author",
            'selector_type' => 'regex',
            'selector' => "@<a href='/icdb/people/[^\s]+'>(.*?)</a>@",
            'required' => true
        ),
        array(
            // 制作公司
            'name' => "company",
            'selector_type' => 'regex',
            'selector' => "@<a href=\"/icdb/studio/[^\s]+\">(.*?)</a>@",
        ),
        array(
            // 出品年份
            'name' => "year",
            'selector_type' => 'regex',
            'selector' => "@<a href='/icdb/year/[^\s]+'>(.*?)</a>@",
        ),
        array(
            // 总集数
            'name' => "count",
            'selector_type' => 'regex',
            'selector' => "@middle' >\s+-?(.*?)&nbsp;@",
        ),
    ),
);
$spider->on_start = function ($spider)
{
    // 生成列表页URL入队列
    for ($i = 1959; $i <= 2017; $i++)
    {
        $url = "https://www.acwind.net/icdb/year/{$i}";
        $spider->add_url($url);
    }
};
$spider = new phpspider($configs);
$spider->start();