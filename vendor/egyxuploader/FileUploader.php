<?php
/**
 * Created by PhpStorm.
 * User: abdulla
 * Date: 20/11/17
 * Time: 04:13 م
 */

namespace App;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;

class FileUploader
{
    /**
     * @var mixed|string
     */
    private $appKey = '';

    /**
     * @var string
     */
    private $folder = '';

    /**
     * @var UploadedFile|null
     */
    private $file = null;

    /**
     * @var Request|null
     */
    private $request = null;

    /**
     * @var string
     */
    private $featureName = '';

    private $subFolder = null;

    /**
     * @var string
     */
    private $fileKey = 'file';

    /**
     * @var array
     */
    private $acceptedMimes = [];

    /**
     * @var string
     */
    private $acceptedMethod = '';

    /**
     * FileUploader constructor.
     * @param Request $request
     * @param UploadedFile $file
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->appKey  = env('EGXUPLOADER_KEY');
    }

    /**
     * @return bool|int|mixed|\Symfony\Component\HttpFoundation\File\File
     */
    public function upload()
    {
        if(!is_array($response = $this->validateRequest($this->request))) return array(response('', $response));
        if(!is_array($response = $this->validateFile($this->file))) return array(response('', $response));
        return $this->doUpload($this->folder, $this->file);
    }

    /**
     * @param Request $request
     * @return array|int
     */
    public function validateRequest(Request $request)
    {
        if(!$request->hasHeader('access-key') || !$request->hasHeader('feature-key'))
            return Response::HTTP_NETWORK_AUTHENTICATION_REQUIRED;
        else if(!$request->isMethod($this->acceptedMethod))
            return Response::HTTP_METHOD_NOT_ALLOWED;
        else if($request->header('access-key') != $this->appKey)
            return Response::HTTP_FORBIDDEN;
        else if(!$request->has('feature')|| $request->feature != $this->featureName || !$request->has($this->fileKey))
            return Response::HTTP_BAD_REQUEST;

        $this->file = $request->file($this->fileKey);

        return [];
    }

    /**
     * @param UploadedFile $file
     * @return mixed
     */
    public function validateFile(UploadedFile $file)
    {
        if(!$file->isValid())
            return Response::HTTP_BAD_REQUEST;
        else if(!in_array($file->getMimeType(),$this->acceptedMimes))
            return Response::HTTP_NOT_ACCEPTABLE;

        return [];
    }

    /**
     * @param String|null $featureName
     * @param array $mimes
     * @param String $acceptedMethod
     */
    public function setFeatureProperties(String $featureName, array $mimes, String $acceptedMethod = 'POST', String $subfolder = 'egx')
    {
        $this->featureName  = $featureName;
        $this->folder       = $this->featureName;
        $this->acceptedMethod = $acceptedMethod;
        $this->subFolder    = $subfolder;
        $this->acceptedMime($mimes);

        return;
    }

    /**
     * @param array $mimes
     */
    private function acceptedMime(array $mimes)
    {
        foreach ($mimes as $mime)
        {
            array_push($this->acceptedMimes, $mime);
        }
        return;
    }

    /**
     * @param String $folder
     * @param UploadedFile $file
     * @return string
     */
    private function doUpload(String $folder, UploadedFile $file)
    {
        $link = '/' . $folder . '/' . $this->subFolder . '/' . rand(999999999,100000000) . '_' . rand(999999999,100000000) . '_' . rand(999999999,100000000) . '.' . explode('/',$file->getMimeType())[1];
        $file->move(__DIR__ . '/../public/' . $this->subFolder, $link);

        return $link;
    }
}