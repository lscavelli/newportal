<?php

namespace App\Libraries;

use App\Libraries\sl_text;

class Feeds
{
    private $property = [];

    public function __construct(array $data = [],$value = null)
    {
        $this->set($data,$value);
    }
    public function title($title)
    {
        $this->property['title'] = $title;
        return $this;
    }
    public function subTitle($subTitle)
    {
        $this->property['subTitle'] = $subTitle;
        return $this;
    }
    public function link($link)
    {
        $this->property['link'] = $link;
        return $this;
    }
    public function linkFeed($linkFeed)
    {
        $this->property['linkFeed'] = $linkFeed;
        return $this;
    }
    public function date($date)
    {
        $this->property['date'] = date('c', strtotime($date));
        return $this;
    }

    public function addItem($id,$link,$title,$updated,$summary,$content,$author) {
        $obj = new \stdClass();
        $obj->id = $id;
        $obj->link = $link;
        $obj->title = $title;
        $obj->updated = date('c', strtotime($updated));
        $obj->summary = $summary;
        $obj->content = sl_text::sommario($content);
        $obj->author = $author;
        $this->add($obj);
    }

    private function add($item) {
        $this->property['items'][] = $item;
    }

    public function get($key)
    {
        return array_get($this->property,$key);
    }

    public function set($data,$value=null)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $this->property[$key] = $value;
            }
        } elseif(is_string($data) and !is_null($value)) {
            $this->property[$data] = $value;
        }
        return $this;
    }

    public function render($view = null) {
        $view = !is_null($view) ? "ui.feeds.".$view : "ui.feeds.atom";
        return View()->make($view, [
            'feed' => $this,
        ])->render();
    }
}
