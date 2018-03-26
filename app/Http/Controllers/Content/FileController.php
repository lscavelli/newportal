<?php

namespace App\Http\Controllers\Content;

use App\Libraries\listGenerates;
use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;
use App\Repositories\RepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Filesystem\Filesystem;

class FileController extends Controller {

    private $rp;

    public function __construct(RepositoryInterface $rp)  {
        $this->middleware('auth');
        $this->rp = $rp->setModel('App\Models\Content\File')->setSearchFields(['name','description','file_name']);
    }

    /**
     * @param array $data
     * @return mixed
     */
    private function validator(array $data)   {
            return Validator::make($data, [
            'name' => 'sometimes|required|min:3|max:255'
        ]);
    }

    /**
     * Visualizza la lista dei file, eventualmente filtrata
     * @param Request $request
     * @param listGenerates $list
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request, listGenerates $list) {
        $files = $this->rp->paginate($request);
        $list->setModel($files);
        return view('content.listFile')->with(compact('$files','list'));
    }

    /**
     * Mostra il form per la modifica del file
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $file = $this->rp->find($id);
        return view('content.editFile', compact('file'));
    }

    /**
     * Aggiorna il file
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $data = $request->all(); $data['id'] = $id;
        $this->validator($data)->validate();
        if ($this->rp->update($id,$data)) {
            return redirect('/admin/files')->withSuccess('File modificato correttamente.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $file = $this->rp->find($id);
        $fs = new Filesystem();
        $filePath = public_path($file->path."/".$file->file_name);
        if ($fs->exists($filePath)) {
            $fs->delete($filePath);
            if ($this->rp->delete($id)) {
                return redirect('/admin/files')->withSuccess('File cancellato correttamente');
            }
        }
    }

    /**
     * download file
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($id)
    {
        $file = $this->rp->find($id);
        return response()->download(public_path($file->getPath()));
    }
}