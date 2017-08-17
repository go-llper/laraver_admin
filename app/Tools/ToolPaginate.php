<?php
/**
 * Created by PhpStorm.
 * User: gyl-dev
 * Date: 16/6/3
 * Time: 下午8:51
 * Desc: 核心返回数据处理分页信息
 */

namespace App\Tools;

class ToolPaginate
{

    protected $total = 1;
    protected $size = 1;
    protected $page = 1;
    protected $url  = '';
    protected $pageParam  = '';

    public function __construct($total, $page, $size, $url, $pageParam='page=')
    {

        $this->total = $total;
        $this->size = max($size, 1);
        $this->page = $page;
        $this->url  = $url;
        $this->pageParam = $pageParam;

    }

    /**
     * @return array
     * @desc 获取数组信息，输出在模板
     */
    public function getPaginate()
    {

        return [
              "total"           => $this->getTotal(),
              "per_page"        => $this->getPerpage(),
              "current_page"    => $this->getCurrentPage(),
              "last_page"       => $this->getLastPage(),
              "next_page_url"   => $this->getNextUrl(),
              "prev_page_url"   => $this->getPreUrl(),
              "page_url"        => $this->getPageUrl()
        ];

    }

    /**
     * @return int
     * @desc 每页个数
     */
    public function getPerpage()
    {

        return $this->size;

    }

    /**
     * @return int
     * @desc 总数
     */
    public function getTotal()
    {

        return isset($this->total) ? $this->total : 0;

    }

    /**
     * @return int
     * @desc 当前页面
     */
    public function getCurrentPage()
    {

        return $this->page;

    }

    /**
     * @return float
     * @desc 最后一页
     */
    public function getLastPage()
    {

        return floor(ceil($this->getTotal()/$this->getPerpage()));

    }

    /**
     * @return mixed
     * @desc 下一页
     */
    public function getNextPage()
    {

        return min($this->getCurrentPage()+1, $this->getLastPage());

    }

    /**
     * @return mixed
     * @desc 获取每页个数
     */
    public function getPrePage()
    {

        return max($this->getCurrentPage()-1, 1);

    }

    /**
     * @return string
     * @desc 下一页url
     */
    public function getNextUrl()
    {

        return $this->renderUrl($this->url, $this->pageParam.$this->getNextPage());

    }

    /**
     * @return string
     * @desc 上一页url
     */
    public function getPreUrl()
    {

        return $this->renderUrl($this->url, $this->pageParam.$this->getPrePage());

    }

    /**
     * @return string
     * @desc 获取pageurl
     */
    public function getPageUrl()
    {

        return $this->renderUrl($this->url, $this->pageParam);

    }


    /**
     * @param $url
     * @param string $paramStr
     * @return string
     * @desc 组装url
     */
    public function renderUrl($url, $paramStr = '') {

        if(stripos($url, '?') === false) {

            $connect = '?';

        } else {

            $connect = '&';

        }

        return $url . $connect . $paramStr;
    }


}