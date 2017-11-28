<?php

namespace App\Libraries;

use App\Libraries\sl_text;

class Feeds
{
    private $property = [];
    private $type;

    public function __construct($type = 'atom',array $data = [],$value = null)
    {
        $this->type = $type;
        $this->set($data,$value);
    }
    public function title($title)
    {
        if ($title)
            $this->property['title'] = htmlspecialchars(strip_tags($title), ENT_COMPAT, 'UTF-8');
        return $this;
    }
    public function subTitle($subTitle)
    {
        if ($subTitle)
            $this->property['subTitle'] = htmlspecialchars(strip_tags($subTitle), ENT_COMPAT, 'UTF-8');
        return $this;
    }
    public function description($description)
    {
        if ($description)
            $this->property['description'] = htmlspecialchars(strip_tags($description), ENT_COMPAT, 'UTF-8');
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
    public function language($language)
    {
        $this->property['language'] = $language;
        return $this;
    }

    public function addItem($id,$link,$title,$updated,$summary,$content,$author,$enclosure=[]) {

        $obj = new \stdClass();
        $obj->id = $id;
        $obj->link = $link;
        $obj->title = htmlspecialchars(strip_tags($title), ENT_COMPAT, 'UTF-8');
        if ($this->type=='atom') {
            $obj->updated = date('c', strtotime($updated));
        } elseif($this->type=='rss2') {
            $obj->updated = date('D, d M Y H:i:s O', strtotime($updated));
        }

        $obj->summary = $summary; // solo atom 1.0
        $obj->content = sl_text::sommario($content);
        $obj->author = $author;
        if ($enclosure) {
            $obj->enclosure = $enclosure;
        }
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
