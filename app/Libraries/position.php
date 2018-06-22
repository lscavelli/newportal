<?php

namespace App\Libraries;

use App\Repositories\RepositoryInterface;
use Illuminate\Support\Facades\Log;

class position
{
    protected $rp;
    private $orderfield;

    public function __construct(RepositoryInterface $rp, $orderfield='position') {
        $this->rp = $rp;
        $this->orderfield = $orderfield;
    }

    /**
     * ordina un elenco di dati
     * @param null $id
     * @param null $pos
     * @param null $newpos
     * @param array $filter
     */
    public function reorder($id=null,$pos=null,$newpos=null,$filter=[]) {
        if($pos!=$newpos) {
            if ($newpos > $pos) {
                $this->rp->filter($filter)->where($this->orderfield, '<=', $newpos)->where('id', '<>', $id)->decrement($this->orderfield);
            } else {
                $this->rp->filter($filter)->where($this->orderfield, '>=', $newpos)->where('id', '<>', $id)->increment($this->orderfield);
            }
            $this->rp->resetModel()->update($id, [$this->orderfield=>$newpos]);
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

    /**
     * determina la pos di destinazione in base all'id dell'item precedente e riordina
     * @param array $filter
     * @param string $itemID
     * @param string $afterItemID
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function reorderDrag($filter=[],$itemID='itemID',$afterItemID='afterItemID') {
        if (request()->has($itemID)) {
            $taskPos = $this->rp->find(request()->$itemID)->position;
            $taskNewPos = request()->has($afterItemID) && !empty(request()->$afterItemID) ? $this->rp->find(request()->$afterItemID)->position : 0;
            if ($taskNewPos<$taskPos) $taskNewPos++; //salgo
            if ($taskNewPos>$this->rp->count()) $taskNewPos--;
            $this->reorder(request()->$itemID,$taskPos,$taskNewPos,$filter);
            return response()->json(['success' => true], 200);
        }
        return false;
    }

}