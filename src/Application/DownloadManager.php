<?php
declare(strict_types=1);
namespace App\Application;

use App\Application\Export\Export;
use App\Application\GeoConversion\GeoConverter;
use App\Domain\Entity\Activity;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use ZipArchive;

class DownloadManager
{

    /**
     * @var Export
     */
    private $exporter;

    /**
     * @var GeoConverter
     */
    private $geoConverter;

    /**
     * @var ZipArchive
     */
    private $zipper;

    /**
     * @var
     */
    private $fileName;

    public function __construct(Export $exporter, GeoConverter $geoConverter)
    {
        $this->exporter = $exporter;
        $this->geoConverter = $geoConverter;
        $this->zipper = new ZipArchive();
    }

    public function downloadCollection(int $collection, string $text_format = null, string $route_format = null)
    {
        if ($text_format) {

        }

        if ($route_format) {

        }
    }

    public function downloadActivity(Activity $activity, string $text_format = null, string $route_format = null, bool $ajax = false)
    {
        $textFile = null;
        $routeFile = null;

        $this->fileName = $activity->getId().'_'.$activity->getName() .'_'.date("U");

        if ($text_format) {
            $textFile = $this->buildTextFile($activity, $text_format);
        }

        if ($route_format) {
            $routeFile = $this->buildRouteFile($activity, $route_format);
        }

        $files = array($textFile,$routeFile);

        $this->buildZipResponse($files);

        if ($ajax) {
            return $this->fileName;
        } else {
            $response = $this->downloadZipFile($this->fileName);
            return $response;
        }


    }

    public function downloadZipFile($fileName)
    {
        $zipFile = '../temp/'.$fileName.'.zip';

        $response = new Response(file_get_contents($zipFile));
        $response->headers->set('Content-Type', 'application/zip');
        $response->headers->set('Content-Disposition', 'attachment;filename="' .$fileName .'.zip"');
        $response->headers->set('Content-length', filesize($zipFile));
        @unlink($zipFile);
        return $response;
    }

    private function buildTextFile(Activity $activity, string $text_format)
    {

        $textFilePath = '../temp/'.$this->fileName.'.'.$text_format;
        $text_file = $this->exporter->exportActivity($activity, $text_format, 'activity');

        $fsObject = new Filesystem();

        // Handle Files that are returned as a BinaryFileResponse
        if (is_a($text_file, 'Symfony\Component\HttpFoundation\BinaryFileResponse')) {
            $test = $text_file->getFile();
            $fsObject->rename($test->getPathname(), $textFilePath);
            return $textFilePath;
        } else {
            try {
                if(!$fsObject->exists($textFilePath)) {
                    $fsObject->touch($textFilePath);
                    $fsObject->chmod($textFilePath, 0777);
                    $fsObject->dumpFile($textFilePath, $text_file);
                }
            } catch (IOExceptionInterface $exception) {
                echo $exception->getPath();
            }
        }

        return $textFilePath;
    }

    private function buildRouteFile(Activity $activity, string $route_format)
    {
        $routeFilePath = '../temp/'.$this->fileName.'.'.$route_format;

        $converter = 'geom_to_'.$route_format;
        $route_file = $this->geoConverter->$converter($activity->getRoute());

        $fsObject = new Filesystem();
        try {
            if(!$fsObject->exists($routeFilePath)) {
                $fsObject->touch($routeFilePath);
                $fsObject->chmod($routeFilePath, 0777);
                $fsObject->dumpFile($routeFilePath, $route_file);
            }
        } catch (IOExceptionInterface $exception) {
            echo $exception->getPath();
        }
        return $routeFilePath;
    }

    private function buildZipResponse(array $files)
    {
        $zipName = '../temp/'.$this->fileName.'.zip';

        $this->zipper->open($zipName, $this->zipper::CREATE);
        foreach ($files as $file) {
            if(!is_null($file)) {
                $this->zipper->addFromString(basename($file), file_get_contents($file));
                @unlink($file);
            }
        }
        $this->zipper->close();

        return $zipName;
    }


}