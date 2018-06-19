<?php

namespace App\Libraries;

use App\Repositories\RepositoryInterface;

class position
{
    protected $rp;
    private $orderfield;

    public function __construct(RepositoryInterface $rp) {
        $this->rp = $rp;
        $this->orderfield = 'position';
    }

    public function reorder($id=null,$pos=null,$newpos=null,$filter=[]) {
        if($pos!=$newpos) {
            if($newpos>$pos) {
                $this->rp->filter($filter)->where($this->orderfield,'<=',$newpos)->where('id','<>',$id)->decrement($this->orderfield);
            } else {
                $this->rp->filter($filter)->where($this->orderfield,'>=',$newpos)->where('id','<>',$id)->increment($this->orderfield);
            }
        }
        if($pos!=$newpos || ($pos==null && $newpos == null && $id==null) ) {
            $list = $this->rp->resetModel()->filter($filter)->orderBy($this->orderfield)->get(null,['id']);
            $this->rp->resetModel();
            $p = 0;
            foreach ($list as $item) {
                $p++;
                $this->rp->update($item->id,[$this->orderfield=>$p]);
            }
        }
    }
}