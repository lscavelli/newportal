<?php

namespace App\Libraries;

class Feeds
{
    private $property = [];

    public function __construct(array $data = [],$value = null)
    {
        $this->set($data,$value);
    }

    public function id($id)
    {
        $this->property['id'] = $id;
        return $this;
    }
    public function link($link)
    {
        $this->property['link'] = $link;
        return $this;
    }
    public function title($title)
    {
        $this->property['title'] = $title;
        return $this;
    }
    public function updated($updated)
    {
        $this->property['updated'] = $updated;
        return $this;
    }
    public function summary($summary)
    {
        $this->property['summary'] = $summary;
        return $this;
    }
    public function author($author)
    {
        $this->property['author'] = $author;
        return $this;
    }
    public function feedTitle($title)
    {
        $this->property['feedTitle'] = $title;
        return $this;
    }
    public function feedSubTitle($subTitle)
    {
        $this->property['feedSubTitle'] = $subTitle;
        return $this;
    }
    public function feedLink($link)
    {
        $this->property['feedLink'] = $link;
        return $this;
    }
    public function feedDate($date)
    {
        $this->property['feedDate'] = $date;
        return $this;
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
        $view = !is_null($view) ?: "ui.feeds.atom";
        return View()->make($view, [
            'list' => $this,
        ])->render();
    }
}
