<?php
/**
 * @author Mari Doucet
 * Date: 14/08/18
 * Time: 11:24
 */

namespace App\Service;

use App\Entity\Medium;
use Symfony\Component\HttpFoundation\RequestStack;

class FileManager
{
    protected $rsg;
    protected $requestStack;

    public function __construct(RandomStringGenerator $rsg, RequestStack $requestStack)
    {
        $this->rsg = $rsg;
        $this->requestStack = $requestStack;
    }

    public function upload(Medium $medium)
    {
        $file = $medium->getUploadedFile();
        $baseUrl = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost().'/';

        $folder = 'uploads/media/';
        $generatedName = $this->rsg->generate(15);
        $extension = $file->guessExtension();

        $medium->setFilename($generatedName.'.'.$extension);
        $medium->setRelativePath($folder.$medium->getFileName());
        $medium->setPath($baseUrl.$folder.$medium->getFileName());
        $medium->setFiletype($file->getClientMimeType());

        $file->move($folder, $medium->getRelativePath());
        $medium->setUploadedFile(null);
    }

    public function remove(Medium $medium)
    {
        if (file_exists($medium->getRelativePath())) {
            unlink($medium->getRelativePath());
        }
    }
}
