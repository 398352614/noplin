<?php


namespace App\Services;


use App\Constants\Constant;
use App\Exceptions\BusinessException;
use App\Models\File;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService extends BaseService
{
    private Filesystem $disk;

    /**
     * AuthService constructor.
     * @param File $model
     */
    public function __construct(File $model)
    {
        $this->disk = Storage::disk('public');
        parent::__construct($model);
    }

    /**
     * 文件上传
     * @param $data
     * @return array
     * @throws BusinessException
     */
    #[ArrayShape(['name' => "mixed", 'path' => "string", 'size' => "int", 'extension' => "string"])]
    public function store($data): array
    {
        $path = auth()->user()['id'] . DIRECTORY_SEPARATOR . $data['type'];
        /** @var UploadedFile $file */
        $file = $data['file'];
        $name = date('YmdHis') . uniqid() . '.' . $file->getClientOriginalExtension();
        try {
            $rowCount = $this->disk->putFileAs($path, $file, $name);
            if ($rowCount === false) {
                throw new BusinessException('图片上传失败，请重新操作');
            }
            parent::create([
                'name' => $data['name'],
                'user_id' => auth()->user()['id'],
                'type' => Constant::FILE_TYPE_1,
                'path' => $path . '/' . $name,
            ]);
        } catch (Exception) {
            throw new BusinessException('图片上传抛错，请重新操作');
        }
        return [
            'name' => $data['name'],
            'path' => $this->disk->url($path . '/' . $name),
            'size' => $file->getSize(),
            'extension' => $file->getClientOriginalExtension()
        ];
    }

    /**
     * 删除文件
     * @param $id
     * @return mixed
     * @throws BusinessException
     * @throws BindingResolutionException
     */
    public function destroy($id): mixed
    {
        $info = $this->getInfo($id, throw: false);
        if (!empty($info)) {
            $this->disk->delete($info['path']);
        }
        return parent::remove($id);
    }
}