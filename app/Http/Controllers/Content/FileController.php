<?php

namespace App\Http\Controllers\Content;

use App\Services\listGenerates;
use App\Models\Content\Tag;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Arr;
use Validator;
use App\Repositories\RepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Filesystem\Filesystem;
use App\Models\Content\Service;
use App\Models\Content\File;
use Intervention\Image\Facades\Image;

class FileController extends Controller {

    private $rp;
    private $fs;

    public function __construct(RepositoryInterface $rp, Filesystem $fs)  {
        $this->middleware('auth');
        $this->rp = $rp->setModel(File::class)->setSearchFields(['name','description','file_name']);
        $this->fs = $fs;
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
        session()->forget('product');
        $list->setPagination($files);
        return view('content.listFile')->with(compact('files','list'));
    }

    /**
     * Mostra il form per la creazione del file
     * @return bool|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create()
    {
        $file = new File();
        return view('content.editFile', compact('file'));
    }

    /**
     * Salva il file nel database dopo aver validato i dati
     * @param Request $request
     * @param Filesystem $fs
     * @return bool|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Filesystem $fs)
    {
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        $data['username'] = auth()->user()->username;
        if (!$request->has('path'))
            $data['path'] = config('shop.path-general-image');

        $data = $this->makeFile($data,$request);
        $this->validator($data)->validate();

        $file = $this->rp->create($data);

        // per consentire alla funzione di ritorno di eseguire delle operazioni sul file
        if(config('shop.return_session_file') && (strpos($request->get('return'), 'admin/files') !== true)) {
            $request->session()->put('shop_id_file',$file->id);
        }

        return redirect($request->get('return'))->withSuccess('File aggiunto correttamente.');
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
        $tags = $this->rp->setModel('App\Models\Content\Tag')->pluck();
        $vocabularies = $this->rp->listVocabularies($file);
        return view('content.editFile', compact('file','tags','vocabularies'));
    }

    /**
     * Aggiorna il file
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update($id, Request $request, Filesystem $fs)
    {
        $data = $request->all(); $data['id'] = $id;
        $data = $this->makeFile($data,$request);
        $this->validator($data)->validate();
        if ($this->rp->update($id,$data)) {
            return redirect($request->get('return'))->withSuccess('File modificato correttamente.');
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
        $filePath = $file->getPath()."/".$file->file_name;
        $fileThumbPath =  $file->getPath()."/".config('lfm.thumb_folder_name')."/".$file->file_name;
        if ($this->fs->exists($filePath)) {
            $this->fs->delete([$filePath,$fileThumbPath]);
        }
        if ($this->rp->delete($id)) {
            $this->rp->getModel('App\Models\Content\Widget_page')
                ->join('widgets', 'widgets.id', '=', 'widgets_pages.widget_id')
                ->where('widgets.init','imageViewer')
                ->where('widgets_pages.setting', 'LIKE', '%'."\"file_id\":\"$id\"".'%')->delete();

            return redirect(request()->get('return'))->withSuccess('File cancellato correttamente');
        }
    }

    /**
     * download file
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($id)
    {
        $file = $this->rp->find($id);
        $file->increment('hits');
        return response()->download($file->getPath()."/".$file->file_name);
    }

    /**
     * visualizza file
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function viewFile($id)
    {
        $file = $this->rp->find($id);
        $file->increment('hits');
        return response()->file($file->getPath()."/".$file->file_name);
    }

    /**
     * Salva tags e le categorie
     * @param $id
     * @return mixed
     */
    public function saveCategories($id) {
        $this->rp->saveCategories($id);
        return redirect(request()->get('return'))->withSuccess('File aggiornato correttamente');
    }


    private function makeFile($data, Request $request) {

        if ($request->file('fileUploaded')) {

            if (!empty($data['id'])) {
                $file = $this->rp->find($data['id']);
                $fullpath = $file->getPath();
                $path = $file->path;
            } else {
                $fullpath = storage_path('app/public')."/".config('shop.path-general-image');
                $path = config('shop.path-general-image');
            }

            $data['file_name'] = $request->fileUploaded->getClientOriginalName();
            if(empty($data['name'])) $data['name'] = $data['file_name'];
            $filePath = $fullpath."/".$data['file_name'];
            $fileThumbPath =  $fullpath."/".config('shop.thumb_folder_name')."/".$data['file_name'];

            if ($this->fs->exists($filePath)) {
                // sovrascrivo di default ?
                $this->fs->delete([$filePath, $fileThumbPath]);
            }

            $data['slug'] = $this->rp->setModel(File::class)->makeSlug($data['file_name']);
            if (!isset($data['path'])) $data['path'] = $path;
            $data['mime_type'] = $request->fileUploaded->getMimeType();
            $data['extension'] = $request->fileUploaded->guessExtension();
            $data['size'] = $request->fileUploaded->getClientSize();

            $request->fileUploaded->move($fullpath."/",$data['file_name']);
            if ($this->isImage($data['mime_type'])) {
                $this->makeThumb($fullpath."/",$data['file_name']);
            }
        }
        return $data;
    }

    /**
     * Verifica se il file uploaded è di tipo immagine
     * @return bool
     */
    private function isImage($mime_type) {
        if(substr($mime_type, 0, 5) == 'image') {
            return true;
        }
    }

    /**
     * Crea una miniatura del file img
     * @param $path
     * @param $file
     */
    private function makeThumb($path,$file)
    {
        $thumbFolder = $path.config('lfm.thumb_folder_name');
        $this->createDir($thumbFolder);
        Image::make($path.$file)
            ->fit(config('lfm.thumb_img_width', 200), config('lfm.thumb_img_height', 200))
            ->save($thumbFolder."/".$file);
    }

    /**
     * Crea la dir se non esiste
     * @param $path
     */
    private function createDir($path)
    {
        if (!$this->fs->exists($path)) {
            $this->fs->makeDirectory($path, config('lfm.create_folder_mode', 0755), true, true);
        }
    }

}
