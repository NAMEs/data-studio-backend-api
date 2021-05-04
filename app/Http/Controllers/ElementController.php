<?php

namespace App\Http\Controllers;

use App\Repository\ElementRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ElementController extends CrudController {
    /**
     * @var ElementRepository
     */
    protected $repository;

    public function __construct(ElementRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * @param Request $request
     * @param string  $id
     *
     * @return mixed
     * @throws \Exception
     */
    public function updateConfig(Request $request, $id) {
        return $this->repository->updateConfig($id, $request->all());
    }

    /**
     * @param Request $request
     * @param string  $id
     *
     * @return mixed
     * @throws \Exception
     */
    public function updateImageConfig(Request $request, $id) {
        try {
            $image = $request->file('image');
            $element = $this->repository->getOne($id);
            if (
                !empty($oldPath = $element->element_config['src'])
                && strpos($oldPath, '/image/') === 0
                && Storage::disk()->exists(substr($oldPath, 1))
            ) {
                $oldPath = substr($oldPath, 1);
                $tmpPath = $oldPath . '.tmp';
                Storage::disk()->move($oldPath, $tmpPath);
            }

            $fileExtension = $image->getClientOriginalExtension();
            $filename = md5($element->element_id . time()) . '.' . $fileExtension;

            $path = $image->storeAs('image', $filename, ['visibility' => 'public']);

            $element = $this->repository->updateConfig($id, [
                'src' => '/' . $path,
            ]);
            Storage::disk()->delete($tmpPath);
            return $element;
        } catch (\Exception $e) {
            if ($oldPath && $tmpPath) {
                Storage::disk()->move($tmpPath, $oldPath);
            }
            abort(500, $e->getMessage());
        }
    }
}
