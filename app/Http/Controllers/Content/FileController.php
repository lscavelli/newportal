<?php

namespace App\Http\Controllers\Content;

use App\Services\listGenerates;
use App\Models\Content\Tag;
use Illuminate\Http\Request;
use App\Http\Requests;
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
        $filePath = public_path($file->path."/".$file->file_name);
        $fileThumbPath =  public_path($file->path."/".config('lfm.thumb_folder_name')."/".$file->file_name);
        if ($this->fs->exists($filePath)) {
            $this->fs->delete([$filePath,$fileThumbPath]);
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
        $file->increment('hits');
        return response()->download(public_path($file->getPath()));
    }

    /**
     * visualizza file
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function viewFile($id)
    {
        $file = $this->rp->find($id);
        $file->increment('hits');
        return response()->file(public_path($file->getPath()));
    }

    /**
     * Salva tags e le categorie
     * @param $id
     * @return mixed
     */
    public function saveCategories($id) {
        $this->rp->saveCategories($id);
        return redirect('admin/files')->withSuccess('File aggiornato correttamente');
    }

    /**
     * Sostituisce il file
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function replace($id, Request $request) {
        $file = $this->rp->find($id);
        $data['avatar'] = null;
        if ($request->file('fileUploaded')) {
            $filePath = public_path($file->path."/".$file->file_name);
            $fileThumbPath =  public_path($file->path."/".config('lfm.thumb_folder_name')."/".$file->file_name);
            if ($this->fs->exists($filePath)) {
                $this->fs->delete([$filePath, $fileThumbPath]);
                //$this->rp->delete($id);
                $data['file_name'] = $request->fileUploaded->getClientOriginalName();
                $data['mime_type'] = $request->fileUploaded->getMimeType();
                $data['extension'] = $request->fileUploaded->guessExtension();
                $data['size'] = $request->fileUploaded->getClientSize();
                $data['user_id'] = auth()->user()->id;
                $data['username'] = auth()->user()->username;
                $data['id'] = $id;
                $this->validator($data)->validate();
                if ($this->rp->update($id, $data)) {
                    $request->fileUploaded->move(public_path($file->path."/"),$data['file_name']);
                    if ($this->isImage($data['mime_type'])) {
                        $this->makeThumb(public_path($file->path."/"),$data['file_name']);
                    }
                    return redirect('/admin/files/' . $file->id . "/edit")->withSuccess('File sostituito correttamente');
                }
            }
        }
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